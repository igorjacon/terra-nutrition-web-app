<?php

namespace App\Utils;

class TokenGenerator
{
    public static function generateToken(int $length = 32): string
    {
        return rtrim(strtr(base64_encode(random_bytes($length)), '+/', '-_'), '=');
    }
}