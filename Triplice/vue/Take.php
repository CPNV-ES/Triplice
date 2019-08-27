<?php
    $titre='Take.php';
    ob_start();
?>
<h1>Take.php</h1>
<?php
    $contenu=ob_get_clean();
    require 'gabarit.php';
?>