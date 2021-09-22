<?php

namespace Database;

use Config\Config;

class CreationProcess
{
    private $connectionName;
    private $type;

    public function define(string $name): void
    {
        @define("CONF_CONNECTION", $name);
        $this->connectionName = CONF_CONNECTION;
        $this->type = (new Config())->type();
    }

    public function getConnectionName(): string
    {
        return $this->connectionName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function createTable(object $class, array $data)
    {
        $class->createThisTable();
        $class->bootstrap($data);
        $class->save();
        return $class->message();
    }
}
