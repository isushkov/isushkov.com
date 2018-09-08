<?php
class Learn extends App
{
    public $countFalseVariants = 3;
    public $todayNeedCheck = 50;
    public $newWordsCount = 20;
    public $maxSuccessCount = 10;
    public $maxErrorsCount = 5;
    public $maxStableErrorsCount = 5;
    public $lastSSQFailed = false;

    function __construct() 
    {
        $this->getTheme();

        $this->changeVocabulary();
        $this->getAllVocabularyIds();
        $this->getUserVocabulary();

        $this->getUserData();
        $this->getUserStatistic();
        $this->progressingPostData();
        $this->generateQuestion();

        $this->todayNeedCheckYellow = (int)($this->todayNeedCheck / 3) * 2;
        $this->todayNeedCheckGreen = (int)($this->todayNeedCheck / 3);
    }

    public function getUserData()
    {
        $dbh = $this->getConnection();
        $sth = $dbh->prepare("select * from users where id = $this->userId");
        $sth->execute();
        $userData = $sth->fetchAll(PDO::FETCH_ASSOC);

        $this->userLogin = $userData[0]['login'];
        // $this->userLastVisit = substr($userData[0]['last_visit'], 0, 10); 
        $this->userTodayCount = (int)$userData[0]['today_count'];
        $this->typeVocabulary = $userData[0]['type_vocabulary'];
        $this->userTheme = $userData[0]['theme'];

        if ($this->typeVocabulary == '850') {
            $this->vocabularyTable = 'vocabulary850';
            $this->progressTable = 'progress850';
        } else if ($this->typeVocabulary == '5000') {
            $this->vocabularyTable = 'vocabulary';
            $this->progressTable = 'progress';
        } else {
            echo 'Uncorrect type vocabulary'; die;
        }
        return $this;
    }


    public function getAllVocabularyIds()
    {
        $dbh = $this->getConnection();
        $sth = $dbh->prepare("select id from $this->vocabularyTable");
        $sth->execute();

        $this->allVocabularyIds = $sth->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllVocabularyCount()
    {
        $dbh = $this->getConnection();
        $sth = $dbh->prepare("select count(*) from $this->vocabularyTable");
        $sth->execute();

        $this->allVocabularyCount = (int)$sth->fetch(PDO::FETCH_COLUMN);
    }
    
    public function getUserVocabulary()
    {
        $dbh = $this->getConnection();
        $sth = $dbh->prepare("select * from $this->progressTable where user_id = $this->userId");
        $sth->execute();

        $this->userVocabulary = $sth->fetchAll(PDO::FETCH_ASSOC);
        $this->userVocabularyCount = count($this->userVocabulary);

        $userVocabularyIds = array();
        $i = 0;
        foreach ($this->userVocabulary as $vocabulary) {
            $userVocabularyIds[$i] = $vocabulary['id'];  
            $i++;
        }
        unset($i);
        $this->userVocabularyIds = $userVocabularyIds;

        return $this;
    }

    public function getVariants($newQuestion)
    {
        $questionVariants[0] = $newQuestion[0]['ru'];
        // get false variants
        $allVocabulary = $this->allVocabulary;
        $falseVariantsIds = array_rand($allVocabulary, $this->countFalseVariants);
        $i = 1;
        foreach ($falseVariantsIds as $falseVariantId) {
            // if falseVariant = questionRu
            while ($falseVariantId == (int)$newQuestion[0]['id']) {
                $falseVariantId = array_rand($allVocabulary, 1);
            }
            $questionVariants[$i] = $allVocabulary[$falseVariantId]['ru'];
            $i++;
        }
        unset($i);
        shuffle($questionVariants);
        $this->currentQuestionVariants = $questionVariants;

        return $this;
    }

    public function changeVocabulary()
    {
        if (isset($_POST['change-vocabulary'])) {
            if ($_POST['change-vocabulary'] == '850' ||
                $_POST['change-vocabulary'] == '5000') {

                $dbh = $this->getConnection();
                $sth = $dbh->prepare("UPDATE users SET type_vocabulary = :type_vocabulary WHERE id = :id");
                $sth->execute(array(
                    'id' => $this->userId,
                    'type_vocabulary' => $_POST['change-vocabulary']
                ));
            } else {
                echo 'uncorrect POST data'; die;
            }
        }
    }

    public function progressingPostData()
    {
        if (isset($_POST['answer']) && isset($_POST['question-id'])) {
            $lastQuestionId = $_POST['question-id'];
            // get last Question data
            $dbh = $this->getConnection();
            $sth = $dbh->prepare("select * from $this->vocabularyTable where id = $lastQuestionId");
            $sth->execute();
            $lastQuestion = $sth->fetchAll(PDO::FETCH_ASSOC);
            $this->lastQuestionId = $lastQuestion[0]['id'];
            $this->lastQuestionEng = $lastQuestion[0]['eng'];
            $this->lastQuestionRu = $lastQuestion[0]['ru'];
            $this->lastQuestionUserAnswer = $_POST['answer'];

            //SSQ
            if (isset($_POST['lq_ss']) && $_POST['lq_ss'] === 1) {
                $lastQuestionStatus = 'SSQ';
            }
            // try answer
            if ($_POST['answer'] == $this->lastQuestionRu) {
                $this->questionResult = true;
                // old question
                if ($this->currentQuestionSummaryCount > 0) { 
                    $this->updateUserVocabulary(true, true);
                // new question
                } else {
                    $this->updateUserVocabulary(true, false);
                }
            // false answer
            } else {
                $this->questionResult = false;
                // old question
                if ($this->currentQuestionSummaryCount > 0) { 
                    if ($lastQuestionStatus = 'SSQ') {
                        $this->resetSSProgress();
                    } else {
                        $this->updateUserVocabulary(false, true);
                    }
                // new question
                } else {
                    $this->updateUserVocabulary(false, false);
                }
            }
        }
    }

    public function updateUserVocabulary($success, $oldQuestion)
    {
        $dbh = $this->getConnection();
        if ($oldQuestion) {
            if ($success) {
                // Верно. old question
                $newSummary = (int)$this->currentQuestionSummaryCount + 1;
                $newSuccess = (int)$this->currentQuestionSuccessCount + 1;
                $sth = $dbh->prepare("update $this->progressTable set summary = $newSummary, success = $newSuccess where user_id = $this->userId and vocabulary_id = $this->currentQuestionId");
            } else {
                // Неверно. old question
                $newSummary = (int)$this->currentQuestionSummaryCount + 1;
                $newErrors = (int)$this->currentQuestionErrorsCount + 1;
                $sth = $dbh->prepare("update $this->progressTable set summary = $newSummary, errors = $newErrors where user_id = $this->userId and vocabulary_id = $this->currentQuestionId");
            }
            $sth->execute();
        } else {
            if ($success) {
                $sth = $dbh->prepare("insert into $this->progressTable (user_id, vocabulary_id, summary, success, errors) values ($this->userId, $this->currentQuestionId, 1, 1, 0)");
            } else {
                $sth = $dbh->prepare("insert into $this->progressTable (user_id, vocabulary_id, summary, success, errors) values ($this->userId, $this->currentQuestionId, 1, 0, 1)");
            }
            $sth->execute();
        }
        $this->getUserVocabulary();
        // today_count--
        if ($this->userTodayCount > 0) {
            $this->userTodayCount--;
            $sth = $dbh->prepare("UPDATE `users` SET today_count = :todaycount WHERE `id` = :id");
            $sth->execute(array(
                'id' => $this->userId,
                'todaycount' => $this->userTodayCount
            ));
        }
    }

    public function resetSSProgress()
    {
        $this->lastSSQFailed = true;
        $dbh = $this->getConnection();
        // Неверно. old question
        $newSummary = 1;
        $newSuccess = 1;
        $newErrors = 0;
        $sth = $dbh->prepare("update $this->progressTable set summary = $newSummary, success = $newSuccess, errors = $newErrors where user_id = $this->userId and vocabulary_id = $this->currentQuestionId");
        $sth->execute();
        $this->getUserVocabulary();
        // today_count--
        if ($this->userTodayCount > 0) {
            $this->userTodayCount--;
            $sth = $dbh->prepare("UPDATE `users` SET today_count = :todaycount WHERE `id` = :id");
            $sth->execute(array(
                'id' => $this->userId,
                'todaycount' => $this->userTodayCount
            ));
        }
    }

    public function getUserStatistic()
    {
        $dbh = $this->getConnection();
        // SS: success - errors > 5
        $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where user_id = $this->userId and success - errors > 3");
        $sth->execute();
        $this->userStableSuccessCount = count($sth->fetchAll(PDO::FETCH_COLUMN));
        // S: 0 < success - errors <= 5 
        $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where user_id = $this->userId and success - errors > 0 and success - errors <= 3");
        $sth->execute();
        $this->userSuccessCount = count($sth->fetchAll(PDO::FETCH_COLUMN));
        // E: 0 <= errors - success <= 5 
        $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where user_id = $this->userId and errors - success >= 0 and errors - success <= 3");
        $sth->execute();
        $this->userErrorsCount = count($sth->fetchAll(PDO::FETCH_COLUMN));
        // EE: errors - success > 5
        $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where user_id = $this->userId and errors - success > 3");
        $sth->execute();
        $this->userStableErrorsCount = count($sth->fetchAll(PDO::FETCH_COLUMN));
    }

    public function generateQuestion()
    {
        // for padavan
        if ($this->userVocabularyCount < $this->allVocabularyCount) {
            // get started quantity
            if ($this->userVocabularyCount < $this->newWordsCount) {
                $this->getQuestion('new');
            // if first today visit
            } else if ($this->userTodayCount >= 140) {
                $this->getQuestion('new');
            // if last today visit
            } else if ($this->userTodayCount <= 10 && $this->userTodayCount > 0 &&
                $this->userStableSuccessCount >= 50) {
                    $this->getQuestion('stableSuccess');
            // processing errors
            } else if ($this->userStableErrorsCount > $this->maxStableErrorsCount) {
                $this->getQuestion('stableErrors');
            } else if ($this->userErrorsCount > $this->maxErrorsCount) {
                $this->getQuestion('errors');
            } else if ($this->userSuccessCount > $this->maxSuccessCount) {
                $this->getQuestion('success');
            // next
            } else {
                $this->getQuestion('new');
            }
        // for expert
        } else {
            // if first today visit
            if ($this->userTodayCount >= 120 && $this->userTodayCount <= 140 &&
                $this->userStableSuccessCount > 0) {
                    $this->getQuestion('stableSuccess');
            // processing errors
            } else if ($this->userStableErrorsCount > 0) {
                $this->getQuestion('stableErrors');
            } else if ($this->userErrorsCount > 0) {
                $this->getQuestion('errors');
            } else if ($this->userSuccessCount > 0) {
                $this->getQuestion('success');
            // WIN
            } else {
                $this->getQuestion('stableSuccess');
            }
        }
    }

    // @param $status = (str) new || errors || stableErrors || success || stableSuccess
    public function getQuestion($status)
    {
        $dbh = $this->getConnection();
        if ($status === 'new') {
            $this->currentQuestionType = 'Hовое слово';
            $this->currentQuestionTypeClass = 'dark';
            $this->currentQuestionSummaryCount = 0;
            $this->currentQuestionSuccessCount = 0;
            $this->currentQuestionErrorsCount = 0;
            // get new question
            $newVocabularyIds = array_diff($this->allVocabularyIds, $this->userVocabularyIds);
            shuffle($newVocabularyIds);
            $questionId = array_shift($newVocabularyIds);
            // no repit Q
            if (isset($this->lastQuestionId)) {
                while ($this->lastQuestionId == $questionId) {
                    $questionId = array_shift($newVocabularyIds);
                }
            }
            $dbh = $this->getConnection();
            $sth = $dbh->prepare("select * from $this->vocabularyTable where id = $questionId");
        } else {
            if ($status === 'stableErrors') {
                $this->currentQuestionType = 'He знаю';
                $this->currentQuestionTypeClass = 'red2';
                // EE: errors - success > 5
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where user_id = $this->userId and errors - success > 3");
            }
            if ($status === 'errors') {
                $this->currentQuestionType = 'Плохо знаю';
                $this->currentQuestionTypeClass = 'red';
                // E: 0 <= errors - success <= 5 
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where user_id = $this->userId and errors - success >= 0 and errors - success <= 3");
            }
            if ($status === 'success') {
                $this->currentQuestionType = 'Знаю';
                $this->currentQuestionTypeClass = 'yellow';
                // S: 0 < success - errors <= 5 
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where user_id = $this->userId and success - errors > 0 and success - errors <= 3");
            }
            if ($status === 'stableSuccess') {
                $this->currentQuestionType = 'Хорошо знаю';
                $this->currentQuestionTypeClass = 'green';
                // SS: success - errors > 5
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where user_id = $this->userId and success - errors > 3");
            }
            $sth->execute();
            $questionsIds = $sth->fetchAll(PDO::FETCH_COLUMN);

            // get question
            shuffle($questionsIds);
            $questionId = array_shift($questionsIds);
            // no repit Q
            if (isset($this->lastQuestionId)) {
                while ($this->lastQuestionId == $questionId) {
                    $questionId = array_shift($questionsIds);
                }
            }
            $sth = $dbh->prepare("select * from $this->vocabularyTable where id = $questionId");
        }
        $sth->execute();
        $question = $sth->fetchAll(PDO::FETCH_ASSOC);
        // set question
        $this->currentQuestionId = $question[0]['id'];
        $this->currentQuestionEng = $question[0]['eng'];
        $this->currentQuestionRu = $question[0]['ru'];
        // get newQuestion STATISTIC
        if ($status !== 'new') {
            $sth = $dbh->prepare("select * from $this->progressTable where user_id = $this->userId and vocabulary_id = $this->currentQuestionId");
            $sth->execute();
            $questionStatistic = $sth->fetchAll(PDO::FETCH_ASSOC);
            $this->currentQuestionSummaryCount = $questionStatistic[0]['summary'];
            $this->currentQuestionSuccessCount = $questionStatistic[0]['success'];
            $this->currentQuestionErrorsCount = $questionStatistic[0]['errors'];
        }
        // get variants
        $this->getVariants($question);

        return $this;
    }
}
