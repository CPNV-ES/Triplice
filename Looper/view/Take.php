<?php
$title = 'take.php';
ob_start();
?>
<?php
$content = ob_get_clean();
require 'Gabarit.php';
?>