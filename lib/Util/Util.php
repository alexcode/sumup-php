<?php

namespace Sumup\Util;

abstract class Util
{

    /**
    * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
    * @param    string   $str                     String in underscore format
    * @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
    * @return   string                              $str translated into camel caps
    */
    public static function toCamelCase($str, $capitalise_first_char = false) {
        if($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }
        $func = function($c) {
            return strtoupper($c[1]);
        };
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }


    /**
     * Recursively converts the PHP Sumup object to an array.
     *
     * @param array $values The PHP Sumup object to convert.
     * @return array
     */
    public static function convertSumupObjectToArray($object)
    {
        $results = [];
        foreach ($object->_keys as $property) {
            $v = $object->{$property};
            if ($v instanceof SumupObject) {
                $results[$property] = $v->__toArray();
            } elseif (is_array($v)) {
                $results[$property] = self::convertSumupObjectToArray($v);
            } else {
                $results[$property] = $v;
            }
        }
        return $results;
    }

    /**
     * @param array $arr A map of param keys to values.
     * @param string|null $prefix
     *
     * @return string A querystring, essentially.
     */
    public static function urlEncode($arr, $prefix = null)
    {
        if (!is_array($arr)) {
            return $arr;
        }
        $r = array();
        foreach ($arr as $k => $v) {
            if (is_null($v)) {
                continue;
            }
            if ($prefix) {
                if ($k !== null && (!is_int($k) || is_array($v))) {
                    $k = $prefix."[".$k."]";
                } else {
                    $k = $prefix."[]";
                }
            }
            if (is_array($v)) {
                $enc = self::urlEncode($v, $k);
                if ($enc) {
                    $r[] = $enc;
                }
            } else {
                $r[] = urlencode($k)."=".urlencode($v);
            }
        }
        return implode("&", $r);
    }
}
