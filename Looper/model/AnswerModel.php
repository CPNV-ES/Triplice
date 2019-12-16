<?php

class AnswerModel
{
    /**
     * Update an answer
     * @param string $content new content of the answer
     * @param int $idAnswer id of the answer we are modifying
     */
    public static function updateAnswer($content, $idAnswer)
    {
        Database::updateAnswer($content, $idAnswer);
    }
}