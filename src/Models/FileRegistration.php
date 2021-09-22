<?php

namespace Models;

use Core\Model;
use Database\Migrations\CreateCadArquivosTable;

class FileRegistration extends Model implements Models
{
    public static $entity = "CadArquivos";

    /** @var array */
    private $required = [];

    public function load(int $COD_ARQUIVO, string $columns = "*")
    {
        $load = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE COD_ARQUIVO=:COD_ARQUIVO", "COD_ARQUIVO={$COD_ARQUIVO}");

        if($this->fail || !$load->rowCount()) {
            $this->message = "Arquivo não encontrado";
            return null;
        }
        return $load->fetchObject(__CLASS__);
    }

    public function find(string $COD_DOCUMENTO, string $columns = "*")
    {
        if(filter_var($COD_DOCUMENTO, FILTER_SANITIZE_STRIPPED)) {
            $find = $this->read("SELECT {$columns} FROM " . self::$entity . " WHERE COD_DOCUMENTO=:COD_DOCUMENTO ", "COD_DOCUMENTO={$COD_DOCUMENTO}");
        }

        if($this->fail || empty($find)) {
            $this->message = "Arquivo não encontrado.";
            return null;
        }

        return $find->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
        //return $find->fetchObject(__CLASS__);
    }

    public function search(array $where)
    {
        $terms = "";
        $params = "";
        foreach($where as $k => $v) {
            $terms .= " {$k}=:{$k} AND";
            $params .= "{$k}={$v}&";
        }
        $terms = substr($terms, 0, -3);
        $params = substr($params, 0, -1);
        $data = $this->read("SELECT * FROM " . self::$entity . " WHERE {$terms} ", $params);
        return $data->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function all(int $limit=30, int $offset=0, string $columns = "*", string $order = "COD_ARQUIVO"): ?array
    {
        $sql = "SELECT {$columns} FROM  " . self::$entity . " WHERE 1=1 " . $this->order($order);
        if($limit !== 0) {
            $all = $this->read($sql . $this->limit(), "limit={$limit}&offset={$offset}");
        } else {
            $all = $this->read($sql);
        }

        if($this->fail || !$all->rowCount()) {
            $this->message = "Sua consulta não retornou nenhum registro.";
            return null;
        }

        return $all->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function activeAll(int $limit=30, int $offset=0, string $columns = "*", string $order = "COD_ARQUIVO"): ?array
    {
        $sql = "SELECT {$columns} FROM  " . self::$entity . " WHERE " . $this->order($order);
        if($limit !== 0) {
            $all = $this->read($sql . $this->limit(), "limit={$limit}&offset={$offset}");
        } else {
            $all = $this->read($sql);
        }

        if($this->fail || !$all->rowCount()) {
            $this->message = "Sua consulta não retornou nenhum registro.";
            return null;
        }

        return $all->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
    }

    public function readDataTable(string $sql, ?array $where)
    {
        if(empty($where)) {
            return $this->activeAll();
        }
        //return $this->read($sql, $params);
    }

    public function fileSave(string $name, array $files, int $companyId, int $salesOrder, int $indLocal=5)
    {
        foreach($files as $file) {
            $index = array_search($name, $file["name"]);
            $data = [
                "NOM_ARQUIVO" => $file["name"][$index],
                "DAT_INCLUSAO" => date("d/m/Y H:i:s"),
                "COD_EMPRESA" => $companyId,
                "COD_DOCUMENTO" => $salesOrder,
                "ARQ_01" => file_get_contents($file["tmp_name"][$index]),
                "IND_TIPO" => $this->indType($file["type"][$index]),
                "IND_LOCAL" => $indLocal,
                //"COD_ARQUIVO" => $this->lastId(),
            ];
            $this->bootstrap($data);
            return $this->save();
        }
        // $date = new \DateTime();
        // for($x=0; $x < count($files["name"]); $x++) {
        //     if(!empty($files["name"][$x])) {
        //         $data = [
        //             "NOM_ARQUIVO" => $files["name"][$x],
        //             //"DAT_INCLUSAO" => $date->format("d/m/Y"),
        //             "COD_EMPRESA" => $companyId,
        //             "COD_DOCUMENTO" => $salesOrder,
        //             //"ARQ_01" => base64_encode(file_get_contents($files["tmp_name"][$x])),
        //             //"ARQ_01" => bin2hex(file_get_contents($files["tmp_name"][$x])),
        //             "ARQ_01" => file_get_contents($files["tmp_name"][$x]),
        //             "IND_TIPO" => $this->indType($files["type"][$x]),
        //             "IND_LOCAL" => $indLocal,
        //             //"COD_ARQUIVO" => $this->lastId(),
        //         ];

        //         //echo '<img src="data:image/jpeg;base64,'. base64_encode(file_get_contents($files["tmp_name"][$x])) .'" />';
        //         $this->bootstrap($data);
        //         $ids[] = $this->save();
        //     }
        // }
        //return $ids;
    }

    public function save()
    {
        static::$safe = ["created_at","updated_at"];
        if(!$this->required()) {
            return null;
        }

        $this->validateFields();

        /** Update */
        if($this->COD_ARQUIVO) {
            return $this->update_();
        }

        /** Create */
        if(empty($this->COD_ARQUIVO)) {
            return $this->create_();
        }
        //return $this;
    }

    private function update_()
    {
        if(!empty($this->COD_ARQUIVO)) {
            $this->otherCompanies(["COD_ARQUIVO" => $this->COD_ARQUIVO]);
        }

        ( $this->fail() ? $this->message = "<span class='danger'>Erro ao atualizar, verifique os dados</span>" : $this->message = "<span class='success'>Dados atualizados com sucesso</span>" );

        return null;
    }

    private function create_()
    {
        static::$safe = ["COD_ARQUIVO","created_at","updated_at"];
        if(!empty($this->COD_ARQUIVO) && $this->find($this->COD_ARQUIVO)) {
            $this->message = "<span class='warning'>Arquivo informado já está cadastrado</span>";
        } else {
            $id = $this->create(self::$entity, $this->safe());
            if($this->fail()) {
                $this->message = "<span class='danger'>Erro ao cadastrar, verifique os dados</span>";
                return null;
            }
            $this->message = "<span class='success'>Arquivo salvo com sucesso</span>";
        }
        return $id;
    }

    protected function otherCompanies(array $where=[], bool $autoincrement = true)
    {/** Identificar a empresa */
        $companys = (new Company())->all();
        $keys = array_keys($where);
        $terms = "";
        $params = "";

        foreach($keys as $key) {
            $terms .= "{$key}=:{$key},";
            $params .= "{$key}={$where[$key]},";
        }
        $terms = substr($terms, 0, -1);
        $params = substr($params, 0, -1);
        foreach($companys as $company) {
            $transport = $this->read("SELECT * FROM " . self::$entity . " WHERE {$terms} AND COD_EMPRESA={$company->ID}", $params);

            $this->data->COD_EMPRESA = $company->ID;

            if(!$transport->fetch()) {
                if(!$autoincrement) {
                    static::$safe = ["created_at","updated_at"];
                    $this->data->COD_ARQUIVO = $this->lastId();
                }
                $id = $this->create(self::$entity, $this->safe());
            } else {
                $this->update(self::$entity, $this->safe(), "{$terms} AND COD_EMPRESA={$company->ID}", "{$params}");
            }
        }
        return $id ?? null;
    }

    protected function create(string $entity, array $data, bool $msgDb = false)
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys(removeAccentArray($data)));
            $stmt = \Core\Connect::getInstance($msgDb)->prepare("INSERT INTO {$entity} ({$columns}) VALUES ({$values})");

            foreach($data as $key => $value) {
                $$key = $value;
                $params[":{$key}"] = $value;
            }

            $stmt->bindParam(":ARQ_01", $ARQ_01, \PDO::PARAM_LOB, 0, \PDO::SQLSRV_ENCODING_BINARY);
            $stmt->bindParam(':IND_TIPO', $IND_TIPO, \PDO::PARAM_INT);
            $stmt->bindParam(':IND_LOCAL', $IND_LOCAL, \PDO::PARAM_INT);
            $stmt->bindParam(':COD_EMPRESA', $COD_EMPRESA, \PDO::PARAM_INT);
            $stmt->bindParam(':COD_DOCUMENTO', $COD_DOCUMENTO, \PDO::PARAM_INT);
            $stmt->bindParam(':NOM_ARQUIVO', $NOM_ARQUIVO, \PDO::PARAM_STR);
            $stmt->bindParam(':DAT_INCLUSAO', $DAT_INCLUSAO, \PDO::PARAM_STR);

            if($stmt->execute()) {
                return \Core\Connect::getInstance($msgDb)->lastInsertId();
            }
        } catch(\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    public function showImage( $id )
    {
        $dados = $this->load($id);
        if( isset($dados) ){
            $type = $dados->IND_TIPO;
            $img = $dados->ARQ_01;
            header("Content-Type: {$type}");
            return $img;
        }else{
            return false;
        }
    }

    public function destroy()
    {
        if(!empty($this->COD_ARQUIVO)) {
            $this->delete(self::$entity, "COD_ARQUIVO=:COD_ARQUIVO", "COD_ARQUIVO={$this->COD_ARQUIVO}");
        }

        if($this->fail()) {
            $this->message = "<div class=danger>Não foi possível remover arquivo</div>";
            return null;
        }
        $this->message = "<div class=success>Arquivo removido com sucesso</div>";
        $this->data = null;

        return $this;
    }

    private function indType($type): int
    {
        if(strpos($type, "/")) {
            $t = explode("/", $type)[1];
            switch($t) {
                case "png":
                    return 1;
                case "jpg": case "jpeg":
                    return 2;
                case "pdf":
                    return 5;
                default:
                    return 2;
            }
        }
    }

    /** Last id more one */
    private function lastId()
    {
        $lastData = $this->all(1, 0, "COD_ARQUIVO", "COD_ARQUIVO DESC");
        return ($lastData ? $lastData[0]->COD_ARQUIVO + 1 : 1);
    }

    private function validateFields()
    {
        return null;
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
        $sql = (new CreateCadArquivosTable())->up(self::$entity);
        return $this->createTable($sql);
    }

    public function dropThisTable()
    {
        $sql = (new CreateCadArquivosTable())->down(self::$entity);
        return $this->dropTable($sql);
    }
}
