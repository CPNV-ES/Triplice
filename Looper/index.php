<?php
    session_start();
    require 'controleur/controleur.php';
    try
    {
        if(isset($_GET['view']))
        {
            switch($_GET['view'])
            {
                case 'home':
                    home();
                    break;
                case 'error':
                    error();
                    break;
                
                case 'Manage':
                    Manage();
                    break;
                case 'Create':
                    Create();
                    break;
                case 'Take':
                    Take();
                    break;
                default:
                    error('404, NOT FOUND.');
                    break;
            }
        }
        else
        {
            home();
        }
    }
    catch(exception $e)
    {
        echo $e->getMessage();
    }
?>