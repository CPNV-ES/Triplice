<?php
require 'model/ManageModel.php';

class ManageController extends Controller
{
    /**
     * renders the manage view
     */
    static function index()
    {
        return view::render("Exercise/Manage",Database::getAllExercises());
    }

    static function deleteExercise($params)
    {
        // delete the exercise
        $exerciseId = $params->exercise;
        Database::deleteExercise($exerciseId);

        // redirect to manage page
        header("Location: http://".$_SERVER['HTTP_HOST']."/manage");
        exit();
    }
}