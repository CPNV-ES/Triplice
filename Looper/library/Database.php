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
     * get all questions of a take and their answers
     * @param int $takeId id of the take
     * @return array questions of the take with answers
     */
    public static function getQuestionsAndAnswers($takeId)
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
            ORDER BY idQuestion
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([$takeId]);
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
     * @param int $exerciseId id of the exercise containing the question
     * @param string $label label of the question
     * @param int $minimumLength minimum length of answer to accept it
     * @param int $idQuestionType id of the type of the question
     */
    public static function addQuestion($exerciseId, $label, $minimumLength, $idQuestionType)
    {
        $pdo = Database::dbConnection();

        $query =
            'INSERT INTO questions(label, minimumLength, fkExercise, fkQuestionType)
            VALUES (?, ?, ?, ?)
            ;';
        $pdo->prepare($query)->execute([$label, $minimumLength, $exerciseId, $idQuestionType]);
    }

    /**
     * modify a question
     * @param int $questionId id of the question to modify
     * @param string $label new label of the question
     * @param int $minimumLength minimum length of answer to accept it
     * @param int $idQuestionType new id of the type of the question
     */
    public static function modifyQuestion($questionId, $label, $minimumLength, $idQuestionType)
    {
        $pdo = Database::dbConnection();

        $query =
            'UPDATE questions
            SET label = ?, minimumLength = ?, fkQuestionType = ?
            WHERE idQuestion = ?
            ;';

        $pdo->prepare($query)->execute([$label, $minimumLength, $idQuestionType, $questionId]);
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

    /**
     * Create an answer
     * @param string $content content of the answer
     * @param int $idTake id of the take including the answer
     * @param int $idQuestion id of the question the take is answering
     */
    public static function createAnswer($content, $idTake, $idQuestion)
    {
        $pdo = Database::dbConnection();
        $query =
            'INSERT INTO answers(content, fkQuestion, fkTake)
            VALUES (?, ?, ?)
            ;';
        $pdo->prepare($query)->execute([$content, $idQuestion, $idTake]);
    }

    /**
     * Update an answer
     * @param string $content new content of the answer
     * @param int $idAnswer id of the answer we are modifying
     */
    public static function updateAnswer($content, $idAnswer)
    {
        $pdo = Database::dbConnection();
        $query =
            'UPDATE answers
            SET content = ?
            WHERE idAnswer = ?
            ;';
        $pdo->prepare($query)->execute([$content, $idAnswer]);
    }

    /**
     * Get the name of a question
     * @param int $id the id of the question
     * @return string the name of the question
     */
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
     * @return array all answers of specific exercise
     */
    public static function getResultsExercise($id)
    {
        $pdo = Database::dbConnection();
        $query = "SELECT takes.idTake AS id, takes.saveTime AS name, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion
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
     * search on database all answers for a specific question
     * @param $idExercise
     * @param $idQuestion
     * @return object array with users and their answer for the question
     */
    public static function getResultsByQuestion($idExercise, $idQuestion)
    {
        $pdo = Database::dbConnection();
        $query = "SELECT takes.idTake AS id, takes.saveTime AS name, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion
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
     * Search all answers of user for a question
     * @param $idExercise
     * @param $idUser
     * @return object array with user's answers
     */
    public static function getResultsByUser($idExercise, $idUser)
    {
        $pdo = Database::dbConnection();
        $query = "SELECT takes.idTake AS id, takes.saveTime AS name, exercises.name AS exercice, questions.label AS question, content AS answer, questions.idQuestion
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
            $user->question[$index]->label = $value->question;
            $user->question[$index]->answer = $value->answer;
            $index++;
            $lastID = $value->id;
            if (!next($obj)) { //when the last loop, add user on users array
                array_push($users, $user);
            }
        }

        return $users;
    }

    /**
     * Create a take
     * @return int the id of the take
     */
    public static function createTake()
    {
        $pdo = Database::dbConnection();
        $query =
            'INSERT INTO takes(saveTime)
            VALUES (NOW())
            ;';
        $pdo->prepare($query)->execute();

        return Database::getLastTakeId();
    }

    /**
     * get the id of the latest take
     * @return int id of the take
     */
    public static function getLastTakeId()
    {
        $pdo = Database::dbConnection();

        $query =
            'SELECT idTake 
            FROM takes
            ORDER by saveTime DESC, idTake DESC
            LIMIT 1
            ;';
        $statement = $pdo->prepare($query);
        $statement->execute([]);
        $takeId = $statement->fetch()["idTake"];

        return $takeId;
    }
}