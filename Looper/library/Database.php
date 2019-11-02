<?php

/**
 * Class Database
 *
 * @author Damien Jakob
 */
class Database
{
    private static $dsn;
    private static $ip="localhost";
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

    public static function getQuestions($exerciseId)
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT *
            FROM questions
            LEFT JOIN questiontypes
            ON questions.fkQuestionType = questiontypes.idQuestionType
            WHERE fkExercise = ?
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$exerciseId]);
        $exercise = $statement;

        return $exercise;
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
        $exerciseStatus=self::getStatusExercices();
        $exercises=array();
        //get exercises by status an save it in array
        for($i=0;$i<count($exerciseStatus);$i++)
        {
            $query =
                "SELECT `name`,`idExercise` as `id`
            FROM Exercises
            INNER JOIN Exercisestatus 
                ON idExerciseStatus=fkExerciseStatus 
            WHERE `status` LIKE '".$exerciseStatus[$i]->status."';";
            $statement = $pdo->prepare($query);
            $statement->execute();
            //save all exercises with the same status on array
            $exercises[$exerciseStatus[$i]->status]=$statement->fetchAll(PDO::FETCH_CLASS);
        }

        return $exercises;
    }

    /**
     * Get all answers of exercise
     * @param $id
     * @return all answers of specific exercise
     */
    public static function getResultsExercise($id)
    {
        $pdo = Database::dbConnection();
        $query =   "SELECT takes.idTake AS userID, takes.saveTime AS user, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion
                    FROM answers
                    INNER JOIN questions on answers.fkQuestion = questions.idQuestion
                    INNER JOIN takes ON takes.idTake = answers.fkTake
                    INNER JOIN exercises on exercises.idExercise = questions.fkExercise
                    WHERE exercises.idExercise=$id ORDER BY userID";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_CLASS);

        array_push($data,"");//add a empty line for save a last user in the next foreach;

        $results=array();
        $lastID=0;
        foreach ($data as $value)
        {
            if($lastID!=$value->userID)//when new user, add the last user on results array
            {
                if(!$lastID==null)// not add user on the first iteration, because it not exists
                    array_push($results,$user);

                $i=0;
                $user=new stdClass();
                $lastID=$value->userID;
                $user->id=$value->userID;
                $user->name=$value->user;
            }
            $user->question->$i->label=$value->question;
            $user->question->$i->answer=$value->answer;
            $i++;
        }

        return $results;
    }
    public static function getQuestionName($idQuestion)
    {
        $pdo = Database::dbConnection();
        $query =
            "SELECT label
            FROM questions
            WHERE idQuestion=$idQuestion;";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $question = $statement->fetch();
        return $question;
    }
    public static function getResultsByQuestion($idExercise, $idQuestion)
    {
        $pdo = Database::dbConnection();
        $query =   "SELECT takes.idTake AS userID, takes.saveTime AS user, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion
                    FROM answers
                    INNER JOIN questions on answers.fkQuestion = questions.idQuestion
                    INNER JOIN takes ON takes.idTake = answers.fkTake
                    INNER JOIN exercises on exercises.idExercise = questions.fkExercise
                    WHERE exercises.idExercise=$idExercise and questions.idQuestion=$idQuestion ORDER BY userID";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_CLASS);

        array_push($data,"");//add a empty line for save a last user in the next foreach;

        $results=array();
        $lastID=0;
        foreach ($data as $value)
        {
            if($lastID!=$value->userID)//when new user, add the last user on results array
            {
                if(!$lastID==null)// not add user on the first iteration, because it not exists
                    array_push($results,$user);

                $i=0;
                $user=new stdClass();
                $lastID=$value->userID;
                $user->id=$value->userID;
                $user->name=$value->user;
            }
            $user->question->$i->label=$value->question;
            $user->question->$i->answer=$value->answer;
            $i++;
        }

        return $results;
    }
    public static function getResultsByUser($idExercise, $idUser)
    {
        $pdo = Database::dbConnection();
        $query =   "SELECT takes.idTake AS userID, takes.saveTime AS user, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion
                    FROM answers
                    INNER JOIN questions on answers.fkQuestion = questions.idQuestion
                    INNER JOIN takes ON takes.idTake = answers.fkTake
                    INNER JOIN exercises on exercises.idExercise = questions.fkExercise
                    WHERE exercises.idExercise=$idExercise and takes.idTake=$idUser ORDER BY userID";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_CLASS);

        array_push($data,"");//add a empty line for save a last user in the next foreach;

        $results=array();
        $lastID=0;
        foreach ($data as $value)
        {
            if($lastID!=$value->userID)//when new user, add the last user on results array
            {
                if(!$lastID==null)// not add user on the first iteration, because it not exists
                    array_push($results,$user);

                $i=0;
                $user=new stdClass();
                $lastID=$value->userID;
                $user->id=$value->userID;
                $user->name=$value->user;
            }
            $user->question->$i->label=$value->question;
            $user->question->$i->answer=$value->answer;
            $i++;
        }

        return $results;
    }
}