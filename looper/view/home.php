<?php
$title = 'accueil.php';
ob_start();
?>

    <a href='looper?view=create'>
        Create Exercise
    </a>

<?php
$content = ob_get_clean();
require 'Gabarit.php';
?>