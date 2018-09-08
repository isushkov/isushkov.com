<div class="head">
    <div class="head-content">
        <div class="head-content-left">
            <?php if ($render->showBlock('home')): ?>
                <a href="home.php" class="link-index">На главную</a>
            <?php endif ?>
            <?php if ($render->isLogin() && $render->showBlock('profile')): ?>
                <a href="profile.php" class="link-profile">Мой профиль</a>
            <?php endif ?>
        </div>
        <div class="head-content-right">
            <?php if ($render->isLogin()): ?>
                <a href="logout.php" class="link-exit">Выйти</a>
            <?php else: ?>
                <?php if ($render->showBlock('login')): ?>
                    <a href="login.php" class="link-login">Войти</a>
                <?php endif ?>
                <?php if ($render->showBlock('register')): ?>
                    <a href="register.php" class="link-register">Зарегистрироваться</a>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>
</div>
