<?php
    $titre='erreur.php';
    ob_start();
?>
<h1>ERREUR 404</h1>
<?php
    $contenu=ob_get_clean();
    require 'Gabarit.php';
?>