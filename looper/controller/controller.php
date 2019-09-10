<?php
    require 'model/model.php';
    
    function home()
    {
        require 'view/home.php';
    }

    function error($message)
    {
        require 'view/error.php';
    }

    function manage()
    {
        require 'view/manage.php';
    }
    

    function create()
    {
        require 'view/create.php';
    }
    

    function take()
    {
        require 'view/take.php';
    }
    
