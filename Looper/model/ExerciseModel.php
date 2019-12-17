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
            $pdo = Database::dbConnection();
            $query =
                'INSERT INTO exercises(name, fkExerciseStatus)
            VALUES (?, 1)
            ;';
            $pdo->prepare($query)->execute([$exerciseName]);

            return self::getExerciseId($exerciseName);

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
     * @return array exercise
     */
    public static function getExercise($exerciseId)
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT * 
            FROM exercises
            WHERE idExercise = ?
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$exerciseId]);
        $exercise = $statement->fetch();

        $exercise = array_map(function ($val) {
            return htmlspecialchars($val);
        }, $exercise);

        return $exercise;
    }

    /**
     * Get all exercises
     * @return array with all exercises by status
     */
    public static function getExercises()
    {
        $pdo = Database::dbConnection();
        $exerciseStatus = self::getStatusExercices();
        $exercises = array();
        //get exercises by status an save it in array
        for ($i = 0; $i < count($exerciseStatus); $i++) {
            $query =
                "SELECT `name`,`idExercise` as `id`
            FROM Exercises
            INNER JOIN Exercisestatus 
                ON idExerciseStatus=fkExerciseStatus 
            WHERE `status` LIKE '" . $exerciseStatus[$i]->status . "';";
            $statement = $pdo->prepare($query);
            $statement->execute();
            //save all exercises with the same status on array
            $exercises[$exerciseStatus[$i]->status] = $statement->fetchAll(PDO::FETCH_CLASS);

        }

        foreach ($exercises as $exercise)
            foreach ($exercise as $data)
                $data->name = htmlspecialchars($data->name);

        return $exercises;
    }

    /**
     * Get an exercise with its status
     * @param int $exerciseId id of the exercise
     * @return object exercise with status
     */
    public static function getExerciseWithStatus($exerciseId)
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT * 
            FROM exercises
            INNER JOIN exercisestatus
            ON exercisestatus.idExerciseStatus = exercises.fkExerciseStatus
            WHERE idExercise = ?
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$exerciseId]);
        $exercise = $statement->fetch();

        return $exercise;
    }

    /**
     * search all exercises status on database
     * @return array with all status on database
     */
    private static function getStatusExercices()
    {
        $pdo = Database::dbConnection();
        $query =
            'SELECT `status`
            FROM Exercisestatus;';
        $statement = $pdo->prepare($query);
        $statement->execute();
        $exercises = $statement->fetchAll(PDO::FETCH_CLASS);

        return $exercises;
    }

    /**
     * Search all exercises with the "Answering" status
     * @return array all Answering exercises
     */
    public static function getAnsweringExercises()
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT `name`,`idExercise`
            FROM Exercises
            INNER JOIN Exercisestatus 
                ON idExerciseStatus=fkExerciseStatus 
            WHERE `status` LIKE "Answering";';
        $statement = $pdo->prepare($query);
        $statement->execute();
        $exercises = $statement->fetchAll(PDO::FETCH_CLASS);

        foreach ($exercises as $exercise)
            $exercise->name = htmlspecialchars($exercise->name);

        return $exercises;
    }

    /**
     * get the id of an exercise
     * @param string $exerciseName name of the exercise
     * @return int id of the exercise
     */
    public static function getExerciseId($exerciseName)
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT idExercise 
            FROM exercises
            WHERE exercises.name = ?
            ORDER by idExercise DESC
            LIMIT 1
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$exerciseName]);
        $exerciseId = $statement->fetch()["idExercise"];

        return $exerciseId;
    }

    /**
     * Get all questions of an exercise
     * @param int $exerciseId id of the exercise
     * @return array questions of the exercise
     */
    public static function getQuestions($exerciseId)
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT *
            FROM questions
            LEFT JOIN questiontypes
            ON questions.fkQuestionType = questiontypes.idQuestionType
            WHERE fkExercise = ?
            ORDER BY `order` 
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$exerciseId]);
        $questions = $statement->fetchAll();

        $exercise = array();
        foreach ($questions as $question)
            array_push($exercise, array_map(function ($val) {
                return htmlspecialchars($val);
            }, $question));

        return $exercise;
    }

    /**
     * get max order of all questions of an exercise
     * @param int $exerciseId id of the exercise
     * @return number
     */
    public static function getMaxOrder($exerciseId)
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT MAX(`order`) as `order`
            FROM questions
            WHERE fkExercise = ?
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$exerciseId]);
        $questions = $statement->fetch();

        return $questions[0];
    }

    /**
     * Get all answers of an exercise
     * @param int $exerciseId id of the exercise
     * @return array answers of the exercise
     */
    public static function getAnswers($exerciseId)
    {
        $pdo = Database::dbConnection();
        $query = "SELECT takes.idTake AS id, takes.saveTime AS name, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion, minimumLength
                    FROM answers
                    INNER JOIN questions on answers.fkQuestion = questions.idQuestion
                    INNER JOIN takes ON takes.idTake = answers.fkTake
                    INNER JOIN exercises on exercises.idExercise = questions.fkExercise
                    WHERE exercises.idExercise=$exerciseId ORDER BY id,`order`";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_CLASS);

        return self::usersQuestionsOfExercise($data);
    }

    /**
     * sorts all the objects in the table to create a table containing all the answers to questions by user
     * @param array $obj list of objects with id, name and question with answer
     * @param bool $encode determines if the data should be encoded to be displayed in html
     * @return array $users questions and answers by user
     */
    private static function usersQuestionsOfExercise($obj, bool $encode = false)
    {
        $users = array();
        $lastID = '0';
        $index = 0;
        $user = new stdClass();
        foreach ($obj as $value) {
            if ($lastID != $value->id)//when new user
            {
                if ($lastID != 0)// not add user on the first iteration, because it's empty
                {
                    array_push($users, $user);
                }

                $index = 0;
                $user = new stdClass();
                $user->id = $value->id;
                $user->name = $value->name;
            }
            $user->question[$index] = new stdClass();//our question contain label and answer object
            $user->question[$index]->label = htmlspecialchars($value->question);
            if ($encode)
                $user->question[$index]->answer = htmlspecialchars($value->answer);
            else
                $user->question[$index]->answer = $value->answer;
            $user->question[$index]->minimumLength = $value->minimumLength;
            $index++;
            $lastID = $value->id;
            if (!next($obj)) { //when the last loop, add user on users array
                array_push($users, $user);
            }
        }

        return $users;
    }

    /**
     * search on database all answers for a specific question
     * @param $idExercise
     * @param $idQuestion
     * @return array with users and their answer for the question
     */
    public static function getAnswersByQuestion($idExercise, $idQuestion)
    {
        $pdo = Database::dbConnection();
        $query = "SELECT takes.idTake AS id, takes.saveTime AS name, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion, minimumLength
                    FROM answers
                    INNER JOIN questions on answers.fkQuestion = questions.idQuestion
                    INNER JOIN takes ON takes.idTake = answers.fkTake
                    INNER JOIN exercises on exercises.idExercise = questions.fkExercise
                    WHERE exercises.idExercise=$idExercise and questions.idQuestion=$idQuestion ORDER BY id";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_CLASS);

        return self::usersQuestionsOfExercise($data, true);
    }

    /**
     * Search all answers of a take
     * @param $idExercise
     * @param $idUser
     * @return array with the user's answers
     */
    public static function getResultsByTake($idExercise, $idUser)
    {
        $pdo = Database::dbConnection();
        $query = "SELECT takes.idTake AS id, takes.saveTime AS name, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion, minimumLength
                    FROM answers
                    INNER JOIN questions on answers.fkQuestion = questions.idQuestion
                    INNER JOIN takes ON takes.idTake = answers.fkTake
                    INNER JOIN exercises on exercises.idExercise = questions.fkExercise
                    WHERE exercises.idExercise=$idExercise and takes.idTake=$idUser ORDER BY id";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_CLASS); //return an array with id, name, question, answer

        return self::usersQuestionsOfExercise($data, true);
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
        $pdo = Database::dbConnection();

        $query =
            'SELECT *
            FROM questions
            LEFT JOIN questiontypes
            ON questions.fkQuestionType = questiontypes.idQuestionType
            INNER JOIN answers
            ON answers.fkQuestion = questions.idQuestion
            WHERE answers.fkTake = ?
            ORDER BY `order`
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$takeId]);
        $questions = $statement->fetchAll();

        $exercise = array();
        foreach ($questions as $question) {
            $question['label'] = htmlspecialchars($question['label']);//html_entity_decode(
            //escape quotes and html entity quotes
            $question['content'] = str_replace(array('&quot;', "&apos;", '"', "'"), array('&amp;quot&semi;', '&amp;apos&semi;', '&quot;', "&apos;"), $question['content']);
            array_push($exercise, $question);
        }

        return $exercise;
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
     * delete an exercise
     * @param int $exerciseId id of the exercise
     */
    public static function deleteExercise($exerciseId)
    {
        $pdo = Database::dbConnection();

        $query =
            'DELETE FROM exercises 
            WHERE idExercise = ?;';

        $pdo->prepare($query)->execute([$exerciseId]);
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
        $pdo = Database::dbConnection();

        $query =
            'SELECT COUNT(idQuestion)
                FROM questions
                WHERE fkExercise = ?;';

        $statement = $pdo->prepare($query);
        $statement->execute([$idExercise]);
        $questionsCount = $statement->fetch()[0];

        // return the number
        return $questionsCount;
    }

    /**
     * get a question by specific order
     * @param int $exerciseId id of the question
     * @param int $order order of the question
     * @return array question of the exercise with the specified order
     */
    public static function getQuestionByOrder($exerciseId, $order)
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT *
            FROM questions
            LEFT JOIN questiontypes
            ON questions.fkQuestionType = questiontypes.idQuestionType
            WHERE fkExercise = ? and `order` = ?
            ORDER BY idQuestion
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$exerciseId, $order]);
        $questions = $statement->fetch();

        return $questions;
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
        $pdo = Database::dbConnection();

        $query =
            'UPDATE exercises
            SET fkExerciseStatus = ?
            WHERE idExercise = ?
            ;';

        $pdo->prepare($query)->execute([$idStatus, $idExercise]);
    }
}