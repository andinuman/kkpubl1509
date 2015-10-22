<?php

/**
 * Created by PhpStorm.
 * User: sikasep
 * Date: 10/22/15
 * Time: 3:06 AM
 */
class des_enc
{
    const ENCRYPT = 1;
    const DECRYPT = 2;
    const BYTES_BLOCK = 8; // 64 bits
    const BYTES_KEY = 8; // 64 bits
    protected $operation = self::ENCRYPT;

    public function __construct($key)
    {
        $this->initTables();
        $this->blockSize(self::BYTES_BLOCK);
        $this->keyPermutation();
    }

    protected function __construct1($cipher, $key, $key_byte_sz)
    {
        self::__construct($cipher, $key, $key_byte_sz);

        $this->initTables();
    }

    public function encrypt(&$text)
    {
//        $this->operation(parent::ENCRYPT);
        return $this->des($text);
    }

    protected function des(&$data)
    {
        $l = array();
        $r = array();

        $data = $this->ip($data);

        $l[0] = substr($data, 0, 32);
        $r[0] = substr($data, 32, 32);

        for($n = 1; $n <= 16; ++$n)
        {
            $l[$n] = $r[$n-1];

            if(self::DECRYPT)
                $f = $this->F($r[$n-1], $this->sub_keys[16-$n]);
            else
                $f = $this->F($r[$n-1], $this->sub_keys[$n-1]);

            // XOR F with Ln
            $r[$n] = $this->xorBin($l[$n-1], $f);
        }


        $data = $r[16].$l[16];

        $data = $this->fp($data);
        $data = self::bin2Str($data);

        return true;
    }

    // Langkah pertama generate String dan key ke dalam bentuk biner
    public static function hex2Bin($hex)
    {
        if(strlen($hex) % 2)
            $hex = "0$hex";

        $parts = str_split($hex, 2);
        $parts = array_map(function($v) {
            $v = base_convert($v, 16, 2);
            return str_pad($v, 8, "0", STR_PAD_LEFT);
        }, $parts);

        return implode("", $parts);
    }

    public static function bin2Hex($bin)
    {
        $parts = str_split($bin, 8);

        $parts = array_map(function($v) {
            $v = str_pad($v, 8, "0", STR_PAD_LEFT);
            $v = dechex(bindec($v));
            return str_pad($v, 2, "0", STR_PAD_LEFT);
        }, $parts);

        return implode("", $parts);
    }
    public static function hex2Dec($hex)
    {
        return hexdec($hex);
    }
    public static function hex2Str($hex)
    {

        if(function_exists("hex2bin"))
            return hex2bin($hex);

        $parts = str_split($hex, 2);
        $parts = array_map(function($v) {
            return chr(self::hex2Dec($v));
        }, $parts);

        return implode("", $parts);
    }
    public static function str2Hex($str)
    {
        return bin2hex($str);
    }
    public static function bin2Str($bin)
    {
        $hex = self::bin2Hex($bin);
        return self::hex2Str($hex);
    }
    public static function str2Bin($str)
    {
        $hex = self::str2Hex($str);
        $parts = str_split($hex, 2);

        $parts = array_map(function($v) {
            return self::hex2Bin($v);
        }, $parts);

        return implode("", $parts);
    }

    // langkah 2 Initial Permutation (IP) pada bit plaintext menggunakan tabel IP berikut:
    protected $sub_keys = array();
    protected static $_pc1 = array();
    protected static $_pc2 = array();
    protected static $_key_sched = array();
    private static $_ip = array();
    private static $_e = array();
    private static $_s = array();
    private static $_p = array();
    private static $_fp = array();

    private function initTables() {

        self::$_pc1 = array(
            57, 49, 41, 33, 25, 17,  9,
            1, 58, 50, 42, 34, 26, 18,
            10,  2, 59, 51, 43, 35, 27,
            19, 11,  3, 60, 52, 44, 36,
            63, 55, 47, 39, 31, 23, 15,
            7, 62, 54, 46, 38, 30, 22,
            14,  6, 61, 53, 45, 37, 29,
            21, 13,  5, 28, 20, 12,  4
        );

        self::$_pc2 = array(
            14, 17, 11, 24,  1,  5,
            3, 28, 15,  6, 21, 10,
            23, 19, 12,  4, 26,  8,
            16,  7, 27, 20, 13,  2,
            41, 52, 31, 37, 47, 55,
            30, 40, 51, 45, 33, 48,
            44, 49, 39, 56, 34, 53,
            46, 42, 50, 36, 29, 32
        );

        self::$_ip = array(
            58, 50, 42, 34, 26, 18, 10, 2,
            60, 52, 44, 36, 28, 20, 12, 4,
            62, 54, 46, 38, 30, 22, 14, 6,
            64, 56, 48, 40, 32, 24, 16, 8,
            57, 49, 41, 33, 25, 17,  9, 1,
            59, 51, 43, 35, 27, 19, 11, 3,
            61, 53, 45, 37, 29, 21, 13, 5,
            63, 55, 47, 39, 31, 23, 15, 7
        );

        self::$_e = array(
            32,  1,  2,  3,  4,  5,
            4,  5,  6,  7,  8,  9,
            8,  9, 10, 11, 12, 13,
            12, 13, 14, 15, 16, 17,
            16, 17, 18, 19, 20, 21,
            20, 21, 22, 23, 24, 25,
            24, 25, 26, 27, 28, 29,
            28, 29, 30, 31, 32,  1
        );

        self::$_s = array(
            /* S1 */
            array(
                14,  4, 13,  1,  2, 15, 11,  8,  3, 10,  6, 12,  5,  9,  0,  7,
                0, 15,  7,  4, 14,  2, 13,  1, 10,  6, 12, 11,  9,  5,  3,  8,
                4,  1, 14,  8, 13,  6,  2, 11, 15, 12,  9,  7,  3, 10,  5,  0,
                15, 12,  8,  2,  4,  9,  1,  7,  5, 11,  3, 14, 10,  0,  6, 13
            ),

            /* S2 */
            array(
                15,  1,  8, 14,  6, 11,  3,  4,  9,  7,  2, 13, 12,  0,  5, 10,
                3, 13,  4,  7, 15,  2,  8, 14, 12,  0,  1, 10,  6,  9, 11,  5,
                0, 14,  7, 11, 10,  4, 13,  1,  5,  8, 12,  6,  9,  3,  2, 15,
                13,  8, 10,  1,  3, 15,  4,  2, 11,  6,  7, 12,  0,  5, 14,  9
            ),

            /* S3 */
            array(
                10,  0,  9, 14,  6,  3, 15,  5,  1, 13, 12,  7, 11,  4,  2,  8,
                13,  7,  0,  9,  3,  4,  6, 10,  2,  8,  5, 14, 12, 11, 15,  1,
                13,  6,  4,  9,  8, 15,  3,  0, 11,  1,  2, 12,  5, 10, 14,  7,
                1, 10, 13,  0,  6,  9,  8,  7,  4, 15, 14,  3, 11,  5,  2, 12
            ),

            /* S4 */
            array(
                7, 13, 14,  3,  0,  6,  9, 10,  1,  2,  8,  5, 11, 12,  4, 15,
                13,  8, 11,  5,  6, 15,  0,  3,  4,  7,  2, 12,  1, 10, 14,  9,
                10,  6,  9,  0, 12, 11,  7, 13, 15,  1,  3, 14,  5,  2,  8,  4,
                3, 15,  0,  6, 10,  1, 13,  8,  9,  4,  5, 11, 12,  7,  2, 14
            ),

            /* S5 */
            array(
                2, 12,  4,  1,  7, 10, 11,  6,  8,  5,  3, 15, 13,  0, 14,  9,
                14, 11,  2, 12,  4,  7, 13,  1,  5,  0, 15, 10,  3,  9,  8,  6,
                4,  2,  1, 11, 10, 13,  7,  8, 15,  9, 12,  5,  6,  3,  0, 14,
                11,  8, 12,  7,  1, 14,  2, 13,  6, 15,  0,  9, 10,  4,  5,  3
            ),

            /* S6 */
            array(
                12,  1, 10, 15,  9,  2,  6,  8,  0, 13,  3,  4, 14,  7,  5, 11,
                10, 15,  4,  2,  7, 12,  9,  5,  6,  1, 13, 14,  0, 11,  3,  8,
                9, 14, 15,  5,  2,  8, 12,  3,  7,  0,  4, 10,  1, 13, 11,  6,
                4,  3,  2, 12,  9,  5, 15, 10, 11, 14,  1,  7,  6,  0,  8, 13
            ),

            /* S7 */
            array(
                4, 11,  2, 14, 15,  0,  8, 13,  3, 12,  9,  7,  5, 10,  6,  1,
                13,  0, 11,  7,  4,  9,  1, 10, 14,  3,  5, 12,  2, 15,  8,  6,
                1,  4, 11, 13, 12,  3,  7, 14, 10, 15,  6,  8,  0,  5,  9,  2,
                6, 11, 13,  8,  1,  4, 10,  7,  9,  5,  0, 15, 14,  2,  3, 12
            ),

            /* S8 */
            array(
                13,  2,  8,  4,  6, 15, 11,  1, 10,  9,  3, 14,  5,  0, 12,  7,
                1, 15, 13,  8, 10,  3,  7,  4, 12,  5,  6, 11,  0, 14,  9,  2,
                7, 11,  4,  1,  9, 12, 14,  2,  0,  6, 10, 13, 15,  3,  5,  8,
                2,  1, 14,  7,  4, 10,  8, 13, 15, 12,  9,  0,  3,  5,  6, 11
            )
        );

        self::$_p = array(
            16,  7, 20, 21,
            29, 12, 28, 17,
            1, 15, 23, 26,
            5, 18, 31, 10,
            2,  8, 24, 14,
            32, 27,  3,  9,
            19, 13, 30,  6,
            22, 11,  4, 25
        );

        self::$_fp = array(
            40, 8, 48, 16, 56, 24, 64, 32,
            39, 7, 47, 15, 55, 23, 63, 31,
            38, 6, 46, 14, 54, 22, 62, 30,
            37, 5, 45, 13, 53, 21, 61, 29,
            36, 4, 44, 12, 52, 20, 60, 28,
            35, 3, 43, 11, 51, 19, 59, 27,
            34, 2, 42, 10, 50, 18, 58, 26,
            33, 1, 41,  9, 49, 17, 57, 25
        );

        self::$_key_sched = array(1,1,2,2,2,2,2,2,1,2,2,2,2,2,2,1);
    }

