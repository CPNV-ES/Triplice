<?php
$title = 'results';
$titleSection = "Exercise : ";
$details = $params->exercise;

$questionName=$params->question['label'];
?>
<h1><?=$questionName?></h1><br>
<table>
    <thead>
        <tr>
            <th>
                Take
            </th>
            <th>
                Content
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($params->results as $user) : ?>
            <tr>
                <th>
                    <a href="/exercise/<?=$params->exerciseId?>/user/<?=$user->id?>" > <?= $user->name ?></a>
                </th>
                <?php foreach ($user->question as $answer) : ?>
                    <th>
                        <?= $answer->answer ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
