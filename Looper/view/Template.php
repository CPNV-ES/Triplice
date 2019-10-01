<html>
<head>
    <title><?= @$title ?></title>
    <link rel="stylesheet" media="screen" type="text/css" href="../Assets/CSS/Style.css"/>
    <link rel="stylesheet" media="screen" type="text/css" href="../Assets/CSS/Header.css"/>
    <link rel="stylesheet" media="screen" type="text/css" href="../Assets/CSS/Exercise.css"/>
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