<?php

namespace Core;

use Config\Config;
use Traits\CryptoTrait;

abstract class Model
{
    use CryptoTrait;

    /** @var array safe in cretated or updated */
    protected static $safe = [ "id", "created_at", "updated_at" ];

    /** @var object|null */
    protected $data;

    /** @var \PDOException|null */
    protected $fail;

    /** @var string|null */
    protected $message;

    /** @var array connection details */
    protected $connectionDetails;

    /**  */
    protected $accentWorlds;


    public function __construct()
    {
        $this->connectionDetails = new Config();
    }

    public function __set($name, $value)
    {
        if(empty($this->data)) {
            $this->data = new \stdClass();
        }
        $this->data->$name = $value;
    }

    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /** @var array $data columns names and values */
    public function bootstrap(array $data): ?object
    {
        foreach($data as $name => $value) {
            if($name === "Senha" || $name === "passwd") $value = $this->crypt($value);
            $this->$name = $value;
        }

        return $this;
    }

    /**
     * @return object|null
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * @return PDOException|null
     */
    public function fail(): ?\PDOException
    {
        return $this->fail;
    }

    /**
     * @return String|null
     */
    public function message(): ?String
    {
        return $this->message;
    }

    protected function create(string $entity, array $data, bool $msgDb = false)
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys(removeAccentArray($data)));
            $sql = "INSERT INTO {$entity} (" . $this->getAccentWorlds($columns) . ") VALUES ({$values})";
            //$stmt = Connect::getInstance($msgDb)->prepare($sql);
            $this->execute($sql, removeAccentArray($data));
            return Connect::getInstance($msgDb)->lastInsertId();
        } catch(\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    protected function read(string $select, string $params = null, bool $msgDb = false): ?\PDOStatement
    {
        try {
            $stmt = Connect::getInstance($msgDb)->prepare($select);
            if($params) {
                parse_str($params, $params);
                foreach($params as $key => $value) {
                    $type = \PDO::PARAM_STR;
                    if(is_numeric($value)) {
                        $type = \PDO::PARAM_INT;
                        $value = (int) $value;
                    }
                    $stmt->bindValue(":{$key}", $value, $type);
                }
            }
            $stmt->execute();
        } catch(\PDOException $exception) {
            $this->fail = $exception;
        }
        return $stmt;
    }

    protected function update(string $entity, array $data, string $terms, string $params, bool $msgDb = false): ?int
    {
        $data["updated_at"] = ($this->connectionDetails->type() === "sqlsrv" ? (new \DateTime())->format("d/m/Y H:i:s") : (new \DateTime())->format("Y-m-d H:i:s"));
        parse_str($params, $params);
        foreach($data as $bind => $value) {
            $dataSet[] = "{$bind} = '{$value}'";
        }
        $dataSet = implode(", ", $dataSet);

        $sql = "UPDATE {$entity} SET {$dataSet} WHERE {$terms}";
        $this->execute($sql, $params);
        return ($stmt->rowCount ?? 1);
    }

    protected function delete(string $entity, string $terms, string $params, bool $msgDb = false): ?int
    {
        try {
            $stmt = Connect::getInstance($msgDb)->prepare("DELETE FROM {$entity} WHERE {$terms}");
            parse_str($params, $params);
            $stmt->execute($params);
            return ($stmt->rowCount() ?? 1);
        } catch(\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    protected function safe(): ?array
    {
        $safe_ = (array) $this->data();
        foreach(static::$safe as $unset) {
            unset($safe_[$unset]);
        }
        return array_filter($safe_, "filterNull");
    }

    protected function setSafe(string $safe)
    {
        static::$safe = explode(",",$safe);
    }

    private function getAccentWorlds($columns)
    {
        $worlds = explode(", ", $columns);
        $arr = [];
        foreach($worlds as $world) {
            if(!empty($this->accentWorlds) && array_key_exists($world, $this->accentWorlds)) {
                array_push($arr, $this->accentWorlds[$world]);
            } else {
                array_push($arr, $world);
            }
        }
        return implode(", ",$arr);
    }

    private function filter(array $data, array $filtered = []): ?array
    {
        $filter = [];
        foreach($data as $key => $value) {
            if(!in_array($key, $filtered)) {
                $filter[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $filter;
    }

    protected function order(string $params): ?string
    {
        return (new SqlParams())->orderParams($params);
    }

    /** pagination */
    protected function limit(): string
    {
        $type = $this->connectionDetails->type();
        return (new sqlParams())->limitParams($type);
    }

    protected function createTable(string $sql): \PDOStatement
    {
        return $this->execute($sql);
    }

    protected function dropTable(string $sql): ?bool
    {
        return $this->execute($sql);
    }

    protected function execute(string $sql, array $params = []): \PDOStatement
    {
        $stmt = Connect::getInstance()->prepare($sql);
        try {
            if($params) {
                $params = $this->filter(removeAccentArray($params));
                foreach($params as $key => $value) {
                    $type = \PDO::PARAM_STR;
                    if(is_numeric($value)) {
                        $type = \PDO::PARAM_INT;
                        $value = (int) $value;
                    } elseif($value == null) {
                        $type = \PDO::PARAM_NULL;
                    }
                    $stmt->bindValue(":{$key}", $value, $type);
                }
            }
            $stmt->execute();
        } catch(PDOException $exception) {
            $this->fail = $exception;
        }
        return $stmt;
    }
}
