<?php

class QuestionModel
{
    protected const MAX_LABEL_LENGTH = 50;
    protected const MIN_MIN_LENGTH = 1;
    protected const MAX_MIN_LENGTH = 250;

    /**
     * Get a question
     * @param int $questionId id of the question
     * @return object question
     */
    public static function getQuestion($questionId)
    {
        return Database::getQuestion($questionId);
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
        // TODO verify if $idAnswerType corresponds to a valid answer type
        // Data validation
        if (
            is_numeric($exerciseId) &&
            !is_null(Database::getExercise($exerciseId)) &&
            is_string($label) &&
            mb_strlen($label) <= self::MAX_LABEL_LENGTH &&
            is_numeric($minimumLength) &&
            self::MIN_MIN_LENGTH <= $minimumLength &&
            $minimumLength <= self::MAX_MIN_LENGTH &&
            is_numeric($idAnswerType)
        ) {
            // Create the question
            Database::addQuestion($exerciseId, $label, $minimumLength, $idAnswerType);
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
        // TODO verify if $idAnswerType corresponds to a valid answer type
        // Data validation
        if (
            is_numeric($idQuestionToModify) &&
            !is_null(Database::getQuestion($idQuestionToModify)) &&
            is_string($label) &&
            mb_strlen($label) <= self::MAX_LABEL_LENGTH &&
            is_numeric($minimumLength) &&
            self::MIN_MIN_LENGTH <= $minimumLength &&
            $minimumLength <= self::MAX_MIN_LENGTH &&
            is_numeric($idAnswerType)
        ) {
            // Create the question
            Database::modifyQuestion($idQuestionToModify, $label, $minimumLength, $idAnswerType);
        } else {
            throw new Exception('Invalid inputs');
        }
    }


    /**
     * delete a question
     * @param int $questionId id of the question
     */
    public static function deleteQuestion($questionId)
    {
        Database::deleteQuestion($questionId);
    }


    /**
     * get all question types
     * @return array list of the question types
     */
    public static function getQuestionTypes()
    {
        return Database::getQuestionTypes();
    }
}