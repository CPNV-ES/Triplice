<?php
$title = 'modify';

$exercise = $params->exercise;
$questions = $params->questions;
$questionTypes = $params->questionTypes;

$idExercise = $exercise['idExercise'];

$titleSection = 'Modify Exercise '.$idExercise.' :';
$details = $exercise['name'];

?>
<div class="questionsTable">
    <h1>Questions</h1>
    <div class="row title">
        <div class="label">Question</div>
        <div class="label">Answer type</div>
    </div>

    <?php foreach ($questions as $question): ?>
        <div class="row title">
            <div class="label"><?= $question['label'] ?></div>
            <div class="type"><?= $question['type'] ?></div>
        </div>
    <?php endforeach; ?>
</div>

<div class="newQuestionForm">
    <h2>
        New question
    </h2>
    <form action='/exercise/<?= $idExercise; ?>/modify' method="post">
        <label for="label">Label</label>
        <input type="text" name="label" id="label" required>

        <label for="answerType">Answer type</label>
        <select name="idAnswerType" id="idAnswerType">
            <?php foreach ($questionTypes as $questionType): ?>
                <option value="<?= $questionType['idQuestionType'] ?>"><?= $questionType['type'] ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Create field</button>
    </form>
</div>

