<?php
require 'model/ManageModel.php';

class ManageController extends Controller
{
    static function index()
    {
        return view::render("Exercise/Manage",getExercises());
    }
}