<?php
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});
session_start();
$render = new Learn();
$render->allVocabulary = null;
$render->allVocabularyIds = null;
$render->userVocabulary = null;
$render->userVocabularyIds = null;

$pageTitle = 'Учить английскую лексику';
?>
<head>
    <title><?php echo $pageTitle ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <?php echo $render->userThemeRender ?>
</head>
<body>
    <?php include('head.php'); ?>
    <div class="wrapper">
        <div class="question">
            <div class="history">
                <?php if ($render->userTodayCount >= $render->todayNeedCheckYellow): ?>
                    <div class="today-red">
                <?php elseif ($render->userTodayCount >= $render->todayNeedCheckGreen && $render->userTodayCount < $render->todayNeedCheckYellow): ?>
                    <div class="today-yellow">
                <?php elseif ($render->userTodayCount < $render->todayNeedCheckGreen): ?>
                    <div class="today-green">
                <?php endif ?>
                    <?php if ($render->userTodayCount != 0): ?>
                        <div align="center">
                            Сегодня осталось ответить на <span class="today-yellow2"><?php echo $render->userTodayCount ?></span>
                            <?php if ($render->userTodayCount > 4): ?>
                                вопросов
                            <?php elseif ($render->userTodayCount > 1): ?>
                                вопросa
                            <?php elseif ($render->userTodayCount = 1): ?>
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
                        <?php echo $render->currentQuestionEng ?>
                    </div>
                    <div class="question-type-<?php echo $render->currentQuestionTypeClass ?>">
                        <?php echo $render->currentQuestionType ?>
                    </div>
                    <div class="question-statistic">
                        <div class="question-statistic-all">
                            A:<?php echo $render->currentQuestionSummaryCount ?>
                        </div>
                        <div class="question-statistic-success">
                            S:<?php echo $render->currentQuestionSuccessCount ?>
                        </div>
                        <div class="question-statistic-errors">
                            E:<?php echo $render->currentQuestionErrorsCount ?>
                        </div>
                    </div>
                </div>
                <div class="question-content-mini">
                    <div class="question-type-mini-<?php echo $render->currentQuestionTypeClass ?>">
                        <?php echo $render->currentQuestionType ?>
                    </div>
                    <div class="question-statistic-mini">
                        <div class="question-statistic-all">
                            A:<?php echo $render->currentQuestionSummaryCount ?>
                        </div>
                        <div class="question-statistic-success">
                            S:<?php echo $render->currentQuestionSuccessCount ?>
                        </div>
                        <div class="question-statistic-errors">
                            E:<?php echo $render->currentQuestionErrorsCount ?>
                        </div>
                    </div>
                </div>
            </div>
            <form class="form" method="post" action="learn.php">
                <?php $i = 0 ?>
                <?php $j = 1 ?>
                <div class="row-variant">
                    <?php foreach ($render->currentQuestionVariants as $variant): ?>
                        <input name="question-id" value="<?php echo $render->currentQuestionId ?>" type="hidden"/>
                        <?php if ($render->currentQuestionType == 'Хорошо знаю'): ?>
                            <input name="lq_ss" value="1" type="hidden"/>
                        <?php endif ?>
                        <button class="variant" id="<?php echo $i ?>" name="answer"
                            value="<?php echo $render->currentQuestionVariants[$i] ?>" 
                            type="submit"><?php echo $render->currentQuestionVariants[$i] ?></button>
                        <?php if ($j % 2 == 0): ?>
                            </div>
                            <div class="row-variant">
                        <?php endif; $i++; $j++; ?>
                    <?php endforeach ?>
                </div>
                <?php $i = 0 ?>
            </form>
            <div class="statistic-wrapper">
                <div class="statistic">
                    <div class="statistic-row">
                        <div class="statistic-row-item">Хорошо знаю: <span class="statistic-green"><?php echo $render->userStableSuccessCount ?></span></div>
                        <div class="statistic-row-item">Знаю: <span class="statistic-yellow"><?php echo $render->userSuccessCount ?></span></div>
                        <div class="statistic-row-item">Плохо знаю: <span class="statistic-red"><?php echo $render->userErrorsCount ?></span></div>
                        <div class="statistic-row-item">Не знаю: <span class="statistic-red"><?php echo $render->userStableErrorsCount ?></span></div>
                    </div>
                    <div class="statistic-row">
                        <div class="statistic-row-item">Всего: <span><?php echo $render->allVocabularyCount ?></span></div>
                        <div class="statistic-row-item">Известные: <span class="statistic-blue"><?php echo $render->userVocabularyCount ?></span></div>
                        <div class="statistic-row-item">Неизвестные: <span class="statistic-yellow"><?php echo $render->allVocabularyCount - $render->userVocabularyCount ?></span></div>
                    </div>
                </div>
                <form method="post" action="learn.php">
                    <div class="row-variant">
                        <?php if ($render->typeVocabulary == '850'): ?>
                            <input name="change-vocabulary" value="5000" type="hidden"/>
                            <button class="change-vocabulary" value="Учить 5000 слов" type="submit">Учить 5000 слов</button>
                        <?php elseif ($render->typeVocabulary == '5000'): ?>
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
// echo '<pre>';
//     print_r($render);
// echo '</pre>';
