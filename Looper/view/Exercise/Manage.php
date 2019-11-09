<?php
$title = 'manage';
$titleSection="manage an exercise";
?>
<?php foreach (@$params->exercises as $key => $exercises):?>
<div class="column">
    <h1><?=$key?></h1>
    <h2>Title</h2>
    <?php foreach ($exercises as $exercise):?>
    <div class="row">
        <div class="title"><?=$exercise->name?></div>
        <div>
            <?php switch($key):
                case "Building": ?>
                    <?php if($exercise->count>0):?>
                        <a href="/exercise/<?=$exercise->id?>/completeExercise" title="Complete and be ready for answers"><div class="fa fa-comment ico"></div></a>
                    <?php endif; ?>
                    <a href="./Exercise/<?=$exercise->id?>/modify" title="Edit exercise"><div class="fa fa-edit ico"></div></a>
                    <a href="./Exercise/<?=$exercise->id?>/delete" title="Delete exercise"><div class="fas fa-trash ico"></div></a>
                <?php break;
                case "Answering": ?>
                    <a href="./Exercise/<?=$exercise->id?>/results" title="Show results"><div class="fa fa-chart-bar ico"></div></a>
                    <a href="./Exercise/<?=$exercise->id?>/close"  title="Close exercise"><div class="far fa-window-close ico"></div></a>
                <?php break;
                case "Closed": ?>
                    <a href="./Exercise/<?=$exercise->id?>/results" title="Show results"><div class="fa fa-chart-bar ico"></div></a>
                    <a href="./Exercise/<?=$exercise->id?>/delete"  title="Delete exercise"><div class="fas fa-trash ico"></div></a>
                <?php break;
            endswitch;?>
        </div>
    </div>
    <?php endforeach;?>
</div>
<?php endforeach;?>
