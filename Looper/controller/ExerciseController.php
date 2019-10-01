<?php

class ExerciseController extends Controller
{
    static function create()
    {
        View::render("Exercise/Create");
    }

    static function newExercise()
    {
        if (isset($_POST["title"]))
        {
            $exerciseName = $_POST["title"];
            // TODO : update once database fix received
            $exerciseId = 0;
            //$exerciseId = Database::createExercise($_POST["title"]);
            self::modify();
        }
    }

    static function modify()
    {
        View::render("Exercise/Modify");
    }

    static function take()
    {
        View::render("Take");
    }
}