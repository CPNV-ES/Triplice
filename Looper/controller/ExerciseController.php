<?php

use http\Params;

class ExerciseController extends Controller
{
    /**
     * renders the create view
     */
    static function create()
    {
        View::render("Exercise/Create");
    }

    /**
     * create a new exercise according to POST data, then go to the modify page of the exercise
     */
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

    /**
     * renders the modify view,
     * if POST data has been sent, delete/modify an exercise accordingly
     * @param $params contains exercise, the id of the exercise
     */
    static function modify($params)
    {
        $exerciseId = $params->exercise;

        // delete/modify question if the action has been selected
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

            // redirect to modify page, to avoid resending post at the refresh of the page
            header("Location: http://".$_SERVER['HTTP_HOST']."/exercise/".$exerciseId."/modify");
            exit();
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

    /**
     * delete a question, then redirect to the page of the exercise
     * @param $params contains exercise, the id of the exercise, and question, the id of the question
     */
    static function deleteQuestion($params)
    {
        $exerciseId = $params->exercise;
        $questionId = $params->question;
        Database::deleteQuestion($questionId);

        // redirect to modify page
        header("Location: http://".$_SERVER['HTTP_HOST']."/exercise/".$exerciseId."/modify");
        exit();
    }

    /**
     * change the status of an exercise to 'answering', then redirect to the manage page
     * if the exercise has no questions, does not change the exercise, then redirect to the exercise page
     * @param $params contains exercise, the id of the exercise
     */
    static function completeExercise($params)
    {
        $exerciseId = $params->exercise;
        $questionsCount = Database::questionsCount($exerciseId);

        if($questionsCount > 0)
        {
            // update exercise status to 'answering'
            Database::modifyExerciseStatus($exerciseId, 2);

            // redirect to modify page
            header("Location: http://".$_SERVER['HTTP_HOST']."/manage");
            exit();
        }
        else{
            // redirect to modify page
            header("Location: http://".$_SERVER['HTTP_HOST']."/exercise/".$exerciseId."/modify");
            exit();
        }
    }

    /**
     * renders the take view
     */
    static function take()
    {
        return View::render("Take", Database::getAnsweringExercises());
    }

    /**
     * renders the takeExercise view
     * @param $params contains exercise, the id of the exercise
     */
    static function takeExercise($params)
    {
        $exerciseId = $params->exercise;
        $exercise = Database::getExercise($exerciseId);
        $questions = Database::getQuestions($exerciseId);

        $params->exerciseName = $exercise['name'];
        $params->questions = $questions;

        return View::render("Exercise/TakeExercise", $params);
    }
}