<?php

namespace Core;

use Models\Group;
use Database\CreationProcess;

class View
{
    private $path;
    private $access = [];
    public $theme;

    public function __construct(string $theme = null)
    {
        $this->theme = $theme;
        $this->path  = __DIR__ . "/../pages";
        $this->validate();
    }

    public function setPath(string $path): View
    {
        $this->path = __DIR__ . "/../{$path}";
        return $this;
    }

    public function render(string $page, array $params = [])
    {
        $logged = ($_SESSION["login"]->Logon ?? null);

        /** makes variables available to the page */
        if($params) {
            foreach($params as $param) {
                if(!empty($param)) {
                    foreach($param as $key => $values) {
                        $$key = $values;
                    }
                }
            }
        }

        if(!strpos($this->path, "Modals") && !empty($this->access) && !$this->restrictAccess($page)) {
            return print("<h5 align='center' style='color: var(--cor-primary)'>Restricted access</h5>");
        }

        require $this->path . "/{$page}.php";
    }

    public function insertTheme(array $params = null, string $path = null)
    {
        $head = $this->seo(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            CONF_SITE_DESC,
            url(),
            theme("assets/img/loading.png"),
            theme("assets/img/logo-menu.png")
        );
        /** makes variables available to the page */
        if($params) {
            foreach($params as $var => $param) {
                if(!empty($param)) {
                    foreach($param as $key => $values) {
                        $$key = $values;
                    }
                }
            }
        }
        $access = $this->access;

        require (!empty($path) ? $path : $this->theme . "/_theme.php");
    }

    public function validate(): void
    {
        if(!empty($_SESSION) && array_key_exists("login", $_SESSION)) {
            $login = $_SESSION["login"];
        }
        /** allows or prohibits access */
        if(!empty($login) && $login->Group_id) {
            $group = new Group();
            $gAccess = $group->load($login->Group_id, "*", true);
            if(!$gAccess && preg_match("/[doesn't exist][inválido]/", $group->message())) {
                $createTable = new CreationProcess();
                $data = [
                    "name" => "Administrador",
                    "access" => " *",
                    "active" => 1
                ];
                $createTable->createTable(new Group, $data);
                header("Refresh: 0");
            } else {
                $screens = $gAccess->access;
                foreach(explode(",", $screens) as $screen) {
                    array_push($this->access, trim($screen));
                }
            }
        }
    }

    private function restrictAccess(string $page): bool
    {
        if(in_array("*", $this->access) || $page === "home" || $page === "error" || in_array(Safety::renameScreen($page), $this->access)) {
            return true;
        }
        return false;
    }

    protected function seo(string $title, string $desc, string $url, string $img, string $logo, bool $follow = false)
    {
        return compact("title","desc","url","img","logo","follow");
    }
}
