<?php
    $titre='accueil.php';
    ob_start();
?>
<?php
    $contenu=ob_get_clean();
    require 'Gabarit.php';
?>