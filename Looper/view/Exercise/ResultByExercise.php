<?php
$title = 'results';
$titleSection = "Exercise : ";
$details = $params->exercise;

$Querstions = $params->questions;
$exercises = $params->results;

?>
<table>
    <thead>
        <tr>
            <th>
                Takes
            </th>
            <?php foreach ($Querstions as $question) : ?>
                <th>
                    <a href="/exercise/<?=$params->exerciseId?>/results/<?= $question['idQuestion']?>" ><?= $question['label'] ?></a>
                </th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($exercises as $byUser) : ?>
            <tr>
                <th>
                    <a href="/exercise/<?=$params->exerciseId?>/user/<?=$byUser->id?>" ><?= $byUser->name ?></a>
                </th>
                <?php foreach ($byUser->question as $answer) : ?>
                    <th>
                        <?php if(!empty($answer->answer)): ?>
                            <div class="fa fa-check green">
                        <?php endif; ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
