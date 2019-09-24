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

    public function exercise()
    {
        require "view/Manage.php";
    }
}