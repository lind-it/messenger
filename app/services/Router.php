<?php

namespace App\Services;

class Router
{
    private static $routes = [];

    public static function route(string $method, string $url, array $action) : void
    {
        self::$routes[$method][] = [
            'url' => $url === '/' ? '/index.php' : $url,
            'controller' => $action[0],
            'action' => $action[1]
        ];
    }

    public static function enable()
    {
        $isFound = false;

        foreach (self::$routes[$_SERVER['REQUEST_METHOD']] as $route)
        {
            if ($route['url'] === '/' . $_GET['q'])
            {
                $controller = new $route['controller'];

                echo $controller->{$route['action']}();

                $isFound = true;
                break;
            }
        }

        if (!$isFound)
        {
            http_response_code(404);
            require_once 'static/errors/404.php';
        }
    }
}