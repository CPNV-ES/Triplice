<?php

class ExerciseModel
{
    private $params;

    /**
     * ExerciseModel constructor.
     * @param $params params returned by router
     */
    public function __construct($params=null)
    {
        $this->params=$params;
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
        $type=substr(strrchr($url, "/"),1); //get if is up or down information
        switch ($type)
        {
            case "up":
                $current=Database::getSpecificQuestionByOrder($this->params->exercise,$this->params->order);
                $other=Database::getSpecificQuestionByOrder($this->params->exercise,$this->params->order-1);//get question with number before
                break;
            case "down":
                $current=Database::getSpecificQuestionByOrder($this->params->exercise,$this->params->order);
                $other=Database::getSpecificQuestionByOrder($this->params->exercise,$this->params->order+1);//get question with number after
                break;
            default:
                break;
        }
        $tmp=$current["order"];
        $current["order"]=$other["order"];
        $other["order"]=$tmp;

        //update orders
        Database::UpdateQuestionByOrder($current["order"],$current["idQuestion"]);
        Database::UpdateQuestionByOrder($other["order"],$other["idQuestion"]);
    }
}