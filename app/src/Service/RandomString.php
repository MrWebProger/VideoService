<?php

namespace App\Service;

class RandomString
{
    private const WORDS = [
        'A', 'a', 'B', 'b', 'C', 'c', 'D', 'd', 'E', 'e', 'F', 'f', 'G', 'g', 'H', 'h',
        'I', 'i', 'J', 'j', 'K', 'k', 'L', 'l', 'M', 'm', 'N', 'n', 'O', 'o', 'P', 'p',
        'Q', 'q', 'R', 'r', 'S', 's', 'T', 't', 'U', 'u', 'V', 'v', 'W', 'w', 'X', 'x',
        'Y', 'Y', 'Z', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
    ];

    public static function get(int $length): string
    {
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, count(self::WORDS) - 1);
            $randomString .= self::WORDS[$index];
        }

        return $randomString;
    }
}
