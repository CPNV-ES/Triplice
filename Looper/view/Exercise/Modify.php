<?php
$title = 'modify';

$exerciseId = $params->exercise;
Controller::databaseInformations();
$exercise = Database::getExercise($exerciseId);
$questions = Database::getQuestions($exerciseId);

$titleSection = 'Modify Exercise : '.$exercise['name'].' ('.$exerciseId.')';

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
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div class="newQuestionForm">
    <h2>
        New question
    </h2>
    <form action="looper?view=modify" method="post">
        <label for="label">Label</label>
        <input type="text" name="label" id="label" required>

        <label for="answerType">Answer type</label>
        <select name="answerType" id="answerType">
            <option value="single">Single line text</option>
            <option value="list">List of single lines</option>
            <option value="multi">Multi-line text</option>
        </select>

        <button type="submit">Create field</button>
    </form>
</div>

