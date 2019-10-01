<?php

class ExerciseController extends Controller
{
    static function create()
    {
        return View::render("Exercise/Create");
    }

    static function newExercise()
    {
        $id = null;

        if (isset($_POST["title"]))
        {
            $id = Database::createExercise($_POST["title"]);
        }
    }

    static function take()
    {
        return View::render("Take");
    }
}