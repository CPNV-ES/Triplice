<?php
    session_start();
    include('router.php');

    router::add("/", "HomeController@index");
    router::add("/home", "HomeController@index");
    router::add("/exercise/[0-9]*/", "HomeController@exercise");
    router::add("/error", "HomeController@error");

    router::run();



?>