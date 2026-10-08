<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Server
 * @subpackage Method
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Method callback metadata
 *
 * @category   Zend
 * @package    Zend_Server
 * @subpackage Method
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Server_Method_Callback
{
    /**
     * @var string Class name for class method callback
     */
    protected $c;

    /**
     * @var string Function name for function callback
     */
    protected $f;

    /**
     * @var string Method name for class method callback
     */
    protected $m;

    /**
     * @var string Callback type
     */
    protected $t;

    /**
     * @var array Valid callback types
     */
    protected $_types = ['function', 'static', 'instance'];

    /**
     * Constructor
     *
     * @param  null|array $options
     * @return void
     */
    public function __construct($options = null)
    {
        if ((null !== $options) && is_array($options))  {
            $this->setOptions($options);
        }
    }

    /**
     * Set object state from array of options
     *
     * @param  array $options
     * @return Zend_Server_Method_Callback
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst((string) $key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Set callback class
     *
     * @param  string $class
     * @return Zend_Server_Method_Callback
     */
    public function setClass($class)
    {
        if (is_object($class)) {
            $class = $class::class;
        }
        $this->c = $class;
        return $this;
    }

    /**
     * Get callback class
     *
     * @return string|null
     */
    public function getClass()
    {
        return $this->c;
    }

    /**
     * Set callback function
     *
     * @param  string $function
     * @return Zend_Server_Method_Callback
     */
    public function setFunction($function)
    {
        $this->f = (string) $function;
        $this->setType('function');
        return $this;
    }

    /**
     * Get callback function
     *
     * @return null|string
     */
    public function getFunction()
    {
        return $this->f;
    }

    /**
     * Set callback class method
     *
     * @param  string $method
     * @return Zend_Server_Method_Callback
     */
    public function setMethod($method)
    {
        $this->m = $method;
        return $this;
    }

    /**
     * Get callback class  method
     *
     * @return null|string
     */
    public function getMethod()
    {
        return $this->m;
    }

    /**
     * Set callback type
     *
     * @param  string $type
     * @return Zend_Server_Method_Callback
     * @throws Zend_Server_Exception
     */
    public function setType($type)
    {
        if (!in_array($type, $this->_types)) {
            require_once 'Zend/Server/Exception.php';
            throw new Zend_Server_Exception('Invalid method callback type  passed to ' . self::class . '::' . __METHOD__);
        }
        $this->t = $type;
        return $this;
    }

    /**
     * Get callback type
     *
     * @return string
     */
    public function getType()
    {
        return $this->t;
    }

    /**
     * Cast callback to array
     *
     * @return array
     */
    public function toArray()
    {
        $type = $this->getType();
        $array = [
            'type' => $type,
        ];
        if ('function' == $type) {
            $array['function'] = $this->getFunction();
        } else {
            $array['class']  = $this->getClass();
            $array['method'] = $this->getMethod();
        }
        return $array;
    }

    public function __sleep(): array
    {
        $toSerialize = [
            't',
        ];
        if (null === $this->f) {
            $toSerialize[] = 'c';
            $toSerialize[] = 'm';
        } else {
            $toSerialize[] = 'f';
        }
        return $toSerialize;
    }
}
