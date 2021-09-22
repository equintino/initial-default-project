<?php

namespace Database\Migrations;

use Config\Config;
use Database\CreateTable;
use Database\Schema;
use Database\Blueprint;

class CreateUsersTable implements CreateTable
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
            $table->string("Nome");
            $table->string("Email",100)->unique();
            $table->string("Logon",50)->unique();
            $table->string("Senha,USUARIO");
            $table->int("IDEmpresa")->nullable()->default(1);
            $table->bool("Visivel")->nullable()->default(1);
            $table->int("PRINCIPAL")->nullable();
            $table->string("Cargo",255)->nullable();
            $table->int("Usuario_id,Group_id")->nullable();
            $table->token();
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
