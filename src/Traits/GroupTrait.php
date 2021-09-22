<?php

namespace Traits;

use Models\Group;

trait GroupTrait
{
    private $group;

    public function group()
    {
        return new Group();
    }
}
