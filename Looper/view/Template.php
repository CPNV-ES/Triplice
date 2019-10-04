<html>
<head>
    <title><?= @$title ?></title>
    <link rel="stylesheet" media="screen" type="text/css" href="../Assets/CSS/Header.css"/>
    <link rel="stylesheet" media="screen" type="text/css" href="../Assets/CSS/Style.css"/>
    <?php switch( @$title ) :
        case "take":?>
            <link rel="stylesheet" media="screen" type="text/css" href="../Assets/CSS/Take.css"/>
    <?php break;
        case "create": ?>
            <link rel="stylesheet" media="screen" type="text/css" href="../Assets/CSS/Create.css"/>
    <?php break;
        case "manage": ?>
            <link rel="stylesheet" media="screen" type="text/css" href="../Assets/CSS/Manage.css"/>
    <?php break;
    case "modify": ?>
        <link rel="stylesheet" media="screen" type="text/css" href="../../Assets/CSS/Create.css"/>
    <?php break;
    endswitch; ?>
</head>
<body>
<div id="navbar" class="<?= @$title ?> ">
    <div class="section title">
        <a href="/">
            <img src="../Assets/Pictures/logo.png">
        </a>
        <h1><?= @$titleSection ?></h1>
    </div>
</div>
<diV id="content">
    <?= $content ?>
</diV>
</body>
</html>