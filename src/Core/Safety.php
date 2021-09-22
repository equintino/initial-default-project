<?php

namespace Core;

abstract class Safety
{
    static $exceptions = [ "home.php", "error.php", "management.php" ];
    static $includes = [];

    public static function dataConnection(): ?string
    {
        return Connect::getConfConnection();
    }

    /**
     * @return array|null
     * @var $path string
     * @var $exceptions array
     */
    public static function screens($path): ?array
    {
        $directory = dir(__DIR__ . "/../{$path}");
        while($file = $directory->read()) {
            if(!preg_match("/^[.|index]/", $file) && !in_array($file, self::$exceptions)) {
                $screens[] = self::renameScreen(substr($file, 0, -4));
            }
        }
        return array_merge($screens, self::$includes);
    }

    public static function crypt(string $passwd): ?string
    {
        return base64_encode($passwd);
    }

    public static function decrypt(string $passwd): ?string
    {
        return base64_decode($passwd);
    }

    public static function validatePasswd(?string $passwd, $hash)
    {
        return crypt($passwd, $hash) == $hash;
    }

    /*
    * Generate a secure hash for a given password. The cost is passed
    * to the blowfish algorithm. Check the PHP manual page for crypt to
    * find more information about this setting.
    */
    public static function generateHash($password, $cost=11)
    {
        /* To generate the salt, first generate enough random bytes. Because
        * base64 returns one character for each 6 bits, the we should generate
        * at least 22*6/8=16.5 bytes, so we generate 17. Then we get the first
        * 22 base64 characters
        */
        $salt=substr(base64_encode(openssl_random_pseudo_bytes(17)),0,22);
        /* As blowfish takes a salt with the alphabet ./A-Za-z0-9 we have to
        * replace any '+' in the base64 string with '.'. We don't have to do
        * anything about the '=', as this only occurs when the b64 string is
        * padded, which is always after the first 22 characters.
        */
        $salt=str_replace("+",".",$salt);
        /* Next, create a string that will be passed to crypt, containing all
        * of the settings, separated by dollar signs
        */
        $param='$'.implode('$',array(
                "2y", //select the most secure version of blowfish (>=PHP 5.3.7)
                str_pad($cost,2,"0",STR_PAD_LEFT), //add the cost in two digits
                $salt //add the salt
        ));

        //now do the actual hashing
        return crypt($password,$param);
    }

    public static function renameScreen(string $key)
    {
        $screens = [
            "config" => "Configuração",
            "user" => "Login de Acesso",
            "shield" => "Segurança",
            "management" => "Gerenciamento"
        ];
        $key = trim($key);
        return (array_key_exists($key, $screens) ? $screens[$key] : $key);
    }
}
