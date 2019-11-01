<?php
$title = 'results';
$titleSection = "Exercise : ";
$details = $params->exercise['name'];
$exercises= $params->results;

?>
<div>

    <h1><?= $details ?></h1>

    <br>
    <br>
    <br>
    <?php foreach ($exercises as $byUser) :?>
        <?php foreach ($byUser->question as $answer) :?>
            <div><?=$answer->label?> : <?=$answer->answer?></div>
            <br>
        <?php endforeach; ?>
        <br>
        <br>
    <?php endforeach; ?>
</div>
