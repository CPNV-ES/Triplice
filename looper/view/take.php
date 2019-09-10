<?php
    $titre='take.php';
    ob_start();
?>
<?php
    $contenu=ob_get_clean();
    require 'Gabarit.php';
?>