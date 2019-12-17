<?php

/**
 * Class Database
 *
 * @author Damien Jakob
 */
class Database
{
    /**
     * connects to the database
     * @return PDO
     */
    public static function dbConnection()
    {
        // load the database configuration
        include "config.php";
        $ip = $databaseConnection["ip"];
        $dbName = $databaseConnection["dbName"];
        $user = $databaseConnection["user"];
        $password = $databaseConnection["password"];

        $dsn = "mysql:dbname=" . $dbName . ";host=" . $ip;
        try {
            $pdo = new PDO($dsn, $user, $password);
            return $pdo;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
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
}