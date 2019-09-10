<?php
    $titre='accueil.php';
    ob_start();
?>

<a href='Looper?view=create' >
    Create Exercise
</a>

<?php
    $contenu=ob_get_clean();
    require 'Gabarit.php';
?>