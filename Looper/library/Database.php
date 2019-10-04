<?php

/**
 * Class Database
 *
 * @author Damien Jakob
 */
class Database
{
    private static $dsn;
    private static $ip="SC-C332-PC14";
    private static $dbName="Triplice";
    private static $user="Triplice";
    private static $password="Triplice";

    protected static function dbConnection()
    {
        self::$dsn= "mysql:dbname=".self::$dbName.";host=".self::$ip;
        try {
            $pdo = new PDO(self::$dsn, self::$user, self::$password);
            return $pdo;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

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

    public static function getExerciseId($exerciseName)
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT idExercice 
            FROM exercises
            WHERE exercises.name = ?
            ORDER by idExercice DESC
            LIMIT 1
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$exerciseName]);
        $exerciseId = $statement->fetch()[0];

        return $exerciseId;
    }

    public static function addQuestion($exerciseId, $label, $idQuestionType)
    {
        $pdo = Database::dbConnection();

        $query =
            'INSERT INTO questions(label, fkExercise, fkQuestionType)
            VALUES (?, ?, ?)
            ;';
        $pdo->prepare($query)->execute([$label, $exerciseId, $idQuestionType]);
    }

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
        $exercises = $statement->fetchAll();
        return $exercises;
    }
}