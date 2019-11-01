<?php

/**
 * Class Database
 *
 * @author Damien Jakob
 */
class Database
{
    private static $dsn;
    private static $ip = "SC-C332-PC14";
    private static $dbName = "Triplice";
    private static $user = "Triplice";
    private static $password = "Triplice";

    /**
     * connects to the database
     * @return database connection
     */
    protected static function dbConnection()
    {
        self::$dsn = "mysql:dbname=" . self::$dbName . ";host=" . self::$ip;
        try {
            $pdo = new PDO(self::$dsn, self::$user, self::$password);
            return $pdo;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /**
     * create an exercise
     * @param string $exerciseName name of the exercise
     * @return int id of the exercise created
     */
    public static function createExercise($exerciseName)
    {
        $pdo = Database::dbConnection();

        $query =
            'INSERT INTO exercises(name, fkExerciseStatus)
            VALUES (?, 1)
            ;';
        $pdo->prepare($query)->execute([$exerciseName]);

        return self::getExerciseId($exerciseName);
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
     * get an exercise
     * @param int $exerciseId id of the exercise
     * @return object exercise
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

        return $exercise;
    }

    /**
     * get all questions of an exercise
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
            ORDER BY idQuestion
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$exerciseId]);
        $questions = $statement;

        return $questions;
    }

    /**
     * get a question
     * @param int $questionId id of the question
     * @return object question
     */
    public static function getQuestion($questionId)
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT *
            FROM questions
            WHERE idQuestion = ?
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$questionId]);
        $question = $statement;

        return $question->fetch();
    }

    /**
     * create a question
     * @param int $exerciseId       id of the exercise containing the question
     * @param string $label         label of the question
     * @param int $idQuestionType   id of the type of the question
     */
    public static function addQuestion($exerciseId, $label, $idQuestionType)
    {
        $pdo = Database::dbConnection();

        $query =
            'INSERT INTO questions(label, fkExercise, fkQuestionType)
            VALUES (?, ?, ?)
            ;';
        $pdo->prepare($query)->execute([$label, $exerciseId, $idQuestionType]);
    }

    /**
     * modify a question
     * @param int $questionId       id of the question to modify
     * @param string $label         new label of the question
     * @param int $idQuestionType   new id of the type of the question
     */
    public static function modifyQuestion($questionId, $label, $idQuestionType)
    {
        $pdo = Database::dbConnection();

        $query =
            'UPDATE questions
            SET label = ?, fkQuestionType = ?
            WHERE idQuestion = ?
            ;';

        $pdo->prepare($query)->execute([$label, $idQuestionType, $questionId]);
    }

    /**
     * delete a question
     * @param int $questionId id of the question
     */
    public static function deleteQuestion($questionId)
    {
        $pdo = Database::dbConnection();

        $query =
            'DELETE FROM questions 
            WHERE idQuestion = ?;';

        $pdo->prepare($query)->execute([$questionId]);
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
     * modify the status of an exercise
     * @param int $idExercise id of the exercise to modify
     * @param int $idStatus new id of the status
     */
    public static function modifyExerciseStatus($idExercise, $idStatus)
    {
        $pdo = Database::dbConnection();

        $query =
            'UPDATE exercises
            SET fkExerciseStatus = ?
            WHERE idExercise = ?
            ;';

        $pdo->prepare($query)->execute([$idStatus, $idExercise]);
    }

    /**
     * get all question types
     * @return array list of the question types
     */
    public static function getQuestionTypes()
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT *
            FROM questiontypes';
        $statement = $pdo->prepare($query);
        $statement->execute();
        $questionTypes = $statement;

        return $questionTypes;
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
        return $exercises;
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
     * search all exercises on database and save on array all exercises by status
     * @return array with all exercises by status
     */
    public static function getAllExercises()
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

        return $exercises;
    }
}