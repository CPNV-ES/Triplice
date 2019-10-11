<?php
$title = 'modify';

$exercise = $params->exercise;
$questions = $params->questions;
$questionTypes = $params->questionTypes;

$idExercise = $exercise['idExercise'];

$titleSection = 'Modify Exercise : ' . $exercise['name'] . ' (' . $idExercise . ')';

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
            <div class="type">
                <div class="questionType"><?= $question['type'] ?></div>
                <div>
                    <a href="/exercise/<?= $idExercise ?>/question/<?= $question['idQuestion'] ?>/modify"
                       title="Modify question">
                        <div class="fa fa-edit ico"></div>
                    </a>
                    <a href="/exercise/<?= $idExercise ?>/question/<?= $question['idQuestion'] ?>/delete"
                       title="Delete question">
                        <div class="fas fa-trash ico"></div>
                    </a>
                </div>
            </div>
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

