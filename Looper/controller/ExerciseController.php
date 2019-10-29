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
            header("Location: http://".$_SERVER['HTTP_HOST']."/exercise/".$exerciseId."/modify");
            exit();
        }
        else {
            self::error();
        }
    }

    static function modify($params)
    {
        $exerciseId = $params->exercise;

        if(isset($_POST['label']))
        {
            if(!isset($_POST['idQuestionToModify']))
            {
                Database::addQuestion($exerciseId, $_POST['label'], $_POST['idAnswerType']);
            }
            else
            {
                Database::modifyQuestion($_POST['idQuestionToModify'], $_POST['label'], $_POST['idAnswerType']);
            }
        }

        $params->modifyQuestion = False;
        if (isset($params->question))
        {
            $questionId = $params->question;
            $params->modifyQuestion = True;
            $params->questionToModify = Database::getQuestion($questionId);
        }

        $params->exercise = Database::getExercise($exerciseId);
        $params->questions = Database::getQuestions($exerciseId);
        $params->questionTypes = Database::getQuestionTypes();

        View::render("Exercise/Modify", $params);
    }

    static function deleteQuestion($params)
    {
        $exerciseId = $params->exercise;
        $questionId = $params->question;
        Database::deleteQuestion($questionId);

        // redirect to modify page
        header("Location: http://".$_SERVER['HTTP_HOST']."/exercise/".$exerciseId."/modify");
        exit();
    }

    static function take()
    {
        return View::render("Take", Database::getAnsweringExercises());
    }
}