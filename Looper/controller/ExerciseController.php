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

    static function newQuestion($params)
    {
        $exerciseId = $params->exercise;
    }

    static function modify($params)
    {
        self::databaseInformations();

        $exerciseId = $params->exercise;
        $params->exercise = Database::getExercise($exerciseId);
        $params->questions = Database::getQuestions($exerciseId);
        $params->questionTypes = Database::getQuestionTypes();

        View::render("Exercise/Modify", $params);
    }

    static function take()
    {
        self::databaseInformations();
        return View::render("Take", Database::getAnsweringExercises());
    }
}