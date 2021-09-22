<?php
    ob_start();

    require __DIR__ . "/vendor/autoload.php";

    use CoffeeCode\Router\Router;
    use Core\Session;
    use _App\Web;

    $session = new Session();
    $router = new Router(url(), ":");

    if(!empty($_SESSION["login"])) {
        /**  Web Routes */
        $router->namespace("_App");
        $router->get("/home", "Web:home");
        $router->get("/", "Web:init");


        /**  The Users' Screens */
        $router->namespace("_App");
        $router->get("/user", "User:init");
        $router->get("/user/{user}", "User:edit");
        $router->post("/user/update", "User:update");
        $router->post("/user/delete/{user}", "User:delete");
        $router->post("/user/password/reset", "User:reset");
        $router->get("/user/register", "User:add");
        $router->post("/user/save", "User:save");
        $router->get("/user/list/company/{companyId}", "User:list");


        /** The Groups' Screens */
        $router->namespace("_App");
        $router->get("/shield", "Group:list");
        $router->get("/group/register", "Group:add");
        $router->post("/group/save", "Group:save");
        $router->post("/group/delete/{name}", "Group:delete");
        $router->post("/group/update", "Group:update");
        $router->post("/group/load/{name}", "Group:load");


        /** The Config's Screens */
        $router->namespace("_App");
        $router->get("/config", "Config:list");
        $router->get("/config/register", "Config:add");
        $router->get("/config/edit/{connectionName}", "Config:edit");
        $router->post("/config/update", "Config:update");
        $router->post("/config/delete/{connectionName}", "Config:delete");
        $router->post("/config/save", "Config:save");


        /** Logout */
        $router->get("/exit", function() {
            (new Session())->destroy();
            echo "<script>document.location.reload(true)</script>";
        });


        /** Error Routes */
        $router->namespace("_App")->group("/ops");
        $router->get("/{errcode}", "Web:error");


        /** Routes */
        $router->dispatch();


        /**  Error Redirect */
        if($router->error()) {
            $router->redirect("/ops/{$router->error()}");
        }

    } else {
        (new Web())->start();
    }

    ob_end_flush();
