<?php


namespace App\Utilities\Enum\Encryption;


class Encryption
{
    private const SIGNATURE = 's15n@tur3';

    /**
     * @param $token
     * @return array
     */
    public static function explode($token): array
    {
        return explode('-', self::decryptString($token));
    }

    public function getKey()
    {
        return env('ENCRYPTION_KEY_VALUE', 7);
    }

    public static function decryptString($string) : string
    {
        return self::decrypt($string, env('ENCRYPTION_SEED', 7));
    }
    public static function encryptString($string) : string
    {
        return self::encrypt($string.'-'.self::SIGNATURE, 1);
    }

    private static function  decrypt($string, $key)
    {
        if($key == 1) return $string;
        $str = base64_decode($string);
        $key--;
        return self::decrypt($str, $key);
    }
    private static function encrypt($string,$round)
    {
        if($round == env('ENCRYPTION_SEED', 7)) return $string;
        $str = base64_encode($string);
        $round++;
        return self::encrypt($str, $round);
    }

    public static function getSignature()
    {
        return self::SIGNATURE;
    }

    public static function verifySignature($token)
    {
        $string = self::explode($token);
        return in_array(self::getSignature(), $string);
    }


}