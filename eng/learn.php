<?php
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
session_start();
$render = new Learn();
$todayCount      = $render->todayCount;
$typeVocabulary  = $render->typeVocabulary;
$progressCount   = $render->progressCount;
$vocabularyCount = $render->vocabularyCount;
$countSS         = $render->countSS;
$countS          = $render->countS;
$countE          = $render->countE;
$countEE         = $render->countEE;

$pageTitle = 'Учить английскую лексику';
?>
<?php include('header.php'); ?>
<body>
    <?php include('head.php'); ?>
    <div class="wrapper">
        <div class="question">
            <div class="history">
                <?php if ($todayCount >= $render->todayNeedCheckYellow): ?>
                    <div class="today-red">
                <?php elseif ($todayCount >= $render->todayNeedCheckGreen
                    && $todayCount < $render->todayNeedCheckYellow): ?>
                    <div class="today-yellow">
                <?php elseif ($todayCount < $render->todayNeedCheckGreen): ?>
                    <div class="today-green">
                <?php endif ?>
                    <?php if ($todayCount != 0): ?>
                        <div align="center">
                            Сегодня осталось ответить на <span class="today-yellow2"><?php echo $todayCount ?></span>
                            <?php if ($todayCount > 4): ?>
                                вопросов
                            <?php elseif ($todayCount > 1): ?>
                                вопросa
                            <?php elseif ($todayCount == 1): ?>
                                вопрос
                            <?php endif ?>
                        </div>
                    <?php else: ?>
                        <div class="today-green2">На сегодня достаточно</div>
                    <?php endif ?>
                </div>
                <?php if (isset($_POST['answer'])): ?>
                    <?php if ($render->questionResult): ?>
                        <div class="result-ok">
                            <div class="result-ok-blue"><?php echo $render->lastQuestionEng ?></div>
                            <div class="result-ok-green"><?php echo $render->lastQuestionRu ?></div>
                        </div>
                    <?php else: ?>
                        <div class="result-false">
                            <div class="result-false-item"><?php echo $render->lastQuestionEng ?></div>
                            <div class="result-false-row">
                                <div class="result-false-row-red"><?php echo $render->lastQuestionUserAnswer ?></div>
                                <div class="result-false-row-green"><?php echo $render->lastQuestionRu ?></div>
                            </div>
                        </div>
                        <?php if ($render->lastSSQFailed): ?>
                            <div class="today-red">Прогресс сброшен (S:1|E:0)</div>
                        <?php endif ?>
                    <?php endif ?>
                <?php endif ?>
            </div>
            <div class="question-head">
                <div class="question-content">
                    <div class="question-title">
                        <?php echo $render->questionEng ?>
                    </div>
                    <div class="question-type-<?php echo $render->questionTypeClass ?>">
                        <?php echo $render->questionType ?>
                    </div>
                    <div class="question-statistic">
                        <div class="question-statistic-all">
                            A:<?php echo $render->questionA ?>
                        </div>
                        <div class="question-statistic-success">
                            S:<?php echo $render->questionS ?>
                        </div>
                        <div class="question-statistic-errors">
                            E:<?php echo $render->questionE ?>
                        </div>
                    </div>
                </div>
                <div class="question-content-mini">
                    <div class="question-type-mini-<?php echo $render->questionTypeClass ?>">
                        <?php echo $render->questionType ?>
                    </div>
                    <div class="question-statistic-mini">
                        <div class="question-statistic-all">
                            A:<?php echo $render->questionA ?>
                        </div>
                        <div class="question-statistic-success">
                            S:<?php echo $render->questionS ?>
                        </div>
                        <div class="question-statistic-errors">
                            E:<?php echo $render->questionE ?>
                        </div>
                    </div>
                </div>
            </div>
            <form class="form" method="post" action="learn.php">
                <input name="question-id" value="<?php echo $render->questionId ?>" type="hidden"/>
                <input name="lq_status" value="<?php $render->questionType ?>" type="hidden"/>
                <?php $i = 0; $j = 1 ?>
                <div class="row-variant">
                    <?php foreach ($render->variants as $variant): ?>
                        <button class="variant" name="answer" value="<?php echo $render->variants[$i] ?>"
                            type="submit"><?php echo $render->variants[$i] ?></button>
                        <?php if ($j % 2 == 0): ?>
                            </div>
                            <div class="row-variant">
                        <?php endif; $i++; $j++; ?>
                    <?php endforeach ?>
                </div>
                <?php $i = null; $j = null ?>
            </form>
            <div class="statistic-wrapper">
                <div class="statistic">
                    <div class="statistic-row">
                        <div class="statistic-row-item">
                            Хорошо знаю: <span class="statistic-green"><?php echo $countSS ?></span>
                        </div>
                        <div class="statistic-row-item">
                            Знаю: <span class="statistic-yellow"><?php echo $countS ?></span>
                        </div>
                        <div class="statistic-row-item">
                            Плохо знаю: <span class="statistic-red"><?php echo $countE ?></span>
                        </div>
                        <div class="statistic-row-item">
                            Не знаю: <span class="statistic-red"><?php echo $countEE ?></span>
                        </div>
                    </div>
                    <div class="statistic-row">
                        <div class="statistic-row-item">
                            Всего: <span><?php echo $vocabularyCount ?></span>
                        </div>
                        <div class="statistic-row-item">
                            Известные: <span class="statistic-blue"><?php echo $progressCount ?></span>
                        </div>
                        <div class="statistic-row-item">
                            Неизвестные: <span class="statistic-yellow"><?php
                                echo $vocabularyCount - $progressCount
                            ?></span>
                        </div>
                    </div>
                </div>
                <form method="post" action="learn.php">
                    <div class="row-variant">
                        <?php if ($typeVocabulary == '850'): ?>
                            <input name="change-vocabulary" value="5000" type="hidden"/>
                            <button class="change-vocabulary" value="Учить 5000 слов" type="submit">Учить 5000 слов</button>
                        <?php elseif ($typeVocabulary == '5000'): ?>
                            <input name="change-vocabulary" value="850" type="hidden"/>
                            <button class="change-vocabulary" value="Учить 850 слов" type="submit">Учить 850 слов</button>
                        <?php endif ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<?php
echo '<pre>';
    var_dump($render);
echo '</pre>';
