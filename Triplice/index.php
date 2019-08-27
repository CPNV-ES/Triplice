<?php
    session_start();
    require 'controleur/controleur.php';
    try
    {
        if(isset($_GET['action']))
        {
            switch($_GET['action'])
            {
                case 'accueil':
                    accueil();
                    break;
                case 'erreur':
                    erreur();
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
                    erreur('La page que vous cherchez est introuvable.');
                    break;
            }
        }
        else
        {
            accueil();
        }
    }
    catch(exception $e)
    {
        echo $e->getMessage();
    }
?>