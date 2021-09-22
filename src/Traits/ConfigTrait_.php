<?php

namespace Traits;

use Config\Config;

trait ConfigTrait
{
    public function config(): Config
    {
        return new Config();
    }
}
