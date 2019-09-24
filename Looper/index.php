<?php
    session_start();
    include('router.php');

    router::add("/", "HomeController@index");
    router::add("/home", "HomeController@index");
    router::add("/exercise/id", "HomeController@exercise");
    router::add("/exercise/id/modif", "HomeController@modif");
    router::add("/error", "HomeController@error");

    router::run();



?>