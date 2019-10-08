<?php

Router::add("/", "HomeController@index");
Router::add("/home", "HomeController@index");
Router::add("/exercise/create", "ExerciseController@create");
Router::add("/exercise/newExercise", "ExerciseController@newExercise");
Router::add("/take", "ExerciseController@take");
Router::add("/exercise/id/modify", "ExerciseController@modify");
Router::add("/exercise/take", "ExerciseController@take");
Router::add("/manage", "ManageController@index");
Router::add("/error", "HomeController@error");

Router::run();