<?php

/**
 * Created by PhpStorm.
 * User: sikasep
 * Date: 1/20/16
 * Time: 10:47 PM
 */
class rc4
{
    public static function rc4enc($key, $str) {
        $s = array();
        for ($i = 0; $i < 256; $i++) {
            $s[$i] = $i;
        }
        $j = 0;
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
        $i = 0;
        $j = 0;
        $res = '';
        for ($y = 0; $y < strlen($str); $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
            $res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
        }
        return $res;
    }
    public static function rc4dec($key, $encrypt) {
        $s = array();
        for ($i = 0; $i < 256; $i++) {
            $s[$i] = $i;
        }
        $j = 0;
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
        $i = 0;
        $j = 0;
        $res = '';
        for ($y = 0; $y < strlen($encrypt); $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
            $res .= $encrypt[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
        }
        return $res;
    }
    public static function isValid($encode, $isfile = false) {
        if ($isfile)
            return preg_match('/[a-zA-Z0-9\/+]+={0,2}\|[a-zA-Z0-9\/+]+={0,2}/', $encode);
        return preg_match('/[a-zA-Z0-9\/+]+={0,2}/', $encode);
    }
}