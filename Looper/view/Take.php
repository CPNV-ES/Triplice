<?php
$title = 'take';
$titleSection = "Take an exercise";
?>
<div class="cardContainer">
    <?php if (isset($params) && !empty($params)): ?>
        <?php foreach ($params as $exercise): ?>
            <div class="card">
                <div class="title"><?= $exercise->name ?></div>
                <a class="cmdExercise take" href="/exercise/<?= $exercise->idExercise ?>/take">Take it</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="card">
            <div class="title">No exercises</div>
            <a class="cmdExercise create" href="/exercise/create">Create an Exercise</a>
        </div>
    <?php endif; ?>
</div>
