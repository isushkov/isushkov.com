<?php
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
session_start();
$render = new Login();
$render->summary();

$pageTitle = 'Вход в систему';
?>
<head>
    <title><?php echo $pageTitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <?php echo $render->userThemeRender ?>
</head>
<body>
    <?php include('head.php'); ?>
    <div class="login">
        <?php if (isset($render->deleteUser) && $render->deleteUser): ?>
            <div class="message-green">
                Ваш профиль был удален
            </div>
        <?php endif ?>
        <?php if (isset($render->sessionError)): ?>
            <div class="message-red">
                <?php echo $render->sessionError ?>
            </div>
        <?php endif ?>
        <?php if ((bool)$render->loginErrors): ?>
            <?php foreach ($render->loginErrors as $loginError): ?>
                <div class="message-red">
                    <?php echo $loginError ?>
                </div>
            <?php endforeach ?>
        <?php endif ?>
        <h1><?php echo $pageTitle ?></h1>
        <form class="login-form" method="post">
            <div class="login-form-item">
                <div class="login-form-item-text">
                    Ваш логин:
                </div>
                <input name="login" class="login-form-item-input" type="text"/>
            </div>
            <div class="login-form-item">
                <div class="login-form-item-text">
                    Ваш пароль:
                </div>
                <input name="password" class="login-form-item-input" type="password"/>
            </div>
            <button class="login-form-submit" name="submit" type="submit" value="Войти">Войти</button>
        </form>
    </div>
</body>
