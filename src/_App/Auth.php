<?php

namespace _App;

class Auth
{
    public function login(): void
    {
        if(!empty($_SESSION["login"])) {
            header("Location: " . url());
        }
        (new Web())->start();
    }

    public function token($login)
    {
        $this->view->setPath("Modals")->render("token", [ compact("login") ]);
    }

    public function save()
    {
        var_dump($_POST);
    }

    public function forget(): void
    {

    }

    public function register(): void
    {

    }
}
