<?php
class Controller
{
    static function error($params)
    {
        return View::render("Error",$params);
    }
}
