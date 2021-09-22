<?php

namespace Support;

class Cookies
{
    private $names;
    private $data;

    public function __construct(array $names, array $data)
    {
        $this->names = $names;
        $this->data = $data;
    }

    public function setCookies($remember, $class, $connectionName)
    {
        $login = $class->Logon;
        if($remember) {
            $time = time() + 60*60*24*7;
            $className = get_class($class);
            foreach($this->data as $name) {
                $data[$name] = $class->$name;
            }
            $data["expire"] = $time;
            setcookie($className, http_build_query($data), $time, "/");
            setcookie("login", $class->Logon, $time, "/");
            setcookie("connectionName", $connectionName, $time, "/");
            setcookie("remember", 1, $time, "/");
        }
        else {
            $this->clean($this->names);
        }
        return $this;
    }

    public function getCookies()
    {
        return $_COOKIE;
    }

    public function clean(array $names): void
    {
        foreach($names  as $name) {
            setcookie($name, null, 0, "/");
        }
    }
}
