<?php
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
session_start();
$render = new RefreshData();
$pageTitle = $render->pageTitle;
?>
<?php include('header.php'); ?>
<body>
    <?php include('head.php'); ?>
    <div class="login-wrapper">
        <div class="login">
            <div class="message-yellow">
                Внимание! После сброса данных восставновить их будет невозможно
            </div>
            <?php if ($render->error): ?>
                <div class="message-red">
                    <?php echo $render->error ?>
                </div>
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
                <button class="login-form-submit-red"
                    name="submit" type="submit" value="Удалить профиль">Сбросить данные</button>
            </form>
        </div>
    </div>
</body>
<?php
echo '<pre>';
    var_dump($render);
echo '</pre>';
