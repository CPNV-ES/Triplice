<?php
    $titre='Create an exercise';
    ob_start();
?>

<form method="post">
    <label for="title">Title</label>
    <input type="text" name="title" id="title" required>

    <button>Create</button>
</form>

<?php
    $contenu=ob_get_clean();
    require 'Gabarit.php';
?>