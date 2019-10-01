<?php


Router::add("/", "HomeController@index");
Router::add("/home", "HomeController@index");
Router::add("/exercise/take", "ExerciceController@take");
Router::add("/exercise/create", "ExerciceController@take");
Router::add("/exercise/manage", "ExerciceController@take");
Router::add("/exercise/id/modif", "ExerciceController@create");
Router::add("/manage", "ManageController@index");
Router::add("/error", "HomeController@error");

Router::run();