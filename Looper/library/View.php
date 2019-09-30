<?php

/**
 * Class View
 *
 * @author Diogo Vieira
 * @date 27.09.2019
 */
class View
{
    private static $dir="view/";
    private static $ext=".php";
    /**
     * render
     *
     * add Gabarit on our page and show it.
     *
     * @param $view our page with html
     * @param null $params datas returned by url
     */
    static function render($view, $params=null)
    {
        ob_start();
        require self::$dir.$view.self::$ext;
        $content = ob_get_clean();
        require 'view/Template.php';
    }
}