<?php
require 'model/HomeModel.php';

class HomeController extends Controller
{
    static function index()
    {
        require "view/Home.php";
    }
    static function modif($params)
    {
        require "view/Manage.php";
    }

    static function exercise($params)
    {
        require "view/Manage.php";
    }
}