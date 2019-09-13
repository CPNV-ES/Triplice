<?php
require 'model/model.php';
require 'controller/Database.php';

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
    require 'view/modify.php';
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