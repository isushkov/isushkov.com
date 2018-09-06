<?php
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
session_start();
$render = new RefreshData();
$render->checkSession();
$render->summary();
$pageTitle = 'Сброс данных - словарь 2';
?>
<head>
    <title><?php echo $pageTitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <?php echo $render->userThemeRender ?>
</head>
<body>
    <?php include('head.php'); ?>
    <div class="login-wrapper">
        <div class="login">
            <div class="message-yellow">
                Внимание! После сброса дынных восставновить их будет невозможно
            </div>
            <?php if (is_array($render->errors) && count($render->errors) > 0): ?>
                <?php foreach($render->errors AS $error): ?>
                    <div class="message-red">
                        <?php echo $error ?>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
            <h1><?php echo $pageTitle ?></h1>
            <form class="login-form" method="post">
                <div class="login-form-item">
                    <div class="login-form-item-text">Пароль:</div>
                    <input class="login-form-item-input" name="password" type="password"/>
                </div>
                <div class="login-form-item">
                    <div class="login-form-item-text">Повторите пароль:</div>
                    <input class="login-form-item-input" name="check-password" type="password"/>
                </div>
                <input type="hidden" name="type_v" value="5000"/>
                <button class="login-form-submit-red"
                    name="submit" type="submit" value="Удалить профиль">Сбросить данные</button>
            </form>
        </div>
    </div>
</body>
