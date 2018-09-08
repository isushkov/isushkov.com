<?php
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
session_start();
$render = new Login();
$pageTitle = 'Вход в систему';
?>
<?php include('header.php'); ?>
<body>
    <?php include('head.php'); ?>
    <div class="login">
        <?php if ($render->deleteUser): ?>
            <div class="message-green">
                Ваш профиль был удален
            </div>
        <?php endif ?>
        <?php if ($render->sessionError): ?>
            <div class="message-red">
                <?php echo $render->sessionError ?>
            </div>
        <?php endif ?>
        <?php if ($render->loginError): ?>
            <div class="message-red">
                <?php echo $render->loginError ?>
            </div>
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
