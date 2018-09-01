<?php
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
$head = new Head();
?>
<div class="head">
    <div class="head-content">
        <div class="head-content-left">
            <?php if ($head->showBlock('home')): ?>
                <a href="home.php" class="link-index">На главную</a>
            <?php endif ?>
            <?php if ($head->userLogin() && $head->showBlock('profile')): ?>
                <a href="profile.php" class="link-profile">Мой профиль</a>
            <?php endif ?>
        </div>
        <div class="head-content-right">
            <?php if ($head->userLogin()): ?>
                <a href="logout.php" class="link-exit">Выйти</a>
            <?php else: ?>
                <?php if ($head->showBlock('login')): ?>
                    <a href="login.php" class="link-login">Войти</a>
                <?php endif ?>
                <?php if ($head->showBlock('register')): ?>
                    <a href="register.php" class="link-register">Зарегистрироваться</a>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>
</div>
