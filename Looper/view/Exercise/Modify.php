<?php
$title = 'modify';

$exercise = $params->exercise;
$questions = $params->questions;
$questionTypes = $params->questionTypes;

$idExercise = $exercise['idExercise'];

$titleSection = 'Modify Exercise : ' . $exercise['name'] . ' (' . $idExercise . ')';

?>
<div class="questionsTable">
    <h2>
        Questions
    </h2>
    <table>
        <thead>
        <tr>
            <th>Question</th>
            <th>Answer type</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($questions as $question): ?>
            <tr>
                <td><?= $question['label'] ?></td>
                <td><?= $question['type'] ?></td>
                <td>
                    <a href="exercise/<?= $idExercise ?>/question/<?= $question['idQuestion'] ?>/modify"
                       title="Modify question">
                        <div class="fa fa-edit ico"></div>
                    </a>
                    <a href="exercise/<?= $idExercise ?>/question/<?= $question['idQuestion'] ?>/delete"
                       title="Delete question">
                        <div class="fas fa-trash ico"></div>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
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

