<?php


class ExerciseController extends Controller
{
    static function create()
    {
        return View::render("Exercise/Create");
    }
    static function new()
    {
        return View::render("Exercise/Modify");
    }

    static function take()
    {
        return View::render("Take", Database::getAnsweringExercises());
    }
}