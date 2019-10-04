<?php

Router::add("/", "HomeController@index");
Router::add("/home", "HomeController@index");
Router::add("/error", "HomeController@error");
Router::add("/create", "ExerciseController@create");
Router::add("/take", "ExerciseController@take");
Router::add("/manage", "ManageController@index");
Router::add("/exercise/id/modify", "ExerciseController@modify");
Router::add("/exercise/id/", "ExerciseController@new");

Router::run();