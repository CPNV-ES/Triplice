<?php
    $titre='accueil.php';
    ob_start();
?>
<h1>accueil.php</h1>
<?php
    $contenu=ob_get_clean();
    require 'gabarit.php';
?>