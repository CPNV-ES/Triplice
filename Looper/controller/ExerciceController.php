<?php


class ExerciceController extends Controller
{
    static function index()
    {
        return View::render("Home");
    }
    static function take()
    {
        return View::render("Take");
    }
}