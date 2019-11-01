<?php
require 'model/ManageModel.php';

class ManageController extends Controller
{
    /**
     * renders the manage view
     */
    static function index()
    {
        return view::render("Exercise/Manage", Database::getAllExercises());
    }

    /**
     * delete an exercise, then redirect to the manage page
     * @param $params contains exercise, the id of the exercise to delete
     */
    static function deleteExercise($params)
    {
        // delete the exercise
        $exerciseId = $params->exercise;
        Database::deleteExercise($exerciseId);

        // redirect to manage page
        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/manage");
        exit();
    }

    /**
     * change the status of an exercise to 'closed'
     * @param $params contains exercise, the id of the exercise
     */
    static function closeExercise($params)
    {
        // update exercise status to 'answering'
        $exerciseId = $params->exercise;
        Database::modifyExerciseStatus($exerciseId, 3);

        // redirect to the manage page
        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/manage");
        exit();
    }
}