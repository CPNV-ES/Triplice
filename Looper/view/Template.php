<html>
<head>
    <title><?= @$title ?></title>
    <link rel="stylesheet" media="screen" type="text/css" href="/Assets/CSS/style.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <?php switch( @$title ) :
        case "take":?>
            <link rel="stylesheet" media="screen" type="text/css" href="/Assets/CSS/take.css"/>
    <?php break;
        case "create":
        case "modify": ?>
            <link rel="stylesheet" media="screen" type="text/css" href="/Assets/CSS/create.css"/>
    <?php break;
        case "manage": ?>
            <link rel="stylesheet" media="screen" type="text/css" href="/Assets/CSS/manage.css"/>
            <?php break;
        case "results": ?>
            <link rel="stylesheet" media="screen" type="text/css" href="/Assets/CSS/results.css"/>
        <?php break;
        case "error": ?>
            <link rel="stylesheet" media="screen" type="text/css" href="/Assets/CSS/error.css"/>
        <?php break;
    endswitch; ?>
</head>
<body>
<div id="navbar" class="<?= @$title ?> ">
    <div class="section title">
        <a href="/">
            <img src="/Assets/Pictures/logo.png">
        </a>
        <h1><?= @$titleSection ?></h1>
        <?php if( isset($details) && !empty($details) ) :?>
            <p><?=$details?></p>
        <?php endif; ?>
    </div>
</div>
<div id="content">
    <?= $content ?>
</div>
</body>
</html>