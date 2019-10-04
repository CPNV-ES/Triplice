<?php

class Controller
{
    static function error()
    {
        return View::render("Error");
    }

    static function databaseInformations()
    {
        Database::informations("Triplice", "SC-C332-PC14");
        Database::credentials("Triplice", "Triplice");
    }
}
