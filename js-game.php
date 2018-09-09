<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='shortcut icon' href='js-game/img/house.png' type='image/png'>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/project.css">
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-101911065-1', 'auto');
        ga('send', 'pageview');

    </script>
    <title>Simple JS-Game</title>
    <link rel='stylesheet' href='css/js-game.css'>
</head>
<body>
<div class="wrapper">
    <div class="wrapper2">
        <header>
            <div class="header2">
                <div class="headerLeft">
                    <a href="index.html">
                        <p class="name">ILYA SUSHKOV</p>
                    </a>
                    <p class="artist">Developer & Artist</p>
                </div>
                <div class="headerRight">
                    <div class="headerRightItem">
                        <a href="me.html">
                            <p class="linkHeader">ABOUT ME</p>
                        </a>
                    </div>
                    <div class="headerRightItem">
                        <a href="index.html">
                            <p class="linkHeader">HOME</p>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="projectContent">
            <div class="gallery">
                <div class="galleryLeft">
                    <a href="english.php">
                        <div class="galleryLinkLeft">
                            <h1 class="indexLeft"><</h1>
                        </div>
                    </a>
                </div>
                <div class="content-gallery">
                    <p class="project-name">Simple JS-Game</p>
                    <a class="a-js-img" href="img/js-game.jpg">
                        <img class="js-img" src="img/js-game.jpg" alt="js-game image">
                    </a>
                </div>
                <div class="galleryRight">
                    <a href="venomSnake.html">
                        <div class="galleryLinkRight">
                            <h1 class="indexRight">></h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="descriptionBlock">
                <div class="descriptionBlockItem2">
                    <h1 class="descriptionBlockName">Описание</h1>
                    <p class="description">
                        Примитивная стратегия.<br>
                        <span class='emphasis'>Демонстрация базовых знаний JavaScript</span>
                        без использования фреймворков. Игра не требует установки.
                    </p>
                    <div class='js-notice'>
                        <h1 class="js-notice-title">Внимание</h1>
                        <p class="js-notice-description">
                            Отсутствует адаптивная верстка.
                            Игра нормально функционирует <span class='js-notice-emphasis'>только на компьютере</span>,
                            с разрешением экрана <span class='js-notice-emphasis'>не менее 1000 px</span>, иначе все посыпится :)
                        </p>
                    </div>
                </div>
                <div class="empty"></div>
            </div>
            <div class='wrapper-js-play'>
                <a class='js-play' href='js-game/index.php'>PLAY GAME</a>
            </div>
            <div class="empty"></div>
            <div class="empty"></div>
        </div>
    </div>
    <div class="footer">
        <div class="footerCenter">
            <p class="update">Last update: 13-06-2018</p>
        </div>
    </div>
</div>
</body>
</html>
