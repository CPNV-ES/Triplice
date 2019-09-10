<?php
session_start();
require 'controller/controller.php';
try {
    if (isset($_GET['view'])) {
        switch ($_GET['view']) {
            case 'home':
                home();
                break;

            case 'create':
                create();
                break;
            case 'modify':
                modify();
                break;

            case 'manage':
                manage();
                break;
            case 'take':
                take();
                break;

            case 'error':
                error();
                break;
            default:
                error('404, NOT FOUND.');
                break;
        }
    } else {
        home();
    }
} catch (exception $e) {
    echo $e->getMessage();
}
?>