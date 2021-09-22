<?php

namespace Database;

class Schema
{
    private $blueprint;

    public function __construct()
    {
        $this->blueprint = new Blueprint();
    }

    public static function create(string $entity, string $type, object $table): string
    {
        return $table(new Blueprint($entity, $type));
    }

    public static function dropIfExists(string $entity, string $type)
    {
        return (new Blueprint($entity, $type))->dropIfExists();
    }
}
