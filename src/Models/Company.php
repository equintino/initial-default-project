<?php

namespace Models;

use Core\Model;
use Database\Migrations\CreateCompanysTable;

class Company extends Model implements Models
{
    public static $entity = "Dados_Empresa";

    /** @var array */
    private $required = [];

    public function load(int $id, string $columns = "*")
    {
        $load = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE ID=:ID", "ID={$id}");

        if($this->fail || !$load->rowCount()) {
            $this->message = "Empresa não encontrada do id informado.";
            return null;
        }

        return $load->fetchObject(__CLASS__);
    }

    public function find(string $busca, string $columns = "*")
    {
        if(filter_var($busca, FILTER_SANITIZE_STRIPPED)) {
            $find = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE CNPJ=:CNPJ AND ATIVO=1", "CNPJ={$busca}");
        }

        if($this->fail || !$find->rowCount()) {
            $this->message = "Empresa não encontrada.";
            return null;
        }

        return $find->fetchObject(__CLASS__);
    }

    public function all(int $limit=30, int $offset=0, string $columns = "*", string $order = "id"): ?array
    {
        $all = $this->read("SELECT {$columns} FROM  "
            . self::$entity . " WHERE 1=1 "
            . $this->order($order)
            . $this->limit(), "limit={$limit}&offset={$offset}");

        if($this->fail || !$all->rowCount()) {
            $this->message = "Sua consulta não retornou nenhum registro.";
            return null;
        }

        return $all->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function activeAll(int $limit=30, int $offset=0, string $columns = "*", string $order = "id"): ?array
    {
        $all = $this->read("SELECT {$columns} FROM  "
            . self::$entity . " WHERE ATIVO=1 "
            . $this->order($order)
            . $this->limit(), "limit={$limit}&offset={$offset}");

        if($this->fail || !$all->rowCount()) {
            $this->message = "Sua consulta não retornou nenhum registro.";
            return null;
        }

        return $all->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function save()
    {
        if(!$this->required()) {
            return null;
        }
        $this->setSafe("ID,created_at,updated_at");

        /** Update */
        if(!empty($this->ID)) {
            $companyId = $this->ID;
            $company = $this->read("SELECT id FROM " . self::$entity . " WHERE CNPJ = :CNPJ AND ID != :ID",
                "CNPJ={$this->CNPJ}&ID={$companyId}");
            if($company->rowCount()) {
                $this->message = "A Empresa informada já está cadastrada.";
                return null;
            }

            $this->update(self::$entity, $this->safe(), "ID = :ID", "ID={$companyId}");
            if($this->fail()) {
                $this->message = "Erro ao atualizar, verifique os dados.";
                return null;
            }

            $this->message = "Dados atualizados com sucesso.";
        }

        /** Create */
        if(empty($this->ID)) {
            if($this->find($this->CNPJ)) {
                $this->message = "A Empresa informada já está cadastrada.";
                return null;
            }
            $companyId = $this->create(self::$entity, $this->safe());
            if($this->fail()) {
                $this->message = "Erro ao cadastrar, verifique os dados.";
                return null;
            }
            $this->message = "Cadastro realizado com sucesso.";
        }
        $this->data = $this->read("SELECT * FROM " . self::$entity . " WHERE ID=:ID", "ID={$companyId}")->fetch();
        return $this;
    }

    public function destroy()
    {
        if(!empty($this->id)) {
            $this->delete(self::$entity, "ID=:ID", "ID={$this->id}");
        }

        if($this->fail()) {
            $this->message = "Não foi possível remover o Empresa";
            return null;
        }
        $this->message = "Empresa foi removida com sucesso";
        $this->data = null;
        return $this;
    }

    public function required(): bool
    {
        foreach($this->required as $field) {
            if(empty(trim($this->$field))) {
                $this->message = "O campo {$field} é obrigatório.";
                return false;
            }
        }
        return true;
    }

    public function createThisTable()
    {
        $sql = (new CreateCompanysTable())->up(self::$entity);
        return $this->createTable($sql);
    }

    public function dropThisTable()
    {
        $sql = (new CreateCompanysTable())->down(self::$entity);
        return $this->dropTable($sql);
    }
}
