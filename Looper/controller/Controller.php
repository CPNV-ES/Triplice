<?php
require 'model/HomeModel.php';

class Controller
{
    public function index()
    {
        require "view/Home.php";
    }
}

function home()
{
    require 'view/home.php';
}


function create()
{
    require 'view/create.php';
}

function modify()
{
    $exerciseName = $_POST['title'];
    $exerciseId = Database::createExercise($exerciseName);
    require 'view/Modify.php';
}


function manage()
{
    require 'view/manage.php';
}

function take()
{
    require 'view/take.php';
}


function error($message)
{
    require 'view/error.php';
}