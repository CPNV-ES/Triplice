<?php

require_once 'TakeModel.php';
require_once 'AnswerModel.php';

class ExerciseModel
{
    private $params;

    private const MAX_NAME_LENGTH = 50;

    /**
     * ExerciseModel constructor.
     * @param $params params returned by router
     */
    public function __construct($params = null)
    {
        $this->params = $params;
    }

    /**
     * @param $exerciseName name of the new exercise
     * @return int id of the exercise created
     * @throws Exception if zhe name is too long
     */
    public static function createExercise($exerciseName)
    {
        // expected input :
        // * title : string, length <= 50
        if (mb_strlen($exerciseName) <= self::MAX_NAME_LENGTH) {
            $exerciseId = Database::createExercise($exerciseName);
            return $exerciseId;
        } else {
            throw new Exception('Name too long');
        }
    }

    /**
     * @param int $exerciseId id of the exercise we are answering
     * @param array $answers list of the answers
     * @return int
     */
    public static function createTake($exerciseId, $answers)
    {
        $idTake = TakeModel::createTake();

        // Get the questions from the exercise
        $questions = self::getQuestions($exerciseId);

        // Create the submitted answers
        // Iterate on the questions and not the $answers data, because we cannot trust the user input
        // (answers comes from a $_POST)
        foreach ($questions as $question) {
            $idQuestion = $question['idQuestion'];
            // Check if the $_POST data contains an answer to the question (if the form is not broken, it should)
            if (isset($answers[$idQuestion])) {
                $answer = $answers[$idQuestion];
                TakeModel::createAnswer($answer, $idTake, $idQuestion);
            } else {
                // Create an empty answer if an actual answer has not been found
                TakeModel::createAnswer("", $idTake, $idQuestion);
            }
        }

        return $idTake;
    }

    /**
     * Get an exercise
     * @param int $exerciseId id of the exercise
     * @return object exercise
     */
    public static function getExercise($exerciseId)
    {
        return Database::getExercise($exerciseId);
    }

    /**
     * Get an exercise with its status
     * @param int $exerciseId id of the exercise
     * @return object exercise with status
     */
    public static function getExerciseWithStatus($exerciseId)
    {
        return Database::getExerciseWithStatus($exerciseId);
    }

    /**
     * Search all exercises with the "Answering" status
     * @return array all Answering exercises
     */
    public static function getAnsweringExercises()
    {
        return Database::getAnsweringExercises();
    }

    /**
     * Get all questions of an exercise
     * @param int $exerciseId id of the exercise
     * @return array questions of the exercise
     */
    public static function getQuestions($exerciseId)
    {
        return Database::getQuestions($exerciseId);
    }

    /**
     * Get all answers of an exercise
     * @param int $exerciseId id of the exercise
     * @return array answers of the exercise
     */
    public static function getAnswers($exerciseId)
    {
        return Database::getResultsExercise($exerciseId);
    }

    /**
     * @param int $exerciseId id of the exercise we want the take
     * @param int $takeId id of the take we want to get
     * @return array questions of the take with answers
     * @throws Exception
     */
    public static function getQuestionsAndAnswers($exerciseId, $takeId)
    {
        $questions = self::getQuestionsAndAnswersFromTake($takeId);

        $takeBelongToExercise = true;
        $thereAreAnswers = false;
        foreach ($questions as $question) {
            $thereAreAnswers = true;
            if ($question['fkExercise'] != $exerciseId) {
                $takeBelongToExercise = false;
                // Once the test has failed, there is no need to use the next item
                break;
            }
        }
        if (!$takeBelongToExercise || !$thereAreAnswers) {
            throw new Exception('invalid take');
        }

        // we have to refetch the questions, because the foreach does not allow us to reuse them later
        return self::getQuestionsAndAnswersFromTake($takeId);
    }

    /**
     * @param int $takeId id of the take we want to get
     * @return array list of questions with the answers of the take
     */
    public static function getQuestionsAndAnswersFromTake($takeId)
    {
        return Database::getQuestionsAndAnswers($takeId);
    }

    /**
     * Check if an exercise is modifiable
     *
     * @param $exerciseId
     * @return bool
     */
    public static function isModifiable($exerciseId)
    {
        $exercise = self::getExerciseWithStatus($exerciseId);
        $isModifiable = ($exercise['status'] == 'Building');
        return $isModifiable;
    }

    /**
     * Check if an exercise is answering
     *
     * @param $exerciseId
     * @return bool
     */
    public static function isAnswering($exerciseId)
    {
        $exercise = self::getExerciseWithStatus($exerciseId);
        $isModifiable = ($exercise['status'] == 'Answering');
        return $isModifiable;
    }

    /**
     * check order of question and use url to get if user want up or down the order of question
     * Update the order of question based on url information
     * Assumes the questions are ordered coutinuously (1, 2, 3, ...)
     *
     * @param $url current url
     * @example Up : $url -> /exercise/1/order/2/up
     * @example Down : $url -> /exercise/1/order/1/down
     */
    public function OrderQuestion($url)
    {
        //get if is up or down information
        $type = substr(strrchr($url, "/"), 1);

        // get the two questions to switch
        switch ($type) {
            case "up":
                $current = self::getQuestionByOrder($this->params->exercise, $this->params->order);
                $other = self::getQuestionByOrder($this->params->exercise, $this->params->order - 1);//get question with number before
                break;
            case "down":
                $current = self::getQuestionByOrder($this->params->exercise, $this->params->order);
                $other = self::getQuestionByOrder($this->params->exercise, $this->params->order + 1);//get question with number after
                break;
            default:
                break;
        }

        // switch the order of the two elements
        $tmp = $current["order"];
        $current["order"] = $other["order"];
        $other["order"] = $tmp;

        //update the order
        QuestionModel::UpdateQuestionOrder($current["order"], $current["idQuestion"]);
        QuestionModel::UpdateQuestionOrder($other["order"], $other["idQuestion"]);
    }

    /**
     * delete a question from the exercise
     * @param $exerciseId id of the exercise
     * @param int $questionId id of the question
     * @throws Exception
     */
    public static function deleteQuestion($exerciseId, $questionId)
    {
        // Check if we are allowed to modify the exercise
        // Check if question belongs to the exercise
        $question = QuestionModel::getQuestion($questionId);
        if (!self::isModifiable($exerciseId) ||
            $question['fkExercise'] != $exerciseId
        ) {
            throw new Exception('not allowesd');
        }

        QuestionModel::deleteQuestion($questionId);
    }

    /**
     * get the number of questions of an exercise
     * @param int $idExercise id of the exercise
     * @return int number of questions of the exercise
     */
    public static function questionsCount($idExercise)
    {
        return Database::questionsCount($idExercise);
    }

    /**
     * get a question by specific order
     * @param int $exerciseId id of the question
     * @return question of the exercise with the specified order
     */
    public static function getQuestionByOrder($exerciseId, $order)
    {
        return Database::getSpecificQuestionByOrder($exerciseId, $order);
    }

    /**
     * Change the status of an exercise to completed
     * @param $exerciseId
     * @throws Exception
     */
    static function completeExercise($exerciseId)
    {
        $questionsCount = self::questionsCount($exerciseId);

        // Check if we are allowed to modify the exercise : right status and at least one question
        if (!self::isModifiable($exerciseId) ||
            $questionsCount <= 0) {
            throw new Exception('not allowed');
        }

        self::updateExerciseStatus($exerciseId, 2);
    }

    /**
     * modify the status of an exercise
     * @param int $idExercise id of the exercise to modify
     * @param int $idStatus new id of the status
     */
    public static function updateExerciseStatus($idExercise, $idStatus)
    {
        Database::modifyExerciseStatus($idExercise, $idStatus);
    }
}