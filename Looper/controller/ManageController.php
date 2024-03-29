<?php

require_once "model/ExerciseModel.php";

class ManageController extends Controller
{
    /**
     * Get all exercises and number of questions by exercise
     */
    static function index()
    {
        $params->exercises = ExerciseModel::getExercises();
        foreach ($params->exercises['Building'] as $exercise)
        {
                $exercise->count=ExerciseModel::questionsCount($exercise->id);
        }
        return view::render("Exercise/Manage",$params);
    }

    /**
     * delete an exercise, then redirect to the manage page
     * @param $params contains exercise, the id of the exercise to delete
     */
    static function deleteExercise($params)
    {
        $exerciseId = $params->exercise;

        // Check if we are allowed to delete the exercise
        if (ExerciseModel::isAnswering($exerciseId) ) {
            $params = new stdClass();
            $params->error = "You are not allowed to delete this exercise. Close it first.";
            $params->message =
                '<a href="/Manage">Manage exercises</a>.';
            return self::error($params);
        }

        // delete the exercise
        ExerciseModel::deleteExercise($exerciseId);

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

        // Check if we are allowed to close the exercise
        if (!ExerciseModel::isAnswering($exerciseId)) {
            $params = new stdClass();
            $params->error = "You are not allowed to close this exercise.";
            $params->message =
                '<a href="/Manage">Manage exercises</a>.';
            return self::error($params);
        }

        ExerciseModel::updateExerciseStatus($exerciseId, 3);

        // redirect to the manage page
        header("Location: http://" . $_SERVER['HTTP_HOST'] . "/manage");
        exit();
    }
}