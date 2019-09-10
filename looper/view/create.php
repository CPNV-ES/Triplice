<?php
$title = 'Create an exercise';
ob_start();
?>

    <form action="looper?view=modify" method="post">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" required>

        <button type="submit">Create</button>
    </form>

<?php
$content = ob_get_clean();
require 'Gabarit.php';
?>