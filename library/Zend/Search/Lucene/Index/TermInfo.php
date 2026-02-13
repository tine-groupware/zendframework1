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
 * @package    Zend_Search_Lucene
 * @subpackage Index
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * A Zend_Search_Lucene_Index_TermInfo represents a record of information stored for a term.
 *
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Index
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Search_Lucene_Index_TermInfo
{
    /**
     * @param int $docFreq
     * @param int $freqPointer
     * @param int $proxPointer
     * @param int $skipOffset
     * @param int $indexPointer
     */
    public function __construct(
        /**
         * The number of documents which contain the term.
         */
        public $docFreq,
        /**
         * Data offset in a Frequencies file.
         */
        public $freqPointer,
        /**
         * Data offset in a Positions file.
         */
        public $proxPointer,
        /**
         * ScipData offset in a Frequencies file.
         */
        public $skipOffset,
        /**
         * Term offset of the _next_ term in a TermDictionary file.
         * Used only for Term Index
         */
        public $indexPointer = null
    )
    {
    }
}

