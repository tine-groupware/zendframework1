<?php
/**
 * Tine 2.0
 *
 * @package     Tinebase
 * @subpackage  Backend
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Paul Mehrer <p.mehrer@metaways.de>
 * @copyright   Copyright (c) 2018-2019 Metaways Infosystems GmbH (http://www.metaways.de)
 */

if (defined('Redis::SERIALIZER_IGBINARY')) {
    class Zend_RedisProxy_C1 { const SERIALIZER_IGBINARY = Redis::SERIALIZER_IGBINARY;}
} else {
    class Zend_RedisProxy_C1 {}
}

if (defined('Redis::OPT_SCAN')) {
    class Zend_RedisProxy_C2 extends Zend_RedisProxy_C1 { const OPT_SCAN = Redis::OPT_SCAN;}
} else {
    class Zend_RedisProxy_C2 extends Zend_RedisProxy_C1 {}
}

if (defined('Redis::SCAN_NORETRY') && defined('Redis::SCAN_RETRY')) {
    class Zend_RedisProxy_C3 extends Zend_RedisProxy_C2 {
        const SCAN_NORETRY = Redis::SCAN_NORETRY;
        const SCAN_RETRY   = Redis::SCAN_RETRY;
    }
} else {
    class Zend_RedisProxy_C3 extends Zend_RedisProxy_C2 {}
}

/**
 * A redis proxy that retries redis communication in case of failures
 *
 * @package     Tinebase
 * @subpackage  Backend
 */
class Zend_RedisProxy extends Zend_RedisProxy_C3
{
    const AFTER                 = Redis::AFTER;
    const BEFORE                = Redis::BEFORE;

    /**
     * Options
     */
    const OPT_SERIALIZER        = Redis::OPT_SERIALIZER;
    const OPT_PREFIX            = Redis::OPT_PREFIX;
    const OPT_READ_TIMEOUT      = Redis::OPT_READ_TIMEOUT;

    /**
     * Serializers
     */
    const SERIALIZER_NONE       = Redis::SERIALIZER_NONE;
    const SERIALIZER_PHP        = Redis::SERIALIZER_PHP;

    /**
     * Multi
     */
    const ATOMIC                = Redis::ATOMIC;
    const MULTI                 = Redis::MULTI;
    const PIPELINE              = Redis::PIPELINE;

    /**
     * Type
     */
    const REDIS_NOT_FOUND       = Redis::REDIS_NOT_FOUND;
    const REDIS_STRING          = Redis::REDIS_STRING;
    const REDIS_SET             = Redis::REDIS_SET;
    const REDIS_LIST            = Redis::REDIS_LIST;
    const REDIS_ZSET            = Redis::REDIS_ZSET;
    const REDIS_HASH            = Redis::REDIS_HASH;

    /**
     * @var Redis
     */
    protected $_redis = null;

    protected $_connectionMethod = null;
    protected $_connectionArguments = null;

    protected $_inMulti = false;
    protected $_multiPipe = [];

    /**
     * Tinebase_Backend_Redis constructor.
     */
    public function __construct()
    {
        $this->_redis = new Redis();
    }

    public function scan(&$iterator, $pattern = null, $count = 0)
    {
        $tries = 0;
        while (true) {
            try {
                return $this->_redis->scan($iterator, $pattern, $count);
            } catch (RedisException $re) {
                if (true === $this->_inMulti || ++$tries > 5) {
                    throw $re;
                }

                // give Redis 100ms and try again
                usleep(100000);

                try {
                    $this->_redis->ping();
                } catch (RedisException $re) {
                    $this->_reconnect();
                }
            }
        }
    }

    /**
     * @param $_name
     * @param array $_arguments
     * @return mixed
     * @throws RedisException
     */
    public function __call($_name, array $_arguments)
    {
        $tries = 0;

        switch ($_name) {
            case 'connect':
            case 'open':
            case 'pconnect':
                if (null !== $this->_connectionMethod) {
                    throw new RedisException('already connected');
                }

                $this->_connectionMethod = $_name;
                $this->_connectionArguments = $_arguments;
                $this->_inMulti = false;
                break;

            case 'multi':
                $this->_inMulti = true;
                break;

            case 'discard':
                $this->_inMulti = false;
                break;

            case 'flushDB':
            case 'flushAll':
                if (defined(__CLASS__ . '::OPT_SCAN')) {
                    throw new RedisException($_name .
                        ' is forbidden, it well may be a shared redis, don\'t do it ever!');
                }
                break;

            default:
                if (true === $this->_inMulti) {
                    if ('exec' === $_name) {
                        $this->_inMulti = false;
                    }
                    $result = call_user_func_array([$this->_redis, $_name], $_arguments);
                    if ($result === $this->_redis) {
                        return $this;
                    }
                    return $result;
                }
        }

        while (true) {
            try {
                $result = call_user_func_array([$this->_redis, $_name], $_arguments);
                if ($result === $this->_redis) {
                    return $this;
                } elseif (false === $result) {
                    if (false === $this->_redis->ping()) {
                        throw new RedisException();
                    }
                }
                return $result;
            } catch (RedisException $re) {
                if (++$tries > 5) {
                    throw $re;
                }

                // give Redis 100ms and try again
                usleep(100000);

                switch ($_name) {
                    case 'connect':
                    case 'open':
                    case 'pconnect':
                        continue 2;
                }

                try {
                    if (false === $this->_redis->ping()) {
                        $this->_reconnect();
                    }
                } catch (RedisException $re) {
                    $this->_reconnect();
                }
            }
        }
    }

    protected function _reconnect()
    {
        $cM = $this->_connectionMethod;
        $cA = $this->_connectionArguments;
        $this->close();
        $this->_connectionMethod = $cM;
        $this->_connectionArguments = $cA;
        try {
            call_user_func_array([$this->_redis, $this->_connectionMethod], $this->_connectionArguments);
        } catch (RedisException $re) {}
    }

    public function close()
    {
        $this->_connectionMethod = null;
        $this->_connectionArguments = null;
        try {
            $this->_redis->close();
        } catch (RedisException $re) {}
        $this->_redis = new Redis();
    }
}