<?php

class TakeModel
{
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

        return self::getLastTakeId();
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

    /**
     * Create an answer
     * @param string $answer content of the answer
     * @param int $idTake id of the take including the answer
     * @param int $idQuestion id of the question the take is answering
     */
    public static function createAnswer($answer, $idTake, $idQuestion)
    {
        $pdo = Database::dbConnection();
        $query =
            'INSERT INTO answers(content, fkQuestion, fkTake)
            VALUES (?, ?, ?)
            ;';
        $pdo->prepare($query)->execute([$answer, $idQuestion, $idTake]);
    }
}