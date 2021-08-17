<?php

namespace App\API;

trait StringFilter
{

    // public function __construct()
    // {
    // }
    function escape_like_str($str)
    {
        $like_escape_char = '!';

        return str_replace([$like_escape_char, '%', '_'], [
            $like_escape_char . $like_escape_char,
            $like_escape_char . '%',
            $like_escape_char . '_',
        ], $str);
    }
    function SpaceTo_($str)
    {
        $str = trim($str); //去除頭尾空白
        $pieces = explode(' ', $str);
        foreach ($pieces as $index => $piece) {
            if ($pieces[$index] === '' && $pieces[$index] === $pieces[$index + 1]) {
                unset($pieces[$index]);
            } elseif ($pieces[$index] === '') {
                $pieces[$index] = '_';
            }
        }
        $str = implode('', $pieces);
        return $str;
    }
    function ASCIIFilter($str)
    {
        // return preg_replace("/[‘.,:;*?~`!@#$%^&=<>{}]|\]|\[|\/|\\\|\”|\|/", "", $str);
        // return preg_replace("/^\W+/", "", $str);
        return preg_replace("/[^A-Za-z0-9 \p{Han}_]+/u", "", $str);
    }
}
