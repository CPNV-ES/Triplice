<?php
// if name change, template must change to specify css to apply
// for now, take the same css as the take page
$title = 'take';

$idExercise = $params->exercise;
$exerciseName = $params->exerciseName;
$questions = $params->questions;
$updateAnswer = $params->updateAnswer;

$titleSection = "Exercise : " . $exerciseName;

$formAction = '/exercise/' . $idExercise . '/submit';
if ($updateAnswer) {
    $formAction = '/exercise/' . $idExercise . '/answer/'. $params->answer . '/resubmit';
}
?>
<form action='<?= $formAction; ?>' method="post">
    <p>If you want to come back later, you can submit your answers and save the link of the page.</p>

    <?php foreach ($questions as $question): ?>
        <div class="card">
            <div class="title"><?= $question['label'] ?></div>
            <?php
            $inputName = $question['idQuestion'];
            $content = "";
            if ($updateAnswer) {
                $inputName = $question['idAnswer'];
                $content = $question['content'];
            }

            switch ($question['fkQuestionType']):
                case 1: ?>
                    <input name="<?= $inputName ?>" value="<?= $content ?>" type="text">
                    <?php break;
                case 2:
                case 3: ?>
                    <textarea name="<?= $inputName ?>"><?= $content ?></textarea>
                    <?php break;
            endswitch; ?>
        </div>
    <?php endforeach; ?>
    <div class="card">
        <button class="cmdExercise take" type="submit">
            Save
        </button>
    </div>
</form>
