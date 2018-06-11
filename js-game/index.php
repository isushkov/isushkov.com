<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Terminal+Dosis" />
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="img/house.png" type="image/png">
    <script src="js/main.js"></script>
</head>
<body>
    <div class="body-up">
        <div class="battlefield">
            <div class="battlefield-head">
                <div class="gold-info">
                    <p class="name">GOLD:</p>
                    <p class="gold-count">20</p>
                </div>
                <div class="next-turn wait">
                    <p>NEXT TURN</p>
                </div>
            </div>
            <?php $cellId = 0; ?>
            <?php for ($row = 0; $row < 25; $row++): ?>
                <?php for ($column = 0; $column < 35; $column++): ?>
                    <?php $cellId++; ?>
                    <div class="cell"
                        id="<?php echo $cellId ?>"
                        row="<?php echo $row?>"
                        column="<?php echo $column?>"
                        onclick="selectCell(this)">
                        <div class="xp-wrapper">
                            <div class="xp-label" value="0">
                            </div>
                        </div>
                        <div class="move-label"></div>
                    </div>
                <?php endfor; ?>
            <?php endfor; ?>
            <div class="win-message">
                <p>YOU ARE WIN!</p>
            </div>
            <div class="lose-message">
                <p>GAME OVER</p>
            </div>
        </div>
        <div class="log-wrapper">
            <div class="log-info">
                <div class='log-title'>LOG INFO</div>
                <div class='log-empty'></div>
                <?php for ($item = 0; $item < 22; $item++): ?>
                    <div class='log-item'></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <div class="info">
        <div class="house-info">
            <p class="title">HOUSE INFO</p>
            <div class="house-items">
                <button class="info-create-house">
                    Create HOUSE
                    <b class='null'>..</b>
                    <b class='price-int'>50 GOLD</b>
                </button>
                <button class="info-create-men">
                    Create MEN
                    <b class='null'>..</b>
                    <b class='price-int'>20 GOLD</b>
                </button>
                <p class="info-message">Plaese build new item in the MAP</p>
                <p class="exit-build">X</p>
            </div>
        </div>
    </div>
</body>
</html>
