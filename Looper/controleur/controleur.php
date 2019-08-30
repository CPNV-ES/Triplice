<?php
    require 'modele/modele.php';
    
    function accueil()
    {
        require 'vue/accueil.php';
    }

    function erreur($message)
    {
        require 'vue/erreur.php';
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
    
