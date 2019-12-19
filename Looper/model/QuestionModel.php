<?php

class QuestionModel
{
    protected const MAX_LABEL_LENGTH = 50;
    protected const MIN_MIN_LENGTH = 1;
    protected const MAX_MIN_LENGTH = 250;

    /**
     * Get a question
     * @param int $questionId id of the question
     * @return array question
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
        $question = $statement->fetch();

        $exercise = array_map(function ($val) {
            return htmlspecialchars($val);
        }, $question);

        return $exercise;
    }

    /**
     * Get the name of a question
     * @param int $questionId id of the question
     * @return string the name of the question
     */
    public static function getName($questionId)
    {
        $pdo = Database::dbConnection();
        $query =
            "SELECT label
            FROM questions
            WHERE idQuestion=?;";
        $statement = $pdo->prepare($query);
        $statement->execute([$questionId]);
        $question = $statement->fetch();

        foreach ($question as $key => $data) {
            $question[$key] = htmlspecialchars($data);
        }

        return $question;
    }

    /**
     * Create a new question
     *
     * @param string $exerciseId id of the exercise
     * @param string $label label of the question
     * @param string $minimumLength minimum length of the question
     * @param string $idAnswerType id of the category of the question
     * @throws Exception
     */
    public static function createQuestion($exerciseId, $label, $minimumLength, $idAnswerType)
    {
        // Data validation
        if (
            is_numeric($exerciseId) &&
            !is_null(ExerciseModel::getExercise($exerciseId)) &&
            is_string($label) &&
            mb_strlen($label) <= self::MAX_LABEL_LENGTH &&
            is_numeric($minimumLength) &&
            self::MIN_MIN_LENGTH <= $minimumLength &&
            $minimumLength <= self::MAX_MIN_LENGTH &&
            is_numeric($idAnswerType)
        ) {
            // Create the question
            $pdo = Database::dbConnection();
            $number = ExerciseModel::getMaxOrder($exerciseId) + 1;
            $query =
                "INSERT INTO questions(label, minimumLength, fkExercise, fkQuestionType, `order`)
            VALUES (?, ?, ?, ?, $number)
            ;";
            $pdo->prepare($query)->execute([$label, $minimumLength, $exerciseId, $idAnswerType]);
        } else {
            throw new Exception('Invalid inputs');
        }
    }

    /**
     * Modify a question
     *
     * @param string $idQuestionToModify id of the question to modify
     * @param string $label new label of the question
     * @param string $minimumLength new minimum length of the question
     * @param string $idAnswerType id of the new category of the question
     * @throws Exception
     */
    public static function updateQuestion($idQuestionToModify, $label, $minimumLength, $idAnswerType)
    {
        // Data validation
        if (
            is_numeric($idQuestionToModify) &&
            !is_null(QuestionModel::getQuestion($idQuestionToModify)) &&
            is_string($label) &&
            mb_strlen($label) <= self::MAX_LABEL_LENGTH &&
            is_numeric($minimumLength) &&
            self::MIN_MIN_LENGTH <= $minimumLength &&
            $minimumLength <= self::MAX_MIN_LENGTH &&
            is_numeric($idAnswerType)
        ) {
            // update the question
            $pdo = Database::dbConnection();
            $query =
                'UPDATE questions
            SET label = ?, minimumLength = ?, fkQuestionType = ?
            WHERE idQuestion = ?
            ;';

            $pdo->prepare($query)->execute([$label, $minimumLength, $idAnswerType, $idQuestionToModify]);

        } else {
            throw new Exception('Invalid inputs');
        }
    }

    /**
     * update the order of a question
     *
     * @param int $order new order of the question
     * @param int $idQuestion id of the question
     */
    public static function UpdateQuestionOrder($order, $idQuestion)
    {
        $pdo = Database::dbConnection();
        $query = "UPDATE `questions` SET `order`=? WHERE  `idQuestion`=?;";
        $statement = $pdo->prepare($query);
        $statement->execute([$order, $idQuestion]);
    }

    /**
     * reorganize questions of exercise
     *
     * @param int $exericeId id of the exercise
     * @param int $order order of the pivot exercise
     */
    public static function reorderQuestions($exericeId, $order)
    {
        $pdo = Database::dbConnection();

        $query = "UPDATE questions
        SET `order` = `order` -1
        WHERE fkExercise = ? 
        AND `order` >= ? ";

        $pdo->prepare($query)->execute([$exericeId, $order]);
    }

    /**
     * delete a question
     * @param int $questionId id of the question
     */
    public static function deleteQuestion($questionId)
    {
        $pdo = Database::dbConnection();
        $question = self::getQuestion($questionId);
        $query =
            'DELETE FROM questions 
            WHERE idQuestion = ?;';

        $pdo->prepare($query)->execute([$questionId]);

        self::reorderQuestions($question["fkExercise"], $question["order"]);
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
        $questionTypes = $statement->fetchAll();

        $exercise = array();
        foreach ($questionTypes as $question)
            array_push($exercise, array_map(function ($val) {
                return htmlspecialchars($val);
            }, $question));

        return $exercise;
    }
}