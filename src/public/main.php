<?php

require __DIR__ . "/../../vendor/autoload.php";

use Support\FileTransation;
use Support\Cookies;
use Core\Session;
use Models\User;

$login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_STRIPPED);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRIPPED);
$remember = filter_input(INPUT_POST, "remember", FILTER_SANITIZE_STRIPPED);
$connectionName = filter_input(INPUT_POST, "connection-name", FILTER_SANITIZE_STRIPPED);

$confEnv = (new FileTransation(".env"))->setLocal($connectionName);

if($confEnv->getLocal()) {
    $search = ["Logon" => $login, "Visivel" => 1];
    $user = (new User())->find($search, "*", true);
    if($user) {
        $user = $user[0];
        /** password reseted */
        if(!empty($user->token)) {
            return print(json_encode(2));
        }
        /** password validated */
        if($user->validate($password, $user->Senha)) {
            $names = [ "user", "login", "connectionName", "remember" ];
            $data = [ "id", "Nome", "Logon", "Email" ];
            $cookie = (new Cookies($names, $data))->setCookies($remember, $user, $connectionName);
            (new Session())->setLogin($user);
            return print(json_encode(1));
        }
        return print(json_encode("The password entered does not confer",
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    } else {
        $data = [
            "Nome" => "Administrador",
            "Logon" => "admin",
            "Senha" => "admin932",
            "Email" => "admin@gmail.com",
            "IDEmpresa" => 1,
            "Visivel" => 1,
            "USUARIO" => "admin",
            "Group_id" => 1
        ];
        if((new Database\CreationProcess())->createTable(new User(), $data)) {
            echo json_encode("A new user table was created", JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode("No login was found informed", JSON_UNESCAPED_UNICODE);
        }
    }
} else {
    echo json_encode("Check the configuration file(.env)",
        JSON_UNESCAPED_UNICODE);
}
