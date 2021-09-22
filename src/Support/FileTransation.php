<?php

namespace Support;

class FileTransation
{
    public $local;
    private $file;

    public function __construct(string $file = ".env", string $text=null)
    {
        $this->file = __DIR__ . "/../../{$file}";
        $this->setConst($text);
    }

    public function getLocal(): ?bool
    {
        return $this->local;
    }

    public function setLocal(string $connectionName)
    {
        if(!defined("CONF_CONNECTION")) {
            define("CONF_CONNECTION", $connectionName);
        }
        $handle = fopen($this->file, "r+b");
        $string = "";
        while($row = fgets($handle)) {
            if(preg_match("/CONF_CONNECTION/", $row)) {
                $string .= "CONF_CONNECTION=" . $connectionName . "\r\n";
            }
            else {
                $string .= $row;
            }
        }

        ftruncate($handle, 0);
        rewind($handle);
        $this->local = ( !fwrite($handle, $string) ? false : true );
        fclose($handle);

        return $this;
    }

    public function saveFile()
    {
        $handle = fopen(__DIR__ . "/../../.env", "r+b");
        $string = "";
        while($row = fgets($handle)) {
            $parter = key($params);
            if(preg_match("/$parter/", $row)) {
                $string .= $parter . "=" . $params[$parter];
            }
            else {
                $string .= $row;
            }
        }

        ftruncate($handle, 0);
        rewind($handle);
        if(!fwrite($handle, $string)) {
            die("Could not change the file");
        }
        else {
            echo json_encode("Successfully changed");
        }
        fclose($handle);
    }

    public function setConst(string $text=null)
    {
        if(!file_exists($this->file)) {
            $handle = fopen($this->file, "w+");
            $text = (!empty($text) ? $text: "CONF_CONNECTION=local\r\nCONF_URL_BASE=stylized-database-table\r\nCONF_URL_TEST=test/stylized-database-table\r\nCONF_BASE_THEME=layout\r\nCONF_VIEW_THEME=template\r\nCONF_SITE_NAME=Site-Address\r\nCONF_SITE_TITLE=System Name\r\nCONF_SITE_DESC=System Description");

            $this->local = ( !fwrite($handle, $text) ? false : true );
            fclose($handle);
            //header('Refresh:0');
        }
    }

    public function getConst(): void
    {
        $handle = fopen($this->file, "r");
        while($row = fgets($handle)) {
            if(!empty(trim($row))) {
                $params = explode("=", trim(str_replace("\"","", $row)));
                if(!defined($params[0])) {
                    define($params[0], "{$params[1]}");
                }
            }
        }
    }
}
