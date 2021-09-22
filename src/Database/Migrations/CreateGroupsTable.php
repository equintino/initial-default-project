<?php

namespace Database\Migrations;

use Config\Config;
use Database\CreateTable;
use Database\Schema;
use Database\Blueprint;

class CreateGroupsTable implements CreateTable
{
    private $type;

    public function __construct()
    {
        $this->type = (new Config())->type();
    }

    public function up(string $entity)
    {
        $schema = Schema::create($entity, $this->type, function(Blueprint $table) {
            $table->increment("id");
            $table->string("name",100)->unique();
            $table->string("access")->nullable()->default(" home, error");
            $table->bool("active")->nullable()->default(1);
            $table->timestamps();
            return $table->run();
        });

        return $schema;
    }

    public function down(string $entity)
    {
        return Schema::dropIfExists($entity, $this->type);
    }
}
