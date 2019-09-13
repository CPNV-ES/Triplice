<?php


public class Router
{
    private $routes = [];
    function route($path, Closure $closure)
    {
        global $routes;
        $action = trim($path, '/');
        $routes[$action] = $closure;
    }
}