<?php

use http\Params;

include "model/ExerciseModel.php";

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
            $params = new stdClass();
            $params->error = "No exercise name";
            $params->message = 'Please enter an exercise name<br><a href="/exercise/create">Back</a>';
            return self::error($params);
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
        if (isset($_POST['label']) and isset($_POST['minimumLength'])) {

            $label = $_POST['label'];
            $minimumLength = $_POST['minimumLength'];

            // expected input :
            // * label : string, length <= 50
            // * minimumLength : int, accepted length of answers
            // * idQuestionToModify : int, id of an existing question, optional
            if (strlen($label) <= 50 && 0 < $minimumLength && $minimumLength <= 250) {
                // TODO verify  is the id of a question, and is the id of the selected question
                // TODO verify idAnswerType
                if (!isset($_POST['idQuestionToModify'])) {
                    // new question : add it
                    Database::addQuestion($exerciseId, $label, $minimumLength, $_POST['idAnswerType']);
                } else {
                    // existing question : update it
                    Database::modifyQuestion(
                        $_POST['idQuestionToModify'], $label, $minimumLength, $_POST['idAnswerType']
                    );
                }
            } else {
                $params = new stdClass();
                $params->error = "Invalid inputs.";
                $params->message = "<a href='/exercise/$exerciseId/modify'>Go Back</a>";
                return self::error($params);
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

        if (!ExerciseController::isModifiable($exerciseId)) {
            $params = new stdClass();
            $params->error = "You are not allowed to modify this exercise";
            $params->message =
                '<a href="/">Home</a>.';
            return self::error($params);
        }

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
        $exercise = Database::getExerciseWithStatus($exerciseId);

        // Check if the exercise has a status that allows answers
        if ($exercise['status'] != 'Answering') {
            $params = new stdClass();
            $params->error = "You are not allowed to answer to this exercise !";
            $params->message = 'Please select an exercise from the <a href="/exercise/take">Take page</a>.';
            return self::error($params);
        }

        $updateAnswer = false;
        if (isset($params->answer)) {
            $takeId = $params->answer;

            // Check if the answers belong to the exercise
            $questions = Database::getQuestionsAndAnswers($takeId);
            $thereAreAnswers = false;
            $takeBelongToExercise = true;
            $otherExerciseId = null;
            foreach ($questions as $question) {
                $thereAreAnswers = true;
                if ($question['fkExercise'] != $exerciseId) {
                    $takeBelongToExercise = false;
                    // save the wrong exercise id found
                    $otherExerciseId = $question['fkExercise'];
                    // Once the test has failed, there is no need to use the next item
                    break;
                }
            }
            if (!$thereAreAnswers) {
                $params = new stdClass();
                $params->error = "Answer does not exist";
                $params->message =
                    'That answer does not exist. <a href="/exercise/take">Take en exercise</a>.';
                return self::error($params);
            }
            if (!$takeBelongToExercise) {
                $otherExercise = Database::getExercise($otherExerciseId);
                $params = new stdClass();
                $params->error = "Answer not recognised";
                $params->message =
                    'That answer contains answers to questions from another exercise. <br/>Maybe you wanted the exercise <a href="/exercise/'
                    . $otherExerciseId . '/answer/' . $takeId . '/edit">' . $otherExercise['name'] . '</a>.';
                return self::error($params);
            }

            $questions = Database::getQuestionsAndAnswers($takeId);
            $updateAnswer = true;
            $params->takeId = $takeId;
        } else {
            $questions = Database::getQuestions($exerciseId);
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
        return View::render("Take", Database::getAnsweringExercises());
    }

    /**
     * Submit the answers to an exercise. Use the POST data to create the answers to the exercise.
     * @param $params contains exercise, the id of the exercise
     */
    static function submitAnswer($params)
    {
        $exerciseId = $params->exercise;

        // Check if the exercise has a status that allows answers
        $exercise = Database::getExerciseWithStatus($exerciseId);
        if ($exercise['status'] != 'Answering') {
            $params = new stdClass();
            $params->error = "You are not allowed to answer to this exercise !";
            $params->message = 'Please select an exercise from the <a href="/exercise/take">Take page</a>.';
            return self::error($params);
        }

        $idTake = Database::createTake();

        // Get the questions from the exercise
        $questions = Database::getQuestions($exerciseId);

        // Create the submitted answers
        // Iterate on the questions and not the $_POST data, because we cannot trust the $_POST
        foreach ($questions as $question) {
            $idQuestion = $question['idQuestion'];
            // Check if the $_POST data contains an answer to the question (if the form is not broken, it should)
            if (isset($_POST[$idQuestion])) {
                $answer = $_POST[$idQuestion];
                Database::createAnswer($answer, $idTake, $idQuestion);
            } else {
                // Create an empty answer if an actual answer has not been found
                Database::createAnswer("", $idTake, $idQuestion);
            }
        }

        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/exercise/" . $exerciseId . "/answer/" . $idTake . "/edit");
        exit();
    }

    /**
     * Edit the answers from a specific take
     * @param $params contains exercise, the id of the exercise, and answer, the id of the take
     */
    static function editAnswer($params)
    {
        $exerciseId = $params->exercise;
        $takeId = $params->answer;

        // Check if the exercise has a status that allows answers
        $exercise = Database::getExerciseWithStatus($exerciseId);
        if ($exercise['status'] != 'Answering') {
            $params = new stdClass();
            $params->error = "You are not allowed to answer to this exercise !";
            $params->message = 'Please select an exercise from the <a href="/exercise/take">Take page</a>.';
            return self::error($params);
        }

        // Get the questions from the exercise
        $exerciseQuestions = Database::getQuestions($exerciseId);

        // Get the answers and the questions of the take
        $questionsWithAnswers = Database::getQuestionsAndAnswers($takeId);

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
                Database::updateAnswer($answer, $answerId);
            }
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

    /**
     * Check if an exercise is modifiable
     *
     * @param $exerciseId
     * @return bool
     */
    static function isModifiable($exerciseId)
    {
        $exercise = Database::getExerciseWithStatus($exerciseId);
        $isModifiable = ($exercise['status'] == 'building');
        return $isModifiable;
    }
}