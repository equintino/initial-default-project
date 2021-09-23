<?php

namespace Core;

class Session
{
    private $SID;
    private $login;

    public function __construct()
    {
        if(!file_exists(__DIR__ . "/../ses")) {
            mkdir (__DIR__ . "/../ses");
        }
        if(!session_id()) {
            session_save_path(__DIR__ . "/../ses");
            session_name("SVSESSID");
            session_start();
        }

        if(!empty($_SESSION["id"])) {
            $this->setSID(session_id());
        }
    }

    public function confSID($atual, $ant)
    {
        return  crypt($atual,$this->SID) == $this->SID;
    }

    public function getSID()
    {
        return $this->SID;
    }

    private function setSID($SID)
    {
        $_SESSION["id"] = $SID;
        $this->SID = $SID;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $_SESSION['login'] = (object)$login;
        $this->login = (object)$login;
    }

    public function destroy()
    {
        session_destroy();
    }
}
