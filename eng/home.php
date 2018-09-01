<?php
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
session_start();
$render = new App();
$pageTitle = 'Учить английскую лексику';
?>
<head>
    <title><?php echo $pageTitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <?php echo $render->userThemeRender ?>
</head>
<body>
    <div class="wrapper">
        <?php include('head.php'); ?>
        <div class="index">
            <h1>Часто употребляемые английские слова</h1>
            <div class="content">
                Cловарный запас англоговорящего человека:<br/>
                850 слов - 70%<br/>
                5000 слов - 90%<br/>
                Войдите в систему чтобы выучить их
            </div>
            <?php if ($render->userLogin()): ?>
                <div class="content">
                    <form method="post" action="<?php echo 'learn.php' ?>">
                        <input name="change-vocabulary" value="850" type="hidden" />
                        <button class="result-ok-item-green" value="Учить 850 слов" type="submit">Учить 850 слов</button>
                    </form>
                    <form method="post" action="<?php echo 'learn.php' ?>">
                        <input name="change-vocabulary" value="5000" type="hidden" />
                        <button class="result-ok-item-yellow" value="Учить 5000 слов" type="submit">Учить 5000 слов</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="content">
                    <a class="result-ok-item-green" href="login.php">Войти</a>
                    <a class="result-ok-item-yellow" href="register.php">Зарегистрироваться</a>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
</body>
