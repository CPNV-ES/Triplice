<?php


class ExerciseController extends Controller
{
    static function create()
    {
        return View::render("ExerciCreate");
    }

    static function take()
    {
        return View::render("Take");
    }
}