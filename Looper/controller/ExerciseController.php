<?php


class ExerciseController extends Controller
{
    static function create()
    {
        return View::render("Exercise/Create");
    }
    static function new()
    {
        return View::render("Exercise/Take");
    }

    static function take()
    {
        Database::informations("Triplice","SC-C332-PC14");
        Database::credentials("Triplice","Triplice");
        return View::render("Take", Database::getAnsweringExercises());
    }
}