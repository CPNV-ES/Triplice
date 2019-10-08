<?php

use http\Params;

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
            $exerciseId = Database::createExercise($exerciseName);

            // redirect to modify page
            header("Location: ".$exerciseId."/modify");
            exit();
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
        $exerciseId = $params->exercise;

        if(isset($_POST['label']))
        {
            Database::addQuestion($exerciseId, $_POST['label'], $_POST['idAnswerType']);
        }

        $params->exercise = Database::getExercise($exerciseId);
        $params->questions = Database::getQuestions($exerciseId);
        $params->questionTypes = Database::getQuestionTypes();

        View::render("Exercise/Modify", $params);
    }

    static function take()
    {
        return View::render("Take", Database::getAnsweringExercises());
    }
}