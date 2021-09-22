<?php

namespace Traits;

trait CryptoTrait
{
    public function crypt(string $passwd): string
    {
        return crypt($passwd, "rl");
    }

    public function validate($passwd, $hash)
    {
        return crypt($passwd, $hash) == $hash;
    }
}
