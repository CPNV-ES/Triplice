<?php
// if name change, template must change to specify css to apply
// for now, take the same css as the take page
$title = 'take';

$exerciseName = $params->exerciseName;
$questions = $params->questions;

$titleSection = "Exercise : " . $exerciseName;
?>
<form>
    <?php foreach ($questions as $question): ?>
        <div class="card">
            <div class="title"><?= $question['label'] ?></div>
            <?php switch ($question['fkQuestionType']):
                case 1: ?>
                    <?php break;
                case 2: ?>
                    <?php break;
                case 3: ?>
                    <?php break;
            endswitch; ?>
        </div>
    <?php endforeach; ?>
</form>
