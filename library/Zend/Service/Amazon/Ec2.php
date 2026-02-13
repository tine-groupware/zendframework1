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
 * @package    Zend_Service
 * @subpackage Amazon
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Amazon Ec2 Interface to allow easy creation of the Ec2 Components
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Amazon
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Service_Amazon_Ec2
{
    /**
     * Factory method to fetch what you want to work with.
     *
     * @param string $section           Create the method that you want to work with
     * @param string $key               Override the default aws key
     * @param string $secret_key        Override the default aws secretkey
     * @throws Zend_Service_Amazon_Ec2_Exception
     * @return object
     */
    public static function factory($section, $key = null, $secret_key = null)
    {
        $class = match (strtolower($section)) {
            'keypair' => 'Zend_Service_Amazon_Ec2_Keypair',
            'eip', 'elasticip' => 'Zend_Service_Amazon_Ec2_Elasticip',
            'ebs' => 'Zend_Service_Amazon_Ec2_Ebs',
            'availabilityzones', 'zones' => 'Zend_Service_Amazon_Ec2_Availabilityzones',
            'ami', 'image' => 'Zend_Service_Amazon_Ec2_Image',
            'instance' => 'Zend_Service_Amazon_Ec2_Instance',
            'security', 'securitygroups' => 'Zend_Service_Amazon_Ec2_Securitygroups',
            default => throw new Zend_Service_Amazon_Ec2_Exception('Invalid Section: ' . $section),
        };

        if (!class_exists($class)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($class);
        }
        return new $class($key, $secret_key);
    }
}

