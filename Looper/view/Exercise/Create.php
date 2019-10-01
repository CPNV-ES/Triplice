<?php
$title = 'create';
$titleSection = 'Create an exercise';
?>
<form action="/exercise/newExercise" method="post">
    <label for="title">Title</label>
    <input id="title" type="text" required>
    <button type="submit">Create Exercise</button>
</form>
