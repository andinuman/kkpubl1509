<?php

/**
 * Created by PhpStorm.
 * User: sikasep
 * Date: 10/22/15
 * Time: 6:24 PM
 */
class DES
{
    public static function des_encrypt($key,$string) {
        $result = '';
        for($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }
}