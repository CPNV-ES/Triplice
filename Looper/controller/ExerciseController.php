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

    static function resultsByExercise($params)
    {
        $params->exerciseId =$params->exercise;
        $params->exercise = Database::getExercise($params->exerciseId)['name'];
        $params->questions = Database::getQuestions($params->exerciseId);
        $params->results = Database::getResultsExercise($params->exerciseId);
        View::render("Exercise/ResultByExercise", $params);
    }
    static function resultsByQuestion($params)
    {
        $params->questionId = $params->results;
        $params->exerciseId =$params->exercise;
        $params->exercise = Database::getExercise($params->exerciseId)['name'];
        $params->question = Database::getQuestionName($params->questionId);
        $params->results = Database::getResultsByQuestion($params->exerciseId, $params->results);
        View::render("Exercise/ResultByQuestion", $params);
    }
    static function resultsByUser($params)
    {
        $params->userId = $params->user;
        $params->exerciseId =$params->exercise;
        $params->exercise = Database::getExercise($params->exerciseId)['name'];
        $params->results = Database::getResultsByUser($params->exerciseId,$params->userId);
        View::render("Exercise/ResultByUser", $params);
    }
}