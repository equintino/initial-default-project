<?php

namespace _App;

use Models\Group;
use Core\View;

abstract class Controller
{
    protected $view;
    protected $seo;
    protected $loading;

    public function __construct()
    {
        $this->view = new View(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");
        $this->loading = theme("assets/img/loading.png");
    }

    protected function getPost($data)
    {
        foreach($data as $key => $value) {
            $params[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRIPPED);
        }
        return $params;
    }

    protected function getGet($data)
    {
        foreach($data as $key => $value) {
            $params[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRIPPED);
        }
        return $params;
    }

    protected function seo(string $title, string $desc, string $url, string $img, bool $follow = false)
    {
        return compact("title","desc","url","img","follow");
    }
}
