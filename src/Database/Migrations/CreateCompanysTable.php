<?php

namespace Database\Migrations;

use Config\Config;
use Database\CreateTable;
use Database\Schema;
use Database\Blueprint;

class CreateCompanysTable implements CreateTable
{
    private $type;

    public function __construct()
    {
        $this->type = (new Config())->type();
    }

    public function up(string $entity): string
    {
        $schema = Schema::create($entity, $this->type, function(Blueprint $table) {
            $table->increment("ID");
                //$table->string("RasãoSocial",100)->unique();
            $table->string("NomeFantasia,InscEstadual,InscMunicipal")->nullable();
            $table->string("CNPJ",50)->unique();
            $table->string("Rua,Num,Complemento,CEP,Bairro,Cidade")->nullable();
            $table->string("UF",2)->nullable();
            $table->string("Telefone,Fax,Email,HomePage,RamoAtividade")->nullable();
                //$table->decimal(18,2)->nullable();
            $table->bool("ATIVO")->nullable()->default(1);
            $table->int("COD_EXTERNO_SCWEB, ID_Empresa_Central")->nullable();
                //$table->tynyint("IND_INTEGRA_SCWEB")->nullable();
            $table->string("DSC_WS_ENDPOINT, DSC_SENHA_WS")->nullable();
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
