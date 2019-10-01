<?php
    $title = 'take';
    $titleSection="Take an exercise";
?>
<?php if(isset($params) && !empty($params)): ?>
    <?php foreach ($params as $exercise): $exercise=(object)$exercise?>
        <div class="card">
            <div class="title"><?=$exercise->name?></div>
            <a class="cmdExercise take" href="/Exercise/<?=$exercise->idExercise?>/">Take it</a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="card">
        <div class="title">No exercises</div>
        <a class="cmdExercise create" href="/Exercise/Create">Create an Exercise</a>
    </div>
<?php endif; ?>