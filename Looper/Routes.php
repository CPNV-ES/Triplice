<?php

// Home
Router::add("/", "HomeController@index");
Router::add("/home", "HomeController@index");

// Create exercise
Router::add("/exercise/create", "ExerciseController@create");
Router::add("/exercise/newExercise", "ExerciseController@newExercise");

// Modify exercise
Router::add("/exercise/id/modify", "ExerciseController@modify");
Router::add("/exercise/id/question/id/delete", "ExerciseController@deleteQuestion");
Router::add("/exercise/id/question/id/modify", "ExerciseController@modify");
Router::add("/exercise/id/order/id/up", "ExerciseController@order");
Router::add("/exercise/di/order/id/down", "ExerciseController@order");

// Manage exercise
Router::add("/manage", "ManageController@index");
Router::add("/exercise/id/completeExercise", "ExerciseController@completeExercise");
Router::add("/exercise/id/delete", "ManageController@deleteExercise");
Router::add("/exercise/id/close", "ManageController@closeExercise");

// Take exercise
Router::add("/exercise/take", "ExerciseController@take");
Router::add("/exercise/id/take", "ExerciseController@takeExercise");
Router::add("/exercise/id/answer/id/edit", "ExerciseController@takeExercise");
Router::add("/exercise/id/submit", "ExerciseController@submitAnswer");
Router::add("/exercise/id/answer/id/resubmit", "ExerciseController@editAnswer");

// Display results
Router::add("/exercise/id/results", "ExerciseController@resultsByExercise");
Router::add("/exercise/id/results/id", "ExerciseController@resultsByQuestion");
Router::add("/exercise/id/user/id", "ExerciseController@resultsByUser");

// Error
Router::add("/error", "HomeController@error");

Router::run();