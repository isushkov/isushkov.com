<?php
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
session_start();
$render = new Profile();
$pageTitle = 'Мой аккаунт';
?>
<head>
    <title><?php echo $pageTitle ?></title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo $render->userThemeRender ?>
</head>
<body>
    <?php include('head.php'); ?>
    <?php if ($render->newUser): ?>
        <div class="profile-message">
            Добро пожаловать, <?php echo $render->getLogin() ?>
        </div>
    <?php endif ?>
    <?php if ($render->refreshMessage): ?>
        <div class="profile-message">
            Вы успешно сбросили данные
        </div>
    <?php endif ?>
    <div class="profile-wrapper">
        <div class="profile">
            <div class="profile-login">Логин: <span class="profile-green"><?php echo $render->getLogin() ?></span></div>
            <div class="profile-content">
                <div class="profile-content-column">
                    <div class="profile-content-column-title">Словарь 1</div>
                    <div class="profile-content-column-item">
                        Всего: <span class="profile-all"><?php echo $render->getVocabularyCount('850') ?></span>
                    </div>
                    <div class="profile-content-column-item">
                        Известныe: <span class="profile-blue"><?php echo $render->getProgressCount('850') ?></span>
                    </div>
                    <div class="profile-content-column-item">
                        Хорошо знаю: <span class="profile-green"><?php echo $render->getProgressCount('850', 'SS') ?></span>
                    </div>
                    <div class="profile-content-column-item">
                        Знаю: <span class="profile-yellow"><?php echo $render->getProgressCount('850', 'S') ?></span>
                    </div>
                    <div class="profile-content-column-item">
                        Плохо знаю: <span class="profile-red"><?php echo $render->getProgressCount('850', 'E') ?></span>
                    </div>
                    <div class="profile-content-column-item">
                        He знаю: <span class="profile-red"><?php echo $render->getProgressCount('850', 'EE') ?></span>
                    </div>
                    <form class="profile-content-column-learn" method="post" action="learn.php">
                        <input type="hidden" name="change-vocabulary" value="850"/>
                        <button type="submit" class="input-green" value="Учить 850 слов">Учить 850 слов</button>
                    </form>
                    <div class="refresh-wrapper">
                        <a class="refresh" href="refresh-data850.php">Начать сначала</a>
                    </div>
                </div>
                <div class="profile-content-column">
                    <div class="profile-content-column-title">Словарь 2</div>
                    <div class="profile-content-column-item">
                        Всего: <span class="profile-all"><?php echo $render->getVocabularyCount('5000') ?></span>
                    </div>
                    <div class="profile-content-column-item">
                        Известныe: <span class="profile-blue"><?php echo $render->getProgressCount('5000') ?></span>
                    </div>
                    <div class="profile-content-column-item">
                        Хорошо знаю: <span class="profile-green"><?php echo $render->getProgressCount('5000', 'SS') ?></span>
                    </div>
                    <div class="profile-content-column-item">
                        Знаю: <span class="profile-yellow"><?php echo $render->getProgressCount('5000', 'S') ?></span>
                    </div>
                    <div class="profile-content-column-item">
                        Плохо знаю: <span class="profile-red"><?php echo $render->getProgressCount('5000', 'E') ?></span>
                    </div>
                    <div class="profile-content-column-item">
                        He знаю: <span class="profile-red"><?php echo $render->getProgressCount('5000', 'EE') ?></span>
                    </div>
                    <form class="profile-content-column-learn" method="post" action="learn.php">
                        <input name="change-vocabulary" value="5000" type="hidden"/>
                        <button class="input-yellow" value="Учить 5000 слов" type="submit">Учить 5000 слов</button>
                    </form>
                    <div class="refresh-wrapper">
                        <a class="refresh" href="refresh-data5000.php">Начать сначала</a>
                    </div>
                </div>
            </div>
            <div class="profile-content2">
                <form class="profile-content2-theme" method="post" action="profile.php">
                    <input type="hidden" name="change-theme" value="1"/>
                    <button type="submit" class="input-blue" value="Сменить тему">Сменить тему</button>
                </form>
                <a class="profile-content2-delete" href="delete-profile.php">Удалить профиль</a>
            </div>
        </div>
    </div>
</body>
