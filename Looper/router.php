<?php
class router
{
    private $url; // Contiendra l'URL sur laquelle on souhaite se rendre
    private static $routes = array(); // Contiendra la liste des routes
    private static $dirController="controller"; //dossier de tous nos controlleurs

    static function add($route,$function)
    {
        $path = parse_url($_SERVER['REQUEST_URI']);
        array_push(self::$routes,Array(
            'route' => $route,
            'function' => self::$dirController."/".$function
        ));
    }

    static function run()
    {
        $regex="\/[0-9]*\/";
        preg_match()
        $url = parse_url($_SERVER['REQUEST_URI'])['path'];
        echo $url."<br><br>";
        foreach (self::$routes as $route)
        {
            if($route["route"]===$url)
            {
                self::execute($route["function"]);
                echo "<br>".$route["function"];
                break;
            }
        }
    }
    /*
    //killian
    public function do($function){
        $controller=explode('@',$function);
        $dir= explode('/', $controller[0]);
        $class=array_pop($dir);
        $controller[0]=$controller[0].'.php';
        include("$controller[0]");
        $method=$controller[1];
        $class::$method();
    }
    */
    private function execute($function){
        $controller=explode('@',$function);
        $dir= explode('/', $controller[0]);
        $controller[0]=$controller[0].'.php';
        include("$controller[0]");
        $controller[1]();
    }
}