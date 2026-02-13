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
 * @package    Zend_Pdf
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * PDF reference object context
 * Reference context is defined by PDF parser and PDF Refernce table
 *
 * @category   Zend
 * @package    Zend_Pdf
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Pdf_Element_Reference_Context
{
    /**
     * Object constructor
     *
     * @param Zend_Pdf_StringParser $_stringParser
     * @param Zend_Pdf_Element_Reference_Table $_refTable
     */
    public function __construct(
        /**
         * PDF parser object.
         */
        private readonly Zend_Pdf_StringParser $_stringParser,
        /**
         * Reference table
         */
        private readonly Zend_Pdf_Element_Reference_Table $_refTable
    )
    {
    }


    /**
     * Context parser
     *
     * @return Zend_Pdf_StringParser
     */
    public function getParser()
    {
        return $this->_stringParser;
    }


    /**
     * Context reference table
     *
     * @return Zend_Pdf_Element_Reference_Table
     */
    public function getRefTable()
    {
        return $this->_refTable;
    }
}

