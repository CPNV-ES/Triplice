<?php

use http\Params;

/**
 * @name Router
 *
 * @Author  VIEIRA Diogo
 * @Helper  VIQUERAT Killian
 * @Date    24.09.2019
 */
class router
{
    private static $routes = array(); // Contiendra la liste des routes
    private static $dirController="controller"; //dossier de tous nos controlleurs

    /**
     * add
     *
     * Method to create dynamics path on our site
     *
     * @param route $route url we want
     * @param  function $function name of controller of the page, add "@" and append name of your method
     *
     * @author Diogo VIEIRA Diogo
     */
    static function add($route,$function)
    {
        $path = parse_url($_SERVER['REQUEST_URI']);
        array_push(self::$routes,Array(
            'route' => $route,
            'function' => self::$dirController."/".$function
        ));
    }

    /**
     * run
     *
     * Search with current url a
     *
     * @author Diogo VIEIRA Diogo
     */
    static function run()
    {
        $idRegex="([0-9]+)";
        $textRegex="([A-Za-z]+)";

        $url = parse_url($_SERVER['REQUEST_URI'])['path'];
        foreach (self::$routes as $route)
        {
            $regex='^'.str_replace(array("/","id","text"),array("\/",$idRegex,$textRegex),$route["route"]).'$';

            if(preg_match("#".$regex."#",$url,$matches))
            {

                self::sortArray($route["route"],$matches);
                self::execute($route["function"],$matches);
                echo "<br><br>Ma fonction: ".$route["function"];
                break;
            }
        }
    }

    private function sortArray($route,$params)
    {
        var_dump($route);
        var_dump($params);
        $array = array();
        while($id=strrpos("/id", $route))
        {

            array_push($id, $array);
            $haystack = substr($haystack, 0, $seek);
        }
        return $array;
    }

    private function execute($function,$param){
        $controller=explode('@',$function);
        $dir= explode('/', $controller[0])[0];
        $class=explode('/', $controller[0])[1];
        $controller[0]=$controller[0].'.php';
        include("$controller[0]");
        $method=$controller[1];
        if(count($param)>1)
            $class::$method($param);
        else
            $class::$method();
    }

}