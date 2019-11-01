<?php
$title = 'modify';

$exercise = $params->exercise;
$questions = $params->questions;
$questionTypes = $params->questionTypes;

$modifyQuestion = $params->modifyQuestion;
$questionToModify = null;
$questionLabel = '';
$submitButtonText = 'Create field';
if ($modifyQuestion) {
    $questionToModify = $params->questionToModify;
    $questionLabel = $questionToModify['label'];
    $submitButtonText = 'Modify question';
}

$idExercise = $exercise['idExercise'];

$titleSection = 'Modify Exercise : ' . $exercise['name'];

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

    <div class="buttonRow">
        <button onclick="window.location.href = 'http://<?= $_SERVER["HTTP_HOST"] ?>/exercise/<?= $idExercise ?>/completeExercise';">
            Complete and be ready for answers
        </button>
    </div>
</div>

<div class="newQuestionForm">
    <h2>
        New question
    </h2>
    <form action='/exercise/<?= $idExercise; ?>/modify' method="post">
        <label for="label">Label</label>
        <input type="text" name="label" id="label" value="<?= $questionLabel ?>" required>

        <label for="answerType">Answer type</label>
        <select name="idAnswerType" id="idAnswerType">
            <?php foreach ($questionTypes as $questionType): ?>
                <option value="<?= $questionType['idQuestionType'] ?>"
                    <?php if ($modifyQuestion && $questionType['idQuestionType'] == $questionToModify['fkQuestionType']): ?>
                        selected
                    <?php endif; ?>
                ><?= $questionType['type'] ?></option>
            <?php endforeach; ?>
        </select>

        <?php if ($modifyQuestion): ?>
            <input type="text" name="idQuestionToModify" id="idQuestionToModify"
                   value="<?= $questionToModify['idQuestion'] ?>" required hidden>
        <?php endif; ?>

        <button type="submit">
            <?= $submitButtonText ?>
        </button>
    </form>
</div>

