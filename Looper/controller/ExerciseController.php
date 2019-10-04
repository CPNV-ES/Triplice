<?php

use http\Params;

class ExerciseController extends Controller
{
    static function create()
    {
        View::render("Exercise/Create");
    }
    static function new()
    {
        return View::render("Exercise/Take");
    }

    static function newExercise()
    {
        if (isset($_POST["title"]))
        {
            $exerciseName = $_POST["title"];
            self::databaseInformations();
            $params = (object)array("exercise"=>Database::createExercise($exerciseName));

            self::modify($params);
        }
        else {
            self::error();
        }
    }

    static function modify($params)
    {
        View::render("Exercise/Modify", $params);
    }

    static function take()
    {
        self::databaseInformations();
        return View::render("Take", Database::getAnsweringExercises());
    }
}