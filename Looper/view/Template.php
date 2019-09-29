<html>
<head>
    <title><?= @$title ?></title>
    <link rel="stylesheet" media="screen" type="text/css" href="../Assets/CSS/header.css"/>
</head>
<body>
<div id="navbar" class="<?= @$titleClass ?> ">
    <div class="section title">
        <a href="/">
            <img src="../Assets/Pictures/logo.png">
        </a>
        <h1><?= @$titleSection?></h1>
    </div>
</div>
<?= $content ?>


</body>
</html>