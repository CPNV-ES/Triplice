<?php
    $titre='Manage.php';
    ob_start();
?>
    <br><br>mon id: <?=$params->exercise?><br>
<?php
    $contenu=ob_get_clean();
    require 'Gabarit.php';
?>