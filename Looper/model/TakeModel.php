<?php

class TakeModel
{
    /**
     * Create a take
     * @return int the id of the take
     */
    public static function createTake()
    {
        $idTake = Database::createTake();
        return $idTake;
    }

    /**
     * Create an answer
     * @param string $content content of the answer
     * @param int $idTake id of the take including the answer
     * @param int $idQuestion id of the question the take is answering
     */
    public static function createAnswer($answer, $idTake, $idQuestion)
    {
        Database::createAnswer($answer, $idTake, $idQuestion);
    }
}