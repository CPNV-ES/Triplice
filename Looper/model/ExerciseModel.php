<?php

class ExerciseModel
{
    private $params;

    private const MAX_NAME_LENGTH = 50;

    /**
     * ExerciseModel constructor.
     * @param $params params returned by router
     */
    public function __construct($params = null)
    {
        $this->params = $params;
    }

    /**
     * @param $exerciseName name of the new exercise
     * @return int id of the exercise created
     * @throws Exception if zhe name is too long
     */
    public static function createExercise($exerciseName)
    {
        // expected input :
        // * title : string, length <= 50
        if (mb_strlen($exerciseName) <= self::MAX_NAME_LENGTH) {
            $exerciseId = Database::createExercise($exerciseName);
            return $exerciseId;
        } else {
            throw new Exception('Name too long');
        }
    }

    /**
     * Check if an exercise is modifiable
     *
     * @param $exerciseId
     * @return bool
     */
    public static function isModifiable($exerciseId)
    {
        $exercise = Database::getExerciseWithStatus($exerciseId);
        $isModifiable = ($exercise['status'] == 'Building');
        return $isModifiable;
    }

    /**
     * Check if an exercise is answering
     *
     * @param $exerciseId
     * @return bool
     */
    public static function isAnswering($exerciseId)
    {
        $exercise = Database::getExerciseWithStatus($exerciseId);
        $isModifiable = ($exercise['status'] == 'Answering');
        return $isModifiable;
    }

    /**
     * check order of question and use url to get if user want up or down the order of question
     * Update the order of question based on url information
     *
     * @param $url current url
     * @example Up : $url -> /exercise/1/order/2/up
     * @example Down : $url -> /exercise/1/order/1/down
     */
    public function OrderQuestion($url)
    {
        $type = substr(strrchr($url, "/"), 1); //get if is up or down information
        switch ($type) {
            case "up":
                $current = Database::getSpecificQuestionByOrder($this->params->exercise, $this->params->order);
                $other = Database::getSpecificQuestionByOrder($this->params->exercise, $this->params->order - 1);//get question with number before
                break;
            case "down":
                $current = Database::getSpecificQuestionByOrder($this->params->exercise, $this->params->order);
                $other = Database::getSpecificQuestionByOrder($this->params->exercise, $this->params->order + 1);//get question with number after
                break;
            default:
                break;
        }
        $tmp = $current["order"];
        $current["order"] = $other["order"];
        $other["order"] = $tmp;

        //update orders
        Database::UpdateQuestionByOrder($current["order"], $current["idQuestion"]);
        Database::UpdateQuestionByOrder($other["order"], $other["idQuestion"]);
    }
}