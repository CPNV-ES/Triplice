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
        // expected input :
        // * title : string, length <= 50
        if (isset($_POST["title"]) and strlen($_POST["title"]) <= 50) {
            $exerciseName = $_POST["title"];
            $exerciseId = Database::createExercise($exerciseName);

            // redirect to modify page
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/modify");
            exit();
        } else {
            $params = [];
            $params->message = 'Invalid inputs.';
            self::error($params);
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
        var_dump($_POST);

        // delete/modify question if the action has been selected
        if (isset($_POST['label']) and isset($_POST['minimumLength'])) {

            // expected input :
            // * label : string, length <= 50
            // * idQuestionToModify : int, id of an existing question, optional
            // TODO verify minimumLength is int, > 0, <= 250
            if (strlen($_POST['label']) <= 50) {
                // TODO verify id is int, is the id of a question, and is the id of the selected question
                if (!isset($_POST['idQuestionToModify'])) {
                    // new question : add it
                    Database::addQuestion($exerciseId, $_POST['label'], $_POST['minimumLength'], $_POST['idAnswerType']);
                } else {
                    // existing question : update it
                    Database::modifyQuestion(
                        $_POST['idQuestionToModify'], $_POST['label'], $_POST['minimumLength'], $_POST['idAnswerType']
                    );
                }
            } else {
                $params = [];
                $params->message = 'Invalid inputs.';
                self::error($params);
            }

            // redirect to modify page, to avoid resending post at the refresh of the page
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/modify");
            exit();
        }

        $params->modifyQuestion = False;
        if (isset($params->question)) {
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
        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/modify");
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

        if ($questionsCount > 0) {
            // update exercise status to 'answering'
            Database::modifyExerciseStatus($exerciseId, 2);

            // redirect to modify page
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/manage");
            exit();
        } else {
            // redirect to modify page
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/modify");
            exit();
        }
    }

    /**
     * renders the takeExercise view
     * @param $params contains exercise, the id of the exercise
     */
    static function takeExercise($params)
    {
        $exerciseId = $params->exercise;
        $exercise = Database::getExercise($exerciseId);

        $updateAnswer = false;
        $questions = null;
        if (isset($params->answer)) {
            $takeId = $params->answer;
            $questions = Database::getQuestionsAndAnswers($takeId);
            $updateAnswer = true;
            $params->takeId = $takeId;
        } else {
            $questions = Database::getQuestions($exerciseId);
        }

        $params->exerciseName = $exercise['name'];
        $params->questions = $questions;
        $params->updateAnswer = $updateAnswer;


        // TODO allow to update answer from display page

        return View::render("Exercise/TakeExercise", $params);
    }

    /**
     * return to view all answers with answering status
     */
    static function take()
    {
        return View::render("Take", Database::getAnsweringExercises());
    }

    static function submitAnswer($params)
    {
        $exerciseId = $params->exercise;

        $idTake = Database::createTake();

        // create the submitted answers
        foreach ($_POST as $idQuestion => $answer) {
            Database::createAnswer($answer, $idTake, $idQuestion);
        }

        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/answer/" . $idTake . "/edit");
        exit();
    }

    static function editAnswer($params)
    {
        $exerciseId = $params->exercise;
        $takeId = $params->answer;

        // create the submitted answers
        foreach ($_POST as $idAnswer => $answer) {
            Database::updateAnswer($answer, $idAnswer);
        }

        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/answer/" . $takeId . "/edit");
        exit();
    }

    /**
     * returns to view which questions were answered by user
     * @param $params (questionId)
     */
    static function resultsByExercise($params)
    {
        $params->exerciseId = $params->exercise;
        $params->exercise = Database::getExercise($params->exerciseId)['name'];
        $params->questions = Database::getQuestions($params->exerciseId);
        $params->results = Database::getResultsExercise($params->exerciseId);
        View::render("Exercise/ResultByExercise", $params);
    }

    /**
     * return to view with all answers by question
     * @param $params (questionId)
     */
    static function resultsByQuestion($params)
    {
        $params->questionId = $params->results;
        $params->exerciseId = $params->exercise;
        $params->exercise = Database::getExercise($params->exerciseId)['name'];
        $params->question = Database::getQuestionName($params->questionId);
        $params->results = Database::getResultsByQuestion($params->exerciseId, $params->results);
        View::render("Exercise/ResultByQuestion", $params);
    }

    /**
     * return to view  with answers of user for exercise
     * @param $params (user id)
     */
    static function resultsByUser($params)
    {
        $params->userId = $params->user;
        $params->exerciseId = $params->exercise;
        $params->exercise = Database::getExercise($params->exerciseId)['name'];
        $params->results = Database::getResultsByUser($params->exerciseId, $params->userId);
        View::render("Exercise/ResultByUser", $params);
    }

}