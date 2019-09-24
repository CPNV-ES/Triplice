<?php
    $titre='Manage.php';
    ob_start();
?>

    <br><br>mon id: <?=$id[1]?><br>
<?php
    $contenu=ob_get_clean();
    require 'Gabarit.php';
?>