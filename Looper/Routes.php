<?php


Router::add("/", "HomeController@index");
Router::add("/home", "HomeController@index");
Router::add("/exercise/id", "HomeController@exercise");
Router::add("/exercise/id/modif", "HomeController@modif");
Router::add("/manage", "ManageController@index");
Router::add("/error", "HomeController@error");

Router::run();