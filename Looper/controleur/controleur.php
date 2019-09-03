<?php
    require 'modele/Modele.php';
    
    function home()
    {
        require 'vue/Home.php';
    }

    function error($message)
    {
        require 'vue/Error.php';
    }

    function Manage()
    {
        require 'vue/Manage.php';
    }
    

    function Create()
    {
        require 'vue/Create.php';
    }
    

    function Take()
    {
        require 'vue/Take.php';
    }
    
