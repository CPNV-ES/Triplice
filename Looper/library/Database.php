<?php

/**
 * Class Database
 *
 * @author Damien Jakob
 */
class Database
{
    protected static $dsn;
    private static $credentials;

    public static function informations($dbName,$ip)
    {
        self::$dsn= "mysql:dbname=$dbName;host=$ip";
    }
    public static function credentials($user ,$password)
    {
        self::$credentials= "$user, $password";
    }
    protected static function dbConnection()
    {
        try {
            $pdo = new PDO(self::$dsn, self::$credentials);
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
}