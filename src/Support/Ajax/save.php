<?php

require __DIR__ . "/../../../vendor/autoload.php";

$params = (getPost($_POST));
if($params["act"] === "login") {
    $class = new Models\User();
    echo (new Support\AjaxTransaction($class, $params))->saveData();
} elseif($params["act"] === "connection") {
    $data = $params["data"];
    $config = new \Config\Config();
    $config->save($data);
    echo json_encode($config->message());
}
