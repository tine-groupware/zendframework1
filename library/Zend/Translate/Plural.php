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
 * @package    Zend_Locale
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Utility class for returning the plural rules according to the given locale
 *
 * @category   Zend
 * @package    Zend_Locale
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Translate_Plural
{
    /**
     * Manual rule to use
     *
     * @var string
     */
    protected static $_plural = [];

    /**
     * Returns the plural definition to use
     *
     * @param  integer $number Number for plural selection
     * @param  string  $locale Locale to use
     * @return integer Plural number to use
     */
    public static function getPlural($number, $locale)
    {
        if ($locale == "pt_BR") {
            // temporary set a locale for brasilian
            $locale = "xbr";
        }

        if (strlen($locale) > 3) {
            $locale = substr($locale, 0, -strlen(strrchr($locale, '_')));
        }

        if (isset(self::$_plural[$locale])) {
            $return = call_user_func(self::$_plural[$locale], $number);

            if (!is_int($return) || ($return < 0)) {
                $return = 0;
            }

            return $return;
        }

        return match ($locale) {
            'az', 'bo', 'dz', 'id', 'ja', 'jv', 'ka', 'km', 'kn', 'ko', 'ms', 'th', 'tr', 'vi', 'zh' => 0,
            'af', 'bn', 'bg', 'ca', 'da', 'de', 'el', 'en', 'eo', 'es', 'et', 'eu', 'fa', 'fi', 'fo', 'fur', 'fy', 'gl', 'gu', 'ha', 'he', 'hu', 'is', 'it', 'ku', 'lb', 'ml', 'mn', 'mr', 'nah', 'nb', 'ne', 'nl', 'nn', 'no', 'om', 'or', 'pa', 'pap', 'ps', 'pt', 'so', 'sq', 'sv', 'sw', 'ta', 'te', 'tk', 'ur', 'zu' => ($number == 1) ? 0 : 1,
            'am', 'bh', 'fil', 'fr', 'gun', 'hi', 'ln', 'mg', 'nso', 'xbr', 'ti', 'wa' => (($number == 0) || ($number == 1)) ? 0 : 1,
            'be', 'bs', 'hr', 'ru', 'sr', 'uk' => (($number % 10 == 1) && ($number % 100 != 11)) ? 0 : ((($number % 10 >= 2) && ($number % 10 <= 4) && (($number % 100 < 10) || ($number % 100 >= 20))) ? 1 : 2),
            'cs', 'sk' => ($number == 1) ? 0 : ((($number >= 2) && ($number <= 4)) ? 1 : 2),
            'ga' => ($number == 1) ? 0 : (($number == 2) ? 1 : 2),
            'lt' => (($number % 10 == 1) && ($number % 100 != 11)) ? 0 : ((($number % 10 >= 2) && (($number % 100 < 10) || ($number % 100 >= 20))) ? 1 : 2),
            'sl' => ($number % 100 == 1) ? 0 : (($number % 100 == 2) ? 1 : ((($number % 100 == 3) || ($number % 100 == 4)) ? 2 : 3)),
            'mk' => ($number % 10 == 1) ? 0 : 1,
            'mt' => ($number == 1) ? 0 : ((($number == 0) || (($number % 100 > 1) && ($number % 100 < 11))) ? 1 : ((($number % 100 > 10) && ($number % 100 < 20)) ? 2 : 3)),
            'lv' => ($number == 0) ? 0 : ((($number % 10 == 1) && ($number % 100 != 11)) ? 1 : 2),
            'pl' => ($number == 1) ? 0 : ((($number % 10 >= 2) && ($number % 10 <= 4) && (($number % 100 < 12) || ($number % 100 > 14))) ? 1 : 2),
            'cy' => ($number == 1) ? 0 : (($number == 2) ? 1 : ((($number == 8) || ($number == 11)) ? 2 : 3)),
            'ro' => ($number == 1) ? 0 : ((($number == 0) || (($number % 100 > 0) && ($number % 100 < 20))) ? 1 : 2),
            'ar' => ($number == 0) ? 0 : (($number == 1) ? 1 : (($number == 2) ? 2 : ((($number >= 3) && ($number <= 10)) ? 3 : ((($number >= 11) && ($number <= 99)) ? 4 : 5)))),
            default => 0,
        };
    }

    /**
     * Set's a new plural rule
     *
     * @param string $rule   Callback which acts as rule
     * @param string $locale Locale which is used for this callback
     * @return null
     */
    public static function setPlural($rule, $locale)
    {
        if ($locale == "pt_BR") {
            // temporary set a locale for brasilian
            $locale = "xbr";
        }

        if (strlen($locale) > 3) {
            $locale = substr($locale, 0, -strlen(strrchr($locale, '_')));
        }

        if (!is_callable($rule)) {
            require_once 'Zend/Translate/Exception.php';
            throw new Zend_Translate_Exception('The given rule can not be called');
        }

        self::$_plural[$locale] = $rule;
    }
}
