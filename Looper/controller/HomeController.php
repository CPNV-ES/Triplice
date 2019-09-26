<?php
require 'model/HomeModel.php';

class HomeController
{
    static function index()
    {
        require "view/Home.php";
    }

    static function error()
    {
        require "view/Error.php";
    }
    static function modif($id)
    {
        require "view/Manage.php";
    }

    static function exercise($params)
    {
        require "view/Manage.php";
    }
}