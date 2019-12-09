<?php

class QuestionModel
{
    protected const MAX_LABEL_LENGTH = 50;
    protected const MIN_MIN_LENGTH = 1;
    protected const MAX_MIN_LENGTH = 250;

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
}