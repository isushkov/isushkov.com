<?php
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
session_start();
$render = new Register();
$pageTitle = 'Регистрация нового пользователя';
?>
<?php include('header.php'); ?>
<body>
    <?php include('head.php'); ?>
    <div class="login">
        <?php if (isset($render->errors) && count($render->errors) > 0): ?>
            <?php foreach($render->errors AS $error): ?>
                <div class="message-red">
                    <?php echo $error ?>
                </div>
            <?php endforeach ?>
        <?php endif ?>
        <h1><?php echo $pageTitle ?></h1>
        <form class="login-form" method="post">
            <div class="login-form-item">
                <div class="login-form-item-text">
                    Логин:
                </div>
                <input class="login-form-item-input" name="login" type="text"/>
            </div>
            <div class="login-form-item">
                <div class="login-form-item-text">
                    Пароль:
                </div>
                <input class="login-form-item-input" name="password" type="password"/>
            </div>
            <div class="login-form-item">
                <div class="login-form-item-text">
                    Повторите пароль:
                </div>
                <input class="login-form-item-input" name="check-password" type="password"/>
            </div>
            <button class="login-form-submit-blue" name="submit" type="submit" value="Зарегистрироваться">Зарегистрироваться</button>
        </form>
    </div>
</body>
<?php
echo '<pre>';
    var_dump($render);
echo '</pre>';
