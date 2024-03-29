<?php

use http\Params;

require_once "model/ExerciseModel.php";
require_once "model/QuestionModel.php";

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
        try {
            // create the exercise
            $exerciseId = ExerciseModel::createExercise($_POST["title"]);
        } catch (Exception $exception) {
            // Display error page if problem when creating the exercise
            $params = new stdClass();
            $params->error = "Invalid exercise name";
            $params->message = 'Please enter an exercise name<br><a href="/exercise/create">Back</a>';
            return self::error($params);
        }

        // redirect to modify page
        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/modify");
        exit();
    }

    /**
     * renders the modify view,
     * if POST data has been sent, delete/modify an exercise accordingly
     * @param $params contains exercise, the id of the exercise
     */
    static function modify($params)
    {
        $exerciseId = $params->exercise;

        // Check if we are allowed to modify the exercise
        if (!ExerciseModel::isModifiable($exerciseId)) {
            $params = new stdClass();
            $params->error = "You are not allowed to modify this exercise";
            $params->message =
                '<a href="/">Home</a>.';
            return self::error($params);
        }

        // modify/create question if the action has been selected
        if (isset($_POST['label']) and isset($_POST['minimumLength'])) {

            $label = $_POST['label'];
            $minimumLength = $_POST['minimumLength'];

            try {
                if (!isset($_POST['idQuestionToModify'])) {
                    // new question : add it
                    QuestionModel::createQuestion($exerciseId, $label, $minimumLength, $_POST['idAnswerType']);
                } else {
                    // existing question : update it
                    QuestionModel::updateQuestion(
                        $_POST['idQuestionToModify'], $label, $minimumLength, $_POST['idAnswerType']
                    );
                }
            } catch (Exception $exception) {
                $params = new stdClass();
                $params->error = "Invalid inputs.";
                $params->message = "<a href='/exercise/$exerciseId/modify'>Go Back</a>";
                return self::error($params);
            }

            // redirect to modify page, to avoid resending post at the refresh of the page
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/modify");
            exit();
        }


        // check if there is a question to modify
        $params->modifyQuestion = False;
        if (isset($params->question)) {
            $questionId = $params->question;
            $params->modifyQuestion = True;
            $params->questionToModify = QuestionModel::getQuestion($questionId);
        }

        $params->exercise = ExerciseModel::getExercise($exerciseId);
        $params->questions = ExerciseModel::getQuestions($exerciseId);
        $params->questionTypes = QuestionModel::getQuestionTypes();

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

        try {
            ExerciseModel::deleteQuestion($exerciseId, $questionId);
        } catch (Exception $exception) {
            $params = new stdClass();
            $params->error = "You are not allowed to delete this question";
            $params->message =
                '<a href="/">Home</a>.';
            return self::error($params);
        }

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

        try {
            ExerciseModel::completeExercise($exerciseId);
        } catch (Exception $exception) {
            // redirect to modify exercise page
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/modify");
            exit();
        }

        // redirect to manage page
        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/manage");
        exit();
    }

    /**
     * renders the takeExercise view
     * @param $params contains exercise, the id of the exercise
     */
    static function takeExercise($params)
    {
        $exerciseId = $params->exercise;
        $exercise = ExerciseModel::getExerciseWithStatus($exerciseId);

        // Check if the exercise has a status that allows answers
        if ($exercise['status'] != 'Answering') {
            $params = new stdClass();
            $params->error = "You are not allowed to answer to this exercise !";
            $params->message = 'Please select an exercise from the <a href="/exercise/take">Take page</a>.';
            return self::error($params);
        }

        // Check if we are updating answers
        $updateAnswer = false;
        if (isset($params->answer)) {
            $takeId = $params->answer;

            try {
                $questions = ExerciseModel::getQuestionsAndAnswers($exerciseId, $takeId);
            } catch (Exception $exception) {
                $params = new stdClass();
                $params->error = "Answer does not exist";
                $params->message =
                    'That answer does not exist. <a href="/exercise/take">Take an exercise</a>.';
                return self::error($params);
            }

            $updateAnswer = true;
            $params->takeId = $takeId;
        } else {
            $questions = ExerciseModel::getQuestions($exerciseId);
        }

        $params->exerciseName = $exercise['name'];
        $params->questions = $questions;
        $params->updateAnswer = $updateAnswer;

        return View::render("Exercise/TakeExercise", $params);
    }

    /**
     * return to view all answers with answering status
     */
    static function take()
    {
        return View::render("Take", ExerciseModel::getAnsweringExercises());
    }

    /**
     * Submit the answers to an exercise. Use the POST data to create the answers to the exercise.
     * @param $params contains exercise, the id of the exercise
     */
    static function submitAnswer($params)
    {
        $exerciseId = $params->exercise;

        // Check if the exercise has a status that allows answers
        $exercise = ExerciseModel::getExerciseWithStatus($exerciseId);
        if ($exercise['status'] != 'Answering') {
            $params = new stdClass();
            $params->error = "You are not allowed to answer to this exercise !";
            $params->message = 'Please select an exercise from the <a href="/exercise/take">Take page</a>.';
            return self::error($params);
        }

        // create the take
        $takeId = ExerciseModel::createTake($exerciseId, $_POST);

        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/answer/" . $takeId . "/edit");
        exit();
    }
    
    /**
     * Edit the answers from a specific take
     * @param $params contains exercise, the id of the exercise, and answer, the id of the take
     * @throws Exception
     */
    static function editAnswer($params)
    {
        $exerciseId = $params->exercise;
        $takeId = $params->answer;

        // Check if the exercise has a status that allows answers
        $exercise = ExerciseModel::getExerciseWithStatus($exerciseId);
        if ($exercise['status'] != 'Answering') {
            $params = new stdClass();
            $params->error = "You are not allowed to answer to this exercise !";
            $params->message = 'Please select an exercise from the <a href="/exercise/take">Take page</a>.';
            return self::error($params);
        }

        // Get the questions from the exercise
        $exerciseQuestions = ExerciseModel::getQuestions($exerciseId);

        // Get the answers and the questions of the take
        $questionsWithAnswers = ExerciseModel::getQuestionsAndAnswers($exerciseId, $takeId);

        // Update the answer, after a few checks
        // Iterate on the original answers and not the $_POST data, because we cannot trust the $_POST
        foreach ($questionsWithAnswers as $questionWithAnswer) {
            // check if the answer really belongs to the exercise (if the form is not broken, it should)
            $isInExercise = false;
            foreach ($exerciseQuestions as $question) {
                if ($question['idQuestion'] == $questionWithAnswer['idQuestion']) {
                    $isInExercise = true;
                    // once we have found the question, we don't need to continue the iteration
                    break;
                }
            }

            // Check if a new answer to the question has been submitted (if the form is not broken, it should)
            $answerId = $questionWithAnswer['idAnswer'];
            if ($isInExercise && isset($_POST[$answerId])) {
                $answer = $_POST[$answerId];
                AnswerModel::updateAnswer($answer, $answerId);
            }
        }

        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/answer/" . $takeId . "/edit");
        exit();
    }

    /**
     * renders the view displaying all answers to an exercise
     * @param $params (questionId)
     */
    static function resultsByExercise($params)
    {
        $params->exerciseId = $params->exercise;
        $params->exercise = ExerciseModel::getExercise($params->exerciseId)['name'];
        $params->questions = ExerciseModel::getQuestions($params->exerciseId);
        $params->results = ExerciseModel::getAnswers($params->exerciseId);
        View::render("Exercise/ResultByExercise", $params);
    }

    /**
     * renders the view displaying all answers of a question
     * @param $params (questionId)
     */
    static function resultsByQuestion($params)
    {
        $params->questionId = $params->results;
        $params->exerciseId = $params->exercise;
        $params->exercise = ExerciseModel::getExercise($params->exerciseId)['name'];
        $params->question = QuestionModel::getName($params->questionId);
        $params->results = ExerciseModel::getAnswersByQuestion($params->exerciseId, $params->results);
        View::render("Exercise/ResultByQuestion", $params);
    }

    /**
     * renders the view displaying all answers of a take
     * @param $params (user id)
     */
    static function resultsByUser($params)
    {
        $params->userId = $params->user;
        $params->exerciseId = $params->exercise;
        $params->exercise = ExerciseModel::getExercise($params->exerciseId)['name'];
        $params->results = ExerciseModel::getResultsByTake($params->exerciseId, $params->userId);
        View::render("Exercise/ResultByUser", $params);
    }

    /**
     * when user modify order question on modify page
     *
     * @param $params get by url
     */
    static function order($params)
    {
        $model = new ExerciseModel($params);
        $model->OrderQuestion($_SERVER["REQUEST_URI"]);

        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/$params->exercise/modify/");
        exit();
    }
}