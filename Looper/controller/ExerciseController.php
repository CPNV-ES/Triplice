<?php


class ExerciseController extends Controller
{
    static function create()
    {
        return View::render("Exercise/Create");
    }

    static function take()
    {
        return View::render("Take");
    }
}