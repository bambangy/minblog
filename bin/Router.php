<?php

namespace bin;

use Exception;

class Router
{
    public static function Route(MinContainer $container)
    {
        $controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'home';
        $actionName = isset($_GET['action']) ? $_GET['action'] : 'index';

        $controllerFile = __DIR__ . '/../controllers/' . ucfirst($controllerName) . 'Controller.php';

        if (file_exists($controllerFile)) {
            require $controllerFile;
            $controllerClass = str_replace(__DIR__ . "/../controllers/", "Controllers\\", str_replace(".php", "", $controllerFile));
            $controller = new $controllerClass($container);

            if (method_exists($controller, $actionName)) {
                $controller->$actionName();
            } else {
                throw new Exception("Action $actionName not found!!!", 404);
            }
        } else {
            throw new Exception("Controller $controllerName not found!!!", 404);
        }
    }
}
