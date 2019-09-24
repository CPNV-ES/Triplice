<?php
$title = 'erreur.php';
ob_start();
?>
    <h1><?= $message ?></h1>
<?php
$content = ob_get_clean();
require 'gabarit.php';
?>