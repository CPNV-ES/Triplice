<?php

Router::add("/", "HomeController@index");
Router::add("/home", "HomeController@index");
Router::add("/exercise/create", "ExerciseController@create");
Router::add("/exercise/take", "ExerciseController@take");
Router::add("/exercise/id/modify", "ExerciseController@modify");
Router::add("/exercise/id/", "ExerciseController@new");
Router::add("/manage", "ManageController@index");
Router::add("/error", "HomeController@error");

Router::run();