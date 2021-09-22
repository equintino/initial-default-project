<?php

namespace _App;

use Config\Config;
use Traits\AuthTrait;

class Web extends Controller
{
    use AuthTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function start(): void
    {
        $route = filter_input(INPUT_GET, "route", FILTER_SANITIZE_STRIPPED);
        $version = $this->version();
        $config = new Config();
        $connectionList = array_keys($config->getFile());
        $login = filter_input(INPUT_COOKIE, "login", FILTER_SANITIZE_STRIPPED);
        $connectionName= filter_input(INPUT_COOKIE, "connectionName", FILTER_SANITIZE_STRIPPED);
        $checked = filter_input(INPUT_COOKIE, "remember", FILTER_SANITIZE_STRIPPED);

        if(!$connectionList) {
            echo "<script>var initializing=true</script>";
        }

        if($route) {
            $types = $config->types;
            $act = "add";
            $this->view->setPath("Modals")->render($route, [ compact("login", "types", "act") ]);
        } else {
            $this->view->setPath("public")->render("login", [
                    compact("version","connectionList","login","connectionName","checked")
                ]);
        }
    }

    public function init()
    {
        $logged = ucfirst($_SESSION["login"]->Logon);
        echo "<script>var logged='{$logged}'</script>";
        $this->view->insertTheme();
    }

    public function home(): void
    {
        $page = "home";
        $this->view->render("home", [ compact("page") ]);
    }

    public function error($data): void
    {
        $errcode = $data["errcode"];
        $this->view->render("error", [ compact("errcode") ]);
    }

    public function version(): string
    {
        $file = __DIR__ . "/../../version";
        if(file_exists($file)) {
            foreach(file($file) as $row) {
                if(!preg_match("/^#/", $row)) {
                    return $row;
                }
            }
        }
    }
}
