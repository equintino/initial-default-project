<?php

namespace Config;

use Core\Connect;
use Traits\CryptoTrait;

class Config
{
    use CryptoTrait;

    private $file;
    private $data;
    private $dsn;
    private $user;
    private $passwd;
    public $local;
    public $message;
    public $types = [ "mysql", "sqlsrv" ];
    public $text = "[local]\r\ndsn='sqlsrv:Server=127.0.0.1;Database=lojascom_n'\r\nuser='SA'\r\npasswd='TVFudG4zOTIxMg=='\r\n[localMysql]\r\ndsn='mysql:host=localhost;dbname=lojascom_n'\r\nuser='root'\r\npasswd='TVFfbnRuMzkyMTI='";

    public function __construct()
    {
        $this->setFile("/.config.ini");
        $this->local = $this->getConfConnection();
    }

    /** contant file env */
    public function getConfConnection(): ?string
    {
        return Connect::getConfConnection();
    }

    public function setConfConnection(string $data, string $connectionName = null)
    {
        parse_str($data, $data);
        $this->local = (!empty($connectionName) ? $connectionName : $data["connectionName"]);
        $this->data = $data;
        $this->setType($this->data["type"]);
        $this->setAddress($this->data["address"]);
        $this->setDatabase($this->data["db"]);
        $this->setUser($this->data["user"]);
        if(!empty($this->data["passwd"])) {
            $this->setPasswd($this->data["passwd"]);
        }
    }

    public function getFile(): ?array
    {
        return $this->file;
    }

    public function setFile(string $file)
    {
        if(file_exists(__DIR__ . $file)) {
            $this->file = parse_ini_file(__DIR__ . $file, true);
        } else {
            $this->file = [];
        }

    }

    public function type(): ?string
    {
        return strstr($this->file[$this->local]["dsn"], ":", true);
    }

    private function setType(string $type)
    {
        $dsn = "";
        switch($type) {
            case "sqlsrv":
                $dsn .= "sqlsrv:Server=";
                break;
            case "mysql":
                $dsn .= "mysql:host=";
        }
        $this->dsn = $dsn;
    }

    public function address(): ?string
    {
        return substr(strstr(strstr($this->file[$this->local]["dsn"], "="), ";", true),1);
    }

    private function setAddress(string $address)
    {
        $this->dsn .= "{$address};";
    }

    public function database(): ?string
    {
        return substr(strrchr($this->file[$this->local]["dsn"], "="), 1);
    }

    private function setDatabase(string $database)
    {
        if($this->data["type"] === "sqlsrv") {
            $name = "Database";
        }
        elseif($this->data["type"] === "mysql") {
            $name = "dbname";
        }
        $this->dsn .= "{$name}={$database}";
    }

    public function getDsn(): ?string
    {
        return $this->dsn;
    }

    public function user(): ?string
    {
        return $this->file[$this->local]["user"];
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    private function setUser(string $user)
    {
        $this->user = $user;
    }

    public function passwd(): ?string
    {
        return $this->decrypt($this->file[$this->local]["passwd"]);
    }

    public function getPasswd(): ?string
    {
        return $this->passwd;
    }

    private function setPasswd(string $passwd)
    {
        $this->passwd = (!empty($passwd) ? base64_encode($passwd) : null);
    }

    private function decrypt(?string $passwd): ?string
    {
        return base64_decode($passwd);
    }

    public function confirmSave(): bool
    {
        if(array_key_exists($this->local, $this->file)) {
            $this->message = "<span class=warning >The connection name already exists</span>";
            return false;
        } else {
            return $this->save();
        }
    }

    public function save(string $data): bool
    {
        $file = (object) $this->getFile();
        $this->setConfConnection($data);
        parse_str($data, $data);
        $connectionName = $data["connectionName"];
        if(!empty($file->$connectionName)) {
            $this->message = "<span class='warning'>Existing connection name</span>";
            return false;
        }

        $file->$connectionName = [
            "dsn" => $this->getDsn(),
            "user" => $this->getUser(),
            "passwd" => $this->getPasswd()
        ];

        $saved = $this->saveFile((array) $file);
        $this->message = ($saved ? "<span class='success'>Data saved successfully</span>" : "<span class='danger'>Erro ao salvar</span>");
        return $saved;
    }

    public function update(array $data): bool
    {
        $file = (object) $this->getFile();
        $this->setConfConnection($data["data"]);
        parse_str($data["data"], $data);
        $connectionName = $data["connectionName"];

        $file->$connectionName = [
            "dsn" => $this->getDsn(),
            "user" => $this->getUser(),
            "passwd" => $file->$connectionName["passwd"]
        ];

        $saved = $this->saveFile((array) $file);
        $this->message = ($saved ? "<span class='success'>Data saved successfully</span>" : "<span class='error'>Erro ao salvar</span>");
        return $saved;
    }

    public function delete(string $connectionName): ?bool
    {
        unset($this->file[$connectionName]);
        if($this->saveFile($this->file)) {
            $this->message = "<span class='success'>Excluded data successfully</span>";
            return true;
        } else {
            $this->message = "<span class='warnig'>Could not delete data</span>";
            return false;
        }
    }

    private function saveFile(array $data): bool
    {
        $file = __DIR__ . "/../Config/.config.ini";
        /** saving file */
        if(file_exists($file)) {
            $handle = fopen($file, "r+");
        } else {
            $handle = fopen($file, "w");
        }
        ftruncate($handle, 0);
        rewind($handle);

        /** replace data */
        $string = "";
        foreach($data as $local => $params) {
            $string .= "[{$local}]\r\n";
            foreach($params as $param => $value) {
                $string .= "{$param}='{$value}'\r\n";
            }
        }

        $resp = fwrite($handle, $string);
        fclose($handle);
        return $resp;
    }

    public function message(): ?string
    {
        return $this->message;
    }
}
