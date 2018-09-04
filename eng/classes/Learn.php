<?php
class Learn extends App
{
    public $countFalseVariants = 9;
    public $todayNeedCheck = 150;
    public $newWordsCount = 50;
    public $maxStableSuccessCount = 5000;
    public $maxSuccessCount = 100;
    public $maxErrorsCount = 50;
    public $maxStableErrorsCount = 30;

    public $typeVocabulary = null;
    public $vocabularyTable = null;
    public $progressTable = null;

    public $todayDate = null;
    public $todayNeedCheckYellow = null;
    public $todayNeedCheckGreen = null;

    public $userLogin = null;
    public $userLastVisit = null;
    public $userTodayCount = null;

    public $allVocabulary = null;
    public $allVocabularyIds = null;
    public $allVocabularyCount = null;

    public $userVocabulary = null;
    public $userVocabularyIds = null;
    public $userVocabularyCount = null;

    public $userReadyWords = 0;
    public $userStableSuccesSuccessCount = 0;
    public $userStableSuccessCount = null;
    public $userSuccessCount = null;
    public $userErrorsCount = null;
    public $userStableErrorsCount = null;

    public $currentQuestionId = null;
    public $currentQuestionEng = null;
    public $currentQuestionRu = null;
    public $currentQuestionType = null;
    public $currentQuestionTypeClass = null;
    public $currentQuestionVariants = null;
    public $currentQuestionSummaryCount = null;
    public $currentQuestionSuccessCount = null;
    public $currentQuestionErrorsCount = null;

    public $questionResult = null;
    public $lastQuestionEng = null;
    public $lastQuestionRu = null;
    public $lastQuestionUserAnswer = null;

    function __construct() 
    {
        $this->checkSession();

        $this->todayDate = date("Y-m-d");
        $this->todayNeedCheckYellow = (int)($this->todayNeedCheck / 3) * 2;
        $this->todayNeedCheckGreen = (int)($this->todayNeedCheck / 3);
    }

    public function updateUserTodayCount()
    {
        var_dump($this->userLastVisit);
        echo '<br/>';
        var_dump($this->todayDate);
        // update last_visit and today_count
        if ($this->userLastVisit !== $this->todayDate) {
            // SET last_visit and today_count
            $dbh = $this->getConnection();
            $sth = $dbh->prepare("UPDATE users SET last_visit = :last_visit, today_count = :today_count WHERE id = :id");
            $sth->execute(array(
                'last_visit' => date('Y-m-d G:i:s'),
                'id' => $this->userId,
                'today_count' => $this->todayNeedCheck
            ));
            // get new userdata
            $this->getUserData();
        }
        // check @int
        if (!is_null($this->userTodayCount) || !is_int($this->userTodayCount)) {
            $this->userTodayCount = (int)$this->userTodayCount;
        }
        if (!is_int($this->userTodayCount) || $this->userTodayCount < 0 || $this->userTodayCount > $this->todayNeedCheck) {
            // set new today_count
            $dbh = $this->getConnection();
            $sth = $dbh->prepare("UPDATE users SET today_count = :today_count WHERE id = :id");
            $sth->execute(array(
                'today_count' => $this->todayNeedCheck,
                'id' => $this->userId
            ));
            // get new userdata
            $this->getUserData();
        }
        return $this;
    }

    public function getAllVocabulary()
    {
        $dbh = $this->getConnection();
        $sth = $dbh->prepare("select * from $this->vocabularyTable");
        $sth->execute();

        $this->allVocabulary = $sth->fetchAll(PDO::FETCH_ASSOC);
        $this->allVocabularyCount = count($this->allVocabulary);

        $allVocabularyIds = array();
        $i = 0;
        foreach ($this->allVocabulary as $vocabulary) {
            $allVocabularyIds[$i] = $vocabulary['id'];  
            $i++;
        }
        unset($i);
        $this->allVocabularyIds = $allVocabularyIds;

        return $this;
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
            $this->lastQuestionEng = $lastQuestion[0]['eng'];
            $this->lastQuestionRu = $lastQuestion[0]['ru'];
            $this->lastQuestionUserAnswer = $_POST['answer'];
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
                    $this->updateUserVocabulary(false, true);
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
        if ($this->userVocabularyCount < $this->newWordsCount) {
            $this->getQuestion('new');
        } else {
            if ($this->userVocabularyCount < $this->allVocabularyCount) {
                if ($this->userStableErrorsCount > $this->maxStableErrorsCount) {
                    $this->getQuestion('stableErrors');
                } else if ($this->userErrorsCount > $this->maxErrorsCount) {
                    $this->getQuestion('errors');
                } else if ($this->userSuccessCount > $this->maxSuccessCount) {
                    $this->getQuestion('success');
                // no repit SSQ
                } else if ($this->userStableSuccessCount > $this->maxStableSuccessCount) {
                    $this->getQuestion('stableSuccess');
                } else {
                    $this->getQuestion('new');
                }
            } else {
                if ($this->userStableErrorsCount > 0) {
                    $this->getQuestion('stableErrors');
                } else if ($this->userErrorsCount > 0) {
                    $this->getQuestion('errors');
                } else if ($this->userSuccessCount > 0) {
                    $this->getQuestion('success');
                // } else if ($this->userStableSuccessCount > 0) {
                //     $this->getQuestion('stableSuccess');
                } else {
                    echo 'WIN!';
                    $this->getQuestion('stableSuccess');
                }
            }
        }
    }

    // @param: $status = (str) new || errors || stableErrors || success || stableSuccess
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

