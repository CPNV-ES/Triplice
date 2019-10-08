<?php

class Controller
{
    static function error($params)
    {
        return View::render("Error",$params);
    }

    static function databaseInformations()
    {
        Database::informations("Triplice", "SC-C332-PC14");
        Database::credentials("Triplice", "Triplice");
    }
}
