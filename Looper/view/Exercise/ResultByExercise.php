<?php
$title = 'results';
$titleSection = "Exercise : ";
$details = "<a href='/exercise/$params->exerciseId/results'>$params->exercise</a>";

$Questions = $params->questions;
$exercises = $params->results;

?>
<table>
    <thead>
        <tr>
            <th>
                Takes
            </th>
            <?php foreach ($Questions as $question) : ?>
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
                    <th class="center">
                        <?php if(!empty($answer->answer)): ?>
                            <?php if(strlen($answer->answer)>10): ?>
                                <div class="fa fa-check-double">
                            <?php else : ?>
                                <div class="fa fa-check">
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="fa fa-times">
                        <?php endif; ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