    /* Langkah 3 Generate kunci yang akan digunakan untuk mengenkripsi plaintext
        dengan menggunakan tabel permutasi kompresi PC-1,
    */
    private $key_len = 0;
    private $key = "";
    public function key($key = "", $req_sz = 0)
    {
        if($key != "" && $key != null)
        {

            if($this->key_len > 0 && $req_sz == 0)
                $req_sz = $this->key_len;
            else
                $this->key_len = strlen($key);

            if($req_sz > 0)
            {
                if($this->key_len > $req_sz)
                {
                    // shorten the key length
                    $key = substr($key, 0, $req_sz);
                    $this->key_len = $req_sz;
                }
                else if($this->key_len < $req_sz)
                {

//                    $msg = strtoupper($this->name())." requires a $req_sz byte key, {$this->key_len} bytes received";
//                    trigger_error($msg, E_USER_WARNING);

                    return false;
                }
            }

            $this->key = $key;
        }

        return $this->key;
    }
    private function keyPermutation()
    {
        $this->sub_keys = array();
        $pc1m = array();
        $pcr = array();
        $c = array();
        $d = array();

        $binkey = self::str2Bin($this->key());

        for($i = 1; $i < 56; ++$i)
            $pc1m[$i] = $binkey[self::$_pc1[$i] - 1];

        $c[0] = array_slice($pc1m, 0, 28);
        $d[0] = array_slice($pc1m, 28, 28);

        for($i = 1; $i <= 16; ++$i)
        {
            $c[$i] = $c[$i-1];
            $d[$i] = $d[$i-1];

            for($j = 0; $j < self::$_key_sched[$i-1]; ++$j)
            {
                $c[$i][] = array_shift($c[$i]);
                $d[$i][] = array_shift($d[$i]);
            }
            $CnDn = array_merge($c[$i], $d[$i]);
            $this->sub_keys[$i-1] = "";
            for($j = 0; $j < 48; ++$j)
                $this->sub_keys[$i-1] .= $CnDn[self::$_pc2[$j] - 1];
        }

    }
    public function blockSize($bytes = 0)
    {
        if($bytes > 0)
            $this->block_size = $bytes;

        if(!isset($this->block_size))
            return 0;

        return $this->block_size;
    }

    private function ip($text)
    {
        $text = self::str2Bin($text);
        $ip = "";

        for($i = 0; $i < 64; ++$i)
            $ip .= $text[self::$_ip[$i] - 1];

        return $ip;
    }
    private function f($r, $k)
    {
        $bin = self::xorBin($k, $this->E($r));

        $bin = $this->s($bin);

        $bin = $this->p($bin);

        return $bin;
    }
    private function e($r)
    {
        $e = "";
        for($i = 0; $i < 48; ++$i)
            $e .= $r[self::$_e[$i] - 1];

        return $e;
    }
    private function s($bits)
    {
        $s = "";

        for($i = 0; $i <= 42; $i += 6)
        {
            $sbits = substr($bits, $i, 6);
            $row = bindec("{$sbits[0]}{$sbits[5]}");
            $col = bindec("{$sbits[1]}{$sbits[2]}{$sbits[3]}{$sbits[4]}");

            $pos = ($row * 16) + $col;

            $bin = decbin(self::$_s[($i/6)][$pos]);
            $s .= str_pad($bin, 4, "0", STR_PAD_LEFT);
        }

        return $s;
    }
    private function p($s)
    {
        $p = "";
        for($i = 0; $i < 32; ++$i)
            $p .= $s[self::$_p[$i] - 1];

        return $p;
    }
    private function fp($bin)
    {
        $fp = "";
        for($i = 0; $i < 64; ++$i)
            $fp .= $bin[self::$_fp[$i] - 1];

        return $fp;
    }
    public static function xorBin($a, $b)
    {
        $len_a = strlen($a);
        $len_b = strlen($b);
        $width = $len_a;

        if($len_a > $len_b)
        {
            $width = $len_a;
            $b = str_pad($b, $width, "0", STR_PAD_LEFT);
        }
        else if($len_a < $len_b)
        {
            $width = $len_b;
            $a = str_pad($a, $width, "0", STR_PAD_LEFT);
        }

        $bin = self::bin2Str($a) ^ self::bin2Str($b);
        return self::str2Bin($bin);
    }
}