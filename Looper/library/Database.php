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
    /**
     * Get all answers of exercise
     * @param $id
     * @return all answers of specific exercise
     */
    public static function getResultsExercise($id)
    {
        $pdo = Database::dbConnection();
        $query =   "SELECT takes.idTake AS id, takes.saveTime AS name, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion
                    FROM answers
                    INNER JOIN questions on answers.fkQuestion = questions.idQuestion
                    INNER JOIN takes ON takes.idTake = answers.fkTake
                    INNER JOIN exercises on exercises.idExercise = questions.fkExercise
                    WHERE exercises.idExercise=$id ORDER BY id";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_CLASS);

        return self::usersQuestionsOfExercise($data);
    }

    /**
     * search on satabase all answers for specific question
     * @param $idExercise
     * @param $idQuestion
     * @return object array with users and their answer for the question
     */
    public static function getResultsByQuestion($idExercise, $idQuestion)
    {
        $pdo = Database::dbConnection();
        $query =   "SELECT takes.idTake AS id, takes.saveTime AS name, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion
                    FROM answers
                    INNER JOIN questions on answers.fkQuestion = questions.idQuestion
                    INNER JOIN takes ON takes.idTake = answers.fkTake
                    INNER JOIN exercises on exercises.idExercise = questions.fkExercise
                    WHERE exercises.idExercise=$idExercise and questions.idQuestion=$idQuestion ORDER BY id";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_CLASS);

        return self::usersQuestionsOfExercise($data);
    }

    /**
     * Search all answers of user for question
     * @param $idExercise
     * @param $idUser
     * @return object array with user's answers
     */
    public static function getResultsByUser($idExercise, $idUser)
    {
        $pdo = Database::dbConnection();
        $query =   "SELECT takes.idTake AS id, takes.saveTime AS name, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion
                    FROM answers
                    INNER JOIN questions on answers.fkQuestion = questions.idQuestion
                    INNER JOIN takes ON takes.idTake = answers.fkTake
                    INNER JOIN exercises on exercises.idExercise = questions.fkExercise
                    WHERE exercises.idExercise=$idExercise and takes.idTake=$idUser ORDER BY id";
        $statement = $pdo->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_CLASS); //return an array with id, name, question, answer

        return self::usersQuestionsOfExercise($data);

    }

    /**
     * sorts all the objects in the table to create a table containing all the answers to questions by user
     * @param $obj <- array of objects with id, name and question with answer
     * @return $users <- array objects with questions and answers by user
     */
    private static function usersQuestionsOfExercise($obj)
    {
        $users=array();
        $lastID='0';
        $index=0;
        $user=new stdClass();
        foreach ($obj as $value)
        {
            if($lastID!=$value->id)//when new user
            {
                if($lastID!=0)// not add user on the first iteration, because it's empty
                {
                    array_push($users,$user);
                }

                $index=0;
                $user=new stdClass();
                $user->id=$value->id;
                $user->name=$value->name;
            }
            $user->question[$index]=new stdClass();//our question contain label and answer object
            $user->question[$index]->label=$value->question;
            $user->question[$index]->answer=$value->answer;
            $index++;
            $lastID=$value->id;
            if( !next( $obj ) ) { //when the last loop, add user on users array
                array_push($users,$user);
            }
        }

        return $users;
    }
}