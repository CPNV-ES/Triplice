<?php

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
            $exerciseId = Database::createExercise($exerciseName);

            // go to modify view
            self::modify();
        }
        else {
            self::error();
        }
    }

    static function modify()
    {
        View::render("Exercise/Modify");
    }

    static function take()
    {
        self::databaseInformations();
        return View::render("Take", Database::getAnsweringExercises());
    }
}