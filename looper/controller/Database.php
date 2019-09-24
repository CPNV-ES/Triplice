<?php

class Database
{
    protected static $dsn = 'mysql:dbname=triplice;host=127.0.0.1';
    protected static $user = 'root';
    protected static $password = 'Pa$$w0rd';

    protected static function dbConnection()
    {
        $pdo = new PDO(Database::$dsn, Database::$user, Database::$password);

        try {
            $pdo = new PDO(Database::$dsn, Database::$user, Database::$password);
            return $pdo;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public static function createExercise($exerciseName)
    {
        $pdo = Database::dbConnection();

        $query =
            'INSERT INTO exercises
            VALUES (?, 1)';

        $pdo->prepare($query)->execute([$exerciseName]);
    }
}