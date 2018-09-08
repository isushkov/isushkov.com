<?php
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
session_start();
$render = new Home();
$pageTitle = 'Учить английскую лексику';
?>
<?php include('header.php'); ?>
<body>
    <div class="wrapper">
        <?php include('head.php'); ?>
        <?php if ($render->pageNotFound): ?>
            <div class="page-not-found">
                Запрашиваемая Вами страница не найдена
            </div>
        <?php endif ?>
        <div class="index">
            <h1>Часто употребляемые английские слова</h1>
            <div class="content">
                Cловарный запас англоговорящего человека:
                <div class="index-item">
                    <div class="index-green">850 слов - 70%</div>
                    <div class="index-yellow">5000 слов - 90%</div>
                </div>
                <?php if (!$render->isLogin()): ?>
                    <br/>
                    Войдите в систему чтобы выучить их
                <?php endif ?>
            </div>
            <?php if ($render->isLogin()): ?>
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
</body>
