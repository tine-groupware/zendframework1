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
 * @author     Lars Kneschke <l.kneschke@metaways.de>
 * @copyright  Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * Zend_Http_Client
 */
require_once 'Zend/Http/Client.php';

/**
 * @category   Zend
 * @package    Zend_Service
 * @author     Lars Kneschke <l.kneschke@metaways.de>
 * @copyright  Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Service_Nominatim
{
    /**
     * the url of the Tine 2.0 installation
     * 
     * @var string (for example http://demo.tine20.org/index.php)
     */
    protected $_url = 'http://nominatim.openstreetmap.org/';
    
    protected $_httpClient;
    
    protected $_country;
    
    /**
     * constructor for Zend_Service_Tine20
     * @param string           $url         the url of the Tine 2.0 installation
     * @param Zend_Http_Client $httpClient
     * @return void
     */
    public function __construct($url = null, $httpClient = null)
    {
        if($url !== NULL) {
            $this->_url = $url;
        }

        if(!$httpClient instanceof Zend_Http_Client) {
            $httpClient = new Zend_Http_Client();
        }
        $this->_httpClient = $httpClient;
    }    
    
    public function setCountry($country)
    {
        $this->_country = $country;
        
        return $this;
    }

    public function setVillage($village)
    {
        $this->_village = $village;
        
        return $this;
    }

    public function setPostcode($postcode)
    {
        $this->_postcode = $postcode;
        
        return $this;
    }

    public function setStreet($street)
    {
        $this->_street = $street;
        
        return $this;
    }

    public function setNumber($number)
    {
        $this->_number = $number;
        
        return $this;
    }
    
    public function reset()
    {
        $this->_httpClient->resetParameters();
        $this->_country = null;
        $this->_village = null;
        $this->_postcode = null;
        $this->_street = null;
        $this->_number = null;
        
        return $this;
    }

    /**
     * search for places
     * 
     * @return Zend_Service_Nominatim_ResultSet
     */
    public function search()
    {
        $this->_httpClient->resetParameters();

        $queryParts = array();
        
        if(!empty($this->_street)) {
            $queryParts[] = $this->_street;
        }
        
        if(!empty($this->_number)) {
            $queryParts[] = $this->_number;
        }
        
        if(!empty($this->_postcode)) {
            $queryParts[] = $this->_postcode;
        }
        
        if(!empty($this->_village)) {
            $queryParts[] = $this->_village;
        }
        
        if(!empty($this->_country)) {
            $queryParts[] = $this->_country;
        }

        $this->_httpClient->setUri($this->_url . 'search');
        $this->_httpClient->setParameterGet('q', implode(',', $queryParts));
        $this->_httpClient->setParameterGet('format', 'xml');
        $this->_httpClient->setParameterGet('addressdetails', 1);
        $this->_httpClient->setParameterGet('osm_type', 'way');
        
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' Connecting to Nominatim with uri ' . $this->_httpClient->getUri(true));
        
        $response = $this->_httpClient->request();
        
        $xml = new SimpleXMLElement($response->getBody());

        $result = new Zend_Service_Nominatim_ResultSet($xml);

        return $result;
    }    

    public function reverse()
    {
        $this->_httpClient->resetParameters();
        
        return $this;
    }    
}
