window.onload = function () {
var houseInfo = document.getElementsByClassName('house-info')[0];
var infoCreateHouse = document.getElementsByClassName('info-create-house')[0];
var infoCreateMen = document.getElementsByClassName('info-create-men')[0];
var infoMessage = document.getElementsByClassName('info-message')[0];
var infoExitBuild = document.getElementsByClassName('exit-build')[0];
var logItems = document.getElementsByClassName('log-info')[0];

var allCells = document.getElementsByClassName('cell');
var goldCount = +document.getElementsByClassName('gold-count')[0].textContent;
var btnNewTurn = document.getElementsByClassName('next-turn')[0];
// -------------------------------
// -- generate units -------------
// -------------------------------
function generateUnits() {
    setNewUnit('house', document.getElementById(145));
    setNewUnit('men', document.getElementById(146));
    var menMoveLabel = document.getElementById(146).getElementsByClassName('move-label')[0];
    menMoveLabel.classList.remove('red');
    menMoveLabel.classList.add('green');
    setNewUnit('house-enemy', document.getElementById(730));
    setNewUnit('men-enemy', document.getElementById(624));
    setNewUnit('men-enemy', document.getElementById(727));
}
generateUnits();
// -------------------------------
// -- new turn -------------------
// -------------------------------
var countTurns = 0;
btnNewTurn.onclick = function() {
    countTurns++;
    unselectAllCells();

    // enemy turn
    enemyTurn();

    for (cell of allCells) {
        if (cell.classList.contains('men')) {
            var spaceLabel = cell.getElementsByClassName('move-label')[0];
            cell.setAttribute('move', '3');
            spaceLabel.classList.remove('yellow');
            spaceLabel.classList.remove('yellow2');
            spaceLabel.classList.remove('red');
            spaceLabel.classList.add('green');
            spaceLabel.style.display = 'flex';
        }
    }
    var houseQty = document.getElementsByClassName('house').length;
    goldCount = (+goldCount)+(5*(+houseQty));
    document.getElementsByClassName('gold-count')[0].innerHTML = goldCount;
    addNewTurnLog(+houseQty, 5*(+houseQty));
    btnNewTurn.classList.remove('go');
    btnNewTurn.classList.add('wait');
}
// -------------------------------
// -- emeny turn -----------------
// -------------------------------
function enemyTurn() {
    unselectAllCells();
    var allEnemyMens = document.getElementsByClassName('men-enemy');
    for (i = 0; i < allEnemyMens.length; i++) {
        allEnemyMens[i].setAttribute('move', '3');

        if (allEnemyMens[i].getAttribute('move') == '3') {
            oneCellMovingEnemy(allEnemyMens[i]);
        }
        if (allEnemyMens[i].getAttribute('move') == '2') {
            oneCellMovingEnemy(allEnemyMens[i]);
        }
        if (allEnemyMens[i].getAttribute('move') == '1') {
            oneCellMovingEnemy(allEnemyMens[i]);
        }
    }
    function oneCellMovingEnemy(menEnemy) {
        var moveValue = +menEnemy.getAttribute('move');
        var surround = getSurroundCells(menEnemy, 1);
        // atack
        var checkAtack = 0;
        // search men
        for (a in surround) {
            if (checkAtack == 0) {
                if (isUserUnit(surround[a]) &&
                    isMen(surround[a])) {
                    // atack
                    menAtackForEnemy(allEnemyMens[i], surround[a]);
                    moveValue = 0;
                    checkAtack = 1;
                }
            }
        }
        // else search house
        for (a in surround) {
            if (checkAtack == 0) {
                if (isUserUnit(surround[a]) &&
                    isHouse(surround[a])) {
                    // atack
                    menAtackForEnemy(allEnemyMens[i], surround[a]);
                    moveValue = 0;
                    checkAtack = 1;
                }
            }
        }
        // move
        if (moveValue != 0) {
            // get position agressor
            var enemyPosition = {
                'row': +menEnemy.getAttribute('row'),
                'column': +menEnemy.getAttribute('column')
            };
            // get units user
            var allUserUnits = document.querySelectorAll('.men,.house');
            // var allUserMens = document.getElementsByClassName('men');
            // var allUserHouses = document.getElementsByClassName('house');
            var userPositions = {};
            var count = 0
            for (userMen of allUserUnits) {
                // get position
                userPositions[count] = {
                    'row': +userMen.getAttribute('row'),
                    'column': +userMen.getAttribute('column')
                };
                count++;
            }
            // get near position
            var distancesObj = {};
            for (item in userPositions) {
                // get delta
                var deltaColumn = Math.abs(enemyPosition['column']-userPositions[item]['column']);
                var deltaRow = Math.abs(enemyPosition['row']-userPositions[item]['row']);
                var checkDistance = deltaRow + deltaColumn;
                distancesObj[item] = checkDistance;
            }
            // get minimal delta
            var minimalDelta = 1000000;
            var nearItem = '';
            for (item in distancesObj) {
                if (distancesObj[item] < minimalDelta) {
                    minimalDelta = distancesObj[item];
                    nearItem = item;
                }
            }
            // get position minimal distance
            var columnNear = allUserUnits[nearItem].getAttribute('column');
            var rowNear = allUserUnits[nearItem].getAttribute('row');
            // get delta +1 cell to minimal
            var deltaRowSigment = enemyPosition.row - (+rowNear);
            var deltaColumnSigment = enemyPosition.column - (+columnNear);
            // get this cell
            var positionToMove = {
                'row': enemyPosition.row,
                'column': enemyPosition.column
            };
            if (deltaRowSigment > 0) {
                positionToMove.row--;
            } else if (deltaRowSigment < 0) {
                positionToMove.row++;
            }
            if (deltaColumnSigment > 0) {
                positionToMove.column--;
            } else if (deltaColumnSigment < 0) {
                positionToMove.column++;
            }
            for (cell of allCells) {
                if (cell.getAttribute('row') == (''+positionToMove.row)) {
                    if (cell.getAttribute('column') == (''+positionToMove.column)) {
                        // console.log(cell);
                        if (!isUnit(cell)) {
                            //moving to this cell
                            movingMenForEnemy(allEnemyMens[i], cell);
                            moveValue--;
                        } else {
                            // random move
                            var surroundLength = 0;
                            for (a in surround) {
                                surroundLength++
                            }
                            var random = 0;
                            random = randomInteger(1, surroundLength)

                            if (!isUnit(surround[random])) {
                                movingMenForEnemy(allEnemyMens[i], surround[random]);
                                moveValue--;
                            } else {
                                random = randomInteger(1, surroundLength)
                                if (!isUnit(surround[random])) {
                                    movingMenForEnemy(allEnemyMens[i], surround[random]);
                                    moveValue--;
                                } else {
                                    random = randomInteger(1, surroundLength)
                                    if (!isUnit(surround[random])) {
                                        movingMenForEnemy(allEnemyMens[i], surround[random]);
                                        moveValue--;
                                    } else {
                                        sonsole.log('skip move');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        allEnemyMens[i].setAttribute('move', ''+moveValue+'');
    }
    // buildMenEnemy
    var enemyHouses = document.getElementsByClassName('house-enemy');
    if (enemyHouses.length > 0) {
        if (countTurns % 3 == 0) {
            var enemyHouse = document.getElementsByClassName('house-enemy')[0];
            var surround = getSurroundCells(enemyHouse, 1);
            var checkBuildMen = 0;
            for (i in surround) {
                if (checkBuildMen == 0) {
                    if (!isUnit(surround[i])) {
                        setNewUnit('men-enemy', surround[i]);
                        checkBuildMen = 1;
                    }
                }
            }
        }
    }
    for (i = 0; i < allEnemyMens.length; i++) {
        allEnemyMens[i].removeAttribute('move');
    }
}
// -- move men -----------------
// for enemy
function movingMenForEnemy(oldPosition, newPosition) {
    var oldPositionXpWrapper = oldPosition.getElementsByClassName('xp-wrapper')[0];
    var oldPositionXpLabel = oldPosition.getElementsByClassName('xp-label')[0];
    var oldPositionXpValue = oldPositionXpLabel.getAttribute('value');
    var newPositionXpWrapper = newPosition.getElementsByClassName('xp-wrapper')[0];
    var newPositionXpLabel = newPosition.getElementsByClassName('xp-label')[0];
    // add class
    newPosition.classList.add('men-enemy');
    // add xp-label
    newPositionXpLabel.setAttribute('value', oldPositionXpValue);
    checkXp(newPosition, 0);
    newPositionXpWrapper.style.display = 'flex';

    destroyUnit(oldPosition);
}
// -- men atack ----------------
// for enemy
function menAtackForEnemy(agressorCell, cellAtacked) {
    var xpCellAtacked = cellAtacked.getElementsByClassName('xp-label')[0];
    var xpAtackedValue = +xpCellAtacked.getAttribute('value');

    xpCellAtacked.setAttribute('value', xpAtackedValue-30);
    checkXp(cellAtacked, 1);
}
// -- end game -----------
// check = 'win'/'lose'
function endGame(check) {
    unselectAllCells();
    if (check == 'win') {
        var i = 0;
        function loop () {
            setTimeout(function () {
                allCells[i].classList.add('end-game-win');
                allCells[i].classList.remove('cell');
                allCells[i].removeAttribute('onclick');
                btnNewTurn.style.display = "none";
                // btnNewTurn.onclick = function() {}
                if (i < allCells.length) {
                    loop();
                }
            }, 1)
        }
        loop();
        // add message
        setTimeout(function() {
            var winMessage = document.getElementsByClassName('win-message')[0];
            winMessage.style.display = "flex";
        }, 3000);

    } else if (check == 'lose') {
        var i = 0;
        function loop () {
            setTimeout(function () {
                allCells[i].classList.add('end-game-lose');
                allCells[i].classList.remove('cell');
                if (i < allCells.length) {
                    loop();
                }
            }, 1)
        }
        loop();
        // add message
        setTimeout(function() {
            var loseMessage = document.getElementsByClassName('lose-message')[0];
            loseMessage.style.display = "flex";
        }, 3000);
    }
    checkEndGame = 1;
}
// -------------------------------
// -- game-play ------------------
// -------------------------------
for (cell of allCells) {
    cell.onclick = function() {
        if (this.classList.contains('house')) {
            selectHouse(this);
        } else if (this.classList.contains('build-house-ok')) {
            buildHouse(this);
        } else if (this.classList.contains('build-men-ok')) {
            buildMen(this);
        } else if (this.classList.contains('men')) {
            selectMen(this);
        } else if (this.classList.contains('move-men-r1')) {
            movingMen(document.getElementsByClassName('select')[0], this);
        } else if (this.classList.contains('men-atack')) {
            menAtack(document.getElementsByClassName('select')[0], this);
        } else {
            unselectAllCells();
        }
    }
}
// -------------------------------
// -- house game-play ------------
// -------------------------------
// -- select house ---------------
function selectHouse(house) {
    unselectAllCells();
    house.classList.add('select');
    showHouseInfo();
}
// -- button create house --------
infoCreateHouse.onclick = function() {
    if (goldCount >= 50) {
        showInfoExit();
        for (cell of allCells) {
            if (!cell.classList.contains('select')) {
                cell.classList.add('build-house-error-empty');
            }
        }
        var selectCell = document.getElementsByClassName('select')[0];
        var surround = getSurroundCells(selectCell, 3);
        for(i in surround) {
            surround[i].classList.remove('build-house-error-empty');
            if (!isUnit(surround[i])) {
                surround[i].classList.add('build-house-ok')
            } else {
                surround[i].classList.add('build-house-error')
            }
        }
    } else {
        addNoGoldLog();
    }
}
// -- create house -------------
function buildHouse(newHouse) {
    goldCount = goldCount -50;
    document.getElementsByClassName('gold-count')[0].innerHTML = goldCount;
    addGoldChangeLog('-50');
    setNewUnit('house', newHouse);
    unselectAllCells();
}
// -- button create men --------
infoCreateMen.onclick = function() {
    if (goldCount >= 20) {
        showInfoExit();
        for (cell of allCells) {
            if (!cell.classList.contains('select')) {
                cell.classList.add('build-men-error-empty');
            }
        }
        var selectCell = document.getElementsByClassName('select')[0];
        var surround = getSurroundCells(selectCell, 1);
        for(i in surround) {
            surround[i].classList.remove('build-men-error-empty');
            if (!isUnit(surround[i])) {
                surround[i].classList.add('build-men-ok')
            } else {
                surround[i].classList.add('build-men-error')
            }
        }
    } else {
        addNoGoldLog();
    }
}
// -- create men ---------------
function buildMen(newMen) {
    goldCount = goldCount -20;
    document.getElementsByClassName('gold-count')[0].innerHTML = goldCount;
    addGoldChangeLog('-20');
    setNewUnit('men', newMen);
    unselectAllCells();
}
// -- end build ----------------
infoExitBuild.onclick = function() {
    unselectAllCells();
};
// -----------------------------
// -- men game-play ------------
// -----------------------------
// -- select men ---------------
// for user
function selectMen(men) {
    unselectAllCells();
    men.classList.add('select');
    if (men.getElementsByClassName('move-label')[0].classList.contains('green') ||
        men.getElementsByClassName('move-label')[0].classList.contains('yellow') ||
        men.getElementsByClassName('move-label')[0].classList.contains('yellow2')) {
        var surround = getSurroundCells(men, 1);
        for (i in surround) {
            // if men: user
            if (isUserUnit(men)) {
                if (isEnemyUnit(surround[i])) {
                    surround[i].classList.add('men-atack');
                } else if (!isUnit(surround[i])) {
                    surround[i].classList.add('move-men-r1');
                }
            // if men: enemy
            } else if (isEnemyUnit(men)) {
                if (isUserUnit(surround[i])) {
                    surround[i].classList.add('men-atack');
                } else if (!isUnit(surround[i])) {
                    surround[i].classList.add('move-men-r1');
                }
            }
        }
    }
}
// -- move men -----------------
// for user
function movingMen(oldPosition, newPosition) {
    var oldPositionMoveLabel = oldPosition.getElementsByClassName('move-label')[0];
    var oldPositionXpWrapper = oldPosition.getElementsByClassName('xp-wrapper')[0];
    var oldPositionXpLabel = oldPosition.getElementsByClassName('xp-label')[0];
    var oldPositionXpValue = oldPositionXpLabel.getAttribute('value');
    var newPositionMoveLabel = newPosition.getElementsByClassName('move-label')[0];
    var newPositionXpWrapper = newPosition.getElementsByClassName('xp-wrapper')[0];
    var newPositionXpLabel = newPosition.getElementsByClassName('xp-label')[0];
    // add move-label
    if (oldPositionMoveLabel.classList.contains('green')) {
        newPositionMoveLabel.classList.add('yellow');
    } else if (oldPositionMoveLabel.classList.contains('yellow')) {
        newPositionMoveLabel.classList.add('yellow2');
    } else if (oldPositionMoveLabel.classList.contains('yellow2')) {
        newPositionMoveLabel.classList.add('red');
    }
    newPositionMoveLabel.style.display = 'flex';
    // add class
    if (oldPosition.classList.contains('men')) {
        // for user:
        newPosition.classList.add('men');
    } else if (oldPosition.classList.contains('men-enemy')) {
        // for emnmy:
        newPosition.classList.add('men-enemy');
    }
    // add xp-label
    newPositionXpLabel.setAttribute('value', oldPositionXpValue);
    checkXp(newPosition, 0);
    newPositionXpWrapper.style.display = 'flex';

    destroyUnit(oldPosition);
    selectMen(newPosition);
    // check end turn
    if (newPosition.classList.contains('men')) {
        var allGreenLabelLegth = document.getElementsByClassName('green').length;
        var allYellowLabelLegth = document.getElementsByClassName('yellow').length;
        var allYellow2LabelLegth = document.getElementsByClassName('yellow2').length;
        if (allGreenLabelLegth == 0 && allYellowLabelLegth == 0 && allYellow2LabelLegth == 0) {
            // log end turn
            addEndTurnLog();
            // change btnNewTurn
            btnNewTurn.classList.remove('wait');
            btnNewTurn.classList.add('go');
        }
    }
}
// -- men atack ----------------
// for user
function menAtack(selectCell, cellAtacked) {
    var surround = document.getElementsByClassName('move-men-r1');
    var xpCellAtacked = cellAtacked.getElementsByClassName('xp-label')[0];
    var xpAtackedValue = +xpCellAtacked.getAttribute('value');
    var moveLabelSelectCell = selectCell.getElementsByClassName('move-label')[0];

    xpCellAtacked.setAttribute('value', xpAtackedValue-30);
    // slectCell set param
        // move = red;
        if (isUserUnit(selectCell)) {
            moveLabelSelectCell.setAttribute('move', 0);
            moveLabelSelectCell.classList.remove('green');
            moveLabelSelectCell.classList.remove('yellow');
            moveLabelSelectCell.classList.remove('yellow2');
            moveLabelSelectCell.classList.add('red');
        }
        // remove style
        for (i = 0; i < surround.length; i) {
            surround[i].classList.remove('move-men-r1');
        }
    // cellAtacked set param
        // remove style
        cellAtacked.classList.remove('men-atack');
        // xp-label
        checkXp(cellAtacked, 1);

    // check end turn
    if (selectCell.classList.contains('men')) {
        var allGreenLabelLegth = document.getElementsByClassName('green').length;
        var allYellowLabelLegth = document.getElementsByClassName('yellow').length;
        var allYellow2LabelLegth = document.getElementsByClassName('yellow2').length;
        if (allGreenLabelLegth == 0 && allYellowLabelLegth == 0 && allYellow2LabelLegth == 0) {
            // log end turn
            addEndTurnLog();
            // change btnNewTurn
            btnNewTurn.classList.remove('wait');
            btnNewTurn.classList.add('go');
        }
    }
}
// -----------------------------
// -- logs ---------------------
// -----------------------------
function addAtackEnemyLog(xpAtackedValue) {
    delLastLog();
    var atackLog = document.createElement('div');
    atackLog.className = 'log-atack-enemy';
    atackLog.innerHTML = 'Враг был атакован:<b class="empty">..</b>'+
                         '<b class="change-xp">-30xp</b><b class="empty">..</b>'+
                         '<b class="gray">XP:</b><b class="empty">..</b>'+
                         '<b class="xp">'+xpAtackedValue+'</b>';
    logItems.insertBefore(atackLog, logItems.children[2]);
}
function addAtackUserLog(xpAtackedValue) {
    delLastLog();
    var atackLog = document.createElement('div');
    atackLog.className = 'log-atack-user';
    atackLog.innerHTML = 'Ваш юнит атакован:<b class="empty">..</b>'+
                         '<b class="change-xp">-30xp</b><b class="empty">..</b>'+
                         '<b class="gray">XP:</b><b class="empty">..</b>'+
                         '<b class="xp">'+xpAtackedValue+'</b>';
    logItems.insertBefore(atackLog, logItems.children[2]);
}
function addDestroedEnemyLog() {
    delLastLog();
    var destroedLog = document.createElement('div');
    destroedLog.className = 'log-destroed-enemy';
    destroedLog.innerHTML = 'Враг был yничтожен';
    logItems.insertBefore(destroedLog, logItems.children[2]);
}
function addDestroedUserLog() {
    delLastLog();
    var destroedLog = document.createElement('div');
    destroedLog.className = 'log-destroed-user';
    destroedLog.innerHTML = 'Ваш юнит был уничтожен';
    logItems.insertBefore(destroedLog, logItems.children[2]);
}

function addGoldChangeLog(changeGoldInt) {
    delLastLog();
    var goldChangeLog = document.createElement('div');
    goldChangeLog.className = 'log-gold-change';
    goldChangeLog.innerHTML = 'GOLD:<b class="empty">..</b><b class="change-gold-int">'+changeGoldInt+'</b>';
    logItems.insertBefore(goldChangeLog, logItems.children[2]);
}
function addNoGoldLog() {
    delLastLog();
    var noGoldLog = document.createElement('div');
    noGoldLog.className = 'log-nogold';
    noGoldLog.innerHTML = 'У вас недостаточно золота';
    logItems.insertBefore(noGoldLog, logItems.children[2]);
}
function addEndTurnLog() {
    delLastLog();
    var endTurnLog = document.createElement('div');
    endTurnLog.className = 'log-endturn';
    endTurnLog.innerHTML = 'Вы походили всеми юнитами';
    logItems.insertBefore(endTurnLog, logItems.children[2]);
}
function addNewTurnLog(houseQty, goldInt) {
    delLastLog();
    var newTurnLog = document.createElement('div');
    newTurnLog.className = 'log-newturn';
    newTurnLog.innerHTML = 'Новый ход.' +
        '<b class="empty">..</b>'+
        '<b class="gray">Houses:<b class="empty">..</b><b class="houses-int">'+
        +(houseQty)+'</b><b class="empty">..</b>'+
        '<b class="gray">GOLD:<b class="empty">..</b><b class="change-gold-int">+'+
        +(goldInt)+'</b>';
    logItems.insertBefore(newTurnLog, logItems.children[2]);
}
function delLastLog() {
    var lastLog = logItems.lastElementChild;
    lastLog.parentNode.removeChild(lastLog);
}
// -------------------------------
// -- helpers --------------------
// -------------------------------
// -- check XP -------------------
// for user and enemy
function checkXp(cell, needLog) {
    if (needLog === undefined) {
        needLog = 1;
    }
    var xpLabel = cell.getElementsByClassName('xp-label')[0];
    var xpValue = xpLabel.getAttribute('value');
    if (xpValue > 0) {
        // add log atacked
        if (needLog == 1) {
            if (isEnemyUnit(cell)) {
                addAtackEnemyLog(xpValue);
            } else if (isUserUnit(cell)) {
                addAtackUserLog(xpValue);
            }
        }

        if (isHouse(cell)) {
            var xpWidth = Math.floor(xpValue/15);
            xpLabel.style.width = xpWidth+'px';
            if (xpValue < 200) {
                xpLabel.style.backgroundColor = '#990';
            }
            if (xpValue < 100) {
                xpLabel.style.backgroundColor = '#900';
            }
        } else if (isMen(cell)) {
            var xpWidth = Math.floor(xpValue/5);
            xpLabel.style.width = xpWidth+'px';
            if (xpValue < 60) {
                xpLabel.style.backgroundColor = '#990';
            }
            if (xpValue < 30) {
                xpLabel.style.backgroundColor = '#900';
            }
        }
    } else if (xpValue <= 0) {
        xpLabel.style.backgroundColor = '#090';
        xpLabel.setAttribute('value', 0);
        xpLabel.style.display = 'none';

        if (isEnemyUnit(cell)) {
            // add log destroed
            addDestroedEnemyLog();
            destroyUnit(cell);
            // ckeck end game
            var allHousesEnemyLength = document.getElementsByClassName('house-enemy').length;
            var allMensEnemyLength = document.getElementsByClassName('men-enemy').length;
            if (allMensEnemyLength == 0 && allHousesEnemyLength == 0) {
                // alert('you are win!');
                endGame('win');
            }
        } else if (isUserUnit(cell)) {
            // add log destroed
            addDestroedUserLog();
            destroyUnit(cell);
            // ckeck end game
            var allHousesUserLength = document.getElementsByClassName('house').length;
            var allMensUserLength = document.getElementsByClassName('men').length;
            if (allMensUserLength == 0 && allHousesUserLength == 0) {
                // alert('you are lose!');
                endGame('lose');
            }
        }
    }
}
// -- set new unit ----------
// for user and enemy
// typeUnit = house/men/house-enemy/men-enemy
function setNewUnit(typeUnit, cell) {
    var cellMoveLabel = cell.getElementsByClassName('move-label')[0];
    var cellXpWrapper = cell.getElementsByClassName('xp-wrapper')[0];
    var cellXpLabel = cell.getElementsByClassName('xp-label')[0];

    if (typeUnit == 'house') {
        // add xp-label
        cellXpLabel.setAttribute('value', 200);
        cellXpWrapper.style.display = 'flex';
        // add class
        cell.classList.add('house');
    } else if (typeUnit == 'men') {
        // add move-label
        cellMoveLabel.classList.add('red');
        cellMoveLabel.style.display = 'flex';
        // add xp-label
        cellXpLabel.setAttribute('value', 100);
        cellXpWrapper.style.display = 'flex';
        // add class
        cell.classList.add('men');
    } else if (typeUnit == 'house-enemy') {
        // add xp-label
        cellXpLabel.setAttribute('value', 200);
        cellXpWrapper.style.display = 'flex';
        // add class
        cell.classList.add('house-enemy');
    } else if (typeUnit == 'men-enemy') {
        // add xp-label
        cellXpLabel.setAttribute('value', 100);
        cellXpWrapper.style.display = 'flex';
        // add class
        cell.classList.add('men-enemy');
    }
}
// -- destroy unit ----------
// for user and enemy
function destroyUnit(cell) {
    var cellMoveLabel = cell.getElementsByClassName('move-label')[0];
    var cellXpWrapper = cell.getElementsByClassName('xp-wrapper')[0];
    var cellXpLabel = cell.getElementsByClassName('xp-label')[0];
    // remove class
    cell.classList.remove('house');
    cell.classList.remove('men');
    cell.classList.remove('house-enemy');
    cell.classList.remove('men-enemy');
    // remove xp-label
    cellXpLabel.setAttribute('value', 0);
    cellXpLabel.removeAttribute('style');
    cellXpWrapper.removeAttribute('style');
    // remove move-label
    cellMoveLabel.classList.remove('green');
    cellMoveLabel.classList.remove('yellow');
    cellMoveLabel.classList.remove('yellow2');
    cellMoveLabel.classList.remove('red');
    cellMoveLabel.removeAttribute('style');
}
// -- random integer ---------
function randomInteger(min, max) {
    var rand = min + Math.random() * (max - min)
    rand = Math.round(rand);
    return rand;
}
// -- get surround cells -----
function getSurroundCells(selectedCell, radius) {
    var positionSelectedCell = {
        'row': +selectedCell.getAttribute('row'),
        'column': +selectedCell.getAttribute('column')
    };
    var surroundCells = {};
    var i = 1;
    for (cell of allCells) {
        for (row = -radius; row <= radius; row++) {
            if (cell.getAttribute('row') == positionSelectedCell.row +row) {
                for (column = -radius; column <= radius; column++) {
                    if (cell.getAttribute('column') == positionSelectedCell.column +column) {
                        if (cell.getAttribute('column') == selectedCell.getAttribute('column') &&
                            cell.getAttribute('row') == selectedCell.getAttribute('row')) {
                            surroundCells['center'] = cell;
                        } else {
                            surroundCells[''+i+''] = cell;
                            i++;
                        }
                    }
                }
            }
        }
    }
    delete surroundCells['center'];
    return surroundCells;
}
// -- unselect all cells -----
function unselectAllCells() {
    for (cell of allCells) {
        cell.classList.remove('select');
        cell.classList.remove('build-house-ok');
        cell.classList.remove('build-house-error');
        cell.classList.remove('build-house-error-empty');
        cell.classList.remove('build-men-ok');
        cell.classList.remove('build-men-error');
        cell.classList.remove('build-men-error-empty');
        cell.classList.remove('move-men-r1');
        cell.classList.remove('move-men-r2');
        cell.classList.remove('move-men-r3');
        cell.classList.remove('men-atack');
    }
    hiddenAllInfo();
}
// -- info -------------------
function showHouseInfo() {
    houseInfo.style.display = 'flex';
    infoCreateHouse.style.display = 'flex';
    infoCreateMen.style.display = 'flex';
    infoMessage.style.display = 'none';
    infoExitBuild.style.display = 'none';
}
function showInfoExit() {
    houseInfo.style.display = 'flex';
    infoCreateHouse.style.display = 'none';
    infoCreateMen.style.display = 'none';
    infoMessage.style.display = 'flex';
    infoExitBuild.style.display = 'flex';
}
function hiddenAllInfo() {
    houseInfo.style.display = 'none';
    infoCreateHouse.style.display = 'none';
    infoCreateMen.style.display = 'none';
    infoMessage.style.display = 'none';
    infoExitBuild.style.display = 'none';
}
// -- who is unit -------------
function isUnit(cell) {
    if (cell.classList.contains('men') ||
        cell.classList.contains('house') ||
        cell.classList.contains('men-enemy') ||
        cell.classList.contains('house-enemy')) {
        return true;
    } else {
        return false;
    }
}
function isUserUnit(cell) {
    if (cell.classList.contains('men') ||
        cell.classList.contains('house')) {
        return true;
    } else {
        return false;
    }
}
function isEnemyUnit(cell) {
    if (cell.classList.contains('men-enemy') ||
        cell.classList.contains('house-enemy')) {
        return true;
    } else {
        return false;
    }
}
function isMen(cell) {
    if (cell.classList.contains('men') ||
        cell.classList.contains('men-enemy')) {
        return true;
    } else {
        return false;
    }
}
function isHouse(cell) {
    if (cell.classList.contains('house') ||
        cell.classList.contains('house-enemy')) {
        return true;
    } else {
        return false;
    }
}
}
