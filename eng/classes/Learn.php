<?php
class Learn extends Profile
{
    public $countFalseVariants = 9;
    public $todayNeedCheck = 150;
    public $minWords = 50;
    public $maxSS = 50;
    public $maxS = 100;
    public $maxE = 50;
    public $maxEE = 30;
    public $lastSSQFailed = false;

    function __construct()
    {
        $this->checkSession();

        $this->todayNeedCheckYellow = (int)($this->todayNeedCheck / 3) * 2;
        $this->todayNeedCheckGreen = (int)($this->todayNeedCheck / 3);
        $this->todayCount = $this->getUserData('today_count');
        $this->typeVocabulary = $this->getUserData('type_vocabulary');
        if ($this->typeVocabulary == '850') {
            $this->vocabularyTable = 'vocabulary850';
            $this->progressTable = 'progress850';
        } else if ($this->typeVocabulary == '5000') {
            $this->vocabularyTable = 'vocabulary';
            $this->progressTable = 'progress';
        }

        $this->progressingPostData();

        $this->progressCount = $this->getProgressCount($this->typeVocabulary);
        $this->vocabularyCount = $this->getVocabularyCount($this->typeVocabulary);
        $this->countSS = $this->getProgressCount($this->typeVocabulary, 'SS');
        $this->countS = $this->getProgressCount($this->typeVocabulary, 'S');
        $this->countE = $this->getProgressCount($this->typeVocabulary, 'E');
        $this->countEE = $this->getProgressCount($this->typeVocabulary, 'EE');

        $this->generateQuestion();
    }

    public function progressingPostData()
    {
        if (isset($_POST['answer']) && isset($_POST['question-id'])) {
            $this->lastQuestionId = $_POST['question-id'];
            // get last Question data
            $dbh = $this->getConnection();
            $sth = $dbh->prepare("select * from $this->vocabularyTable where id = $this->lastQuestionId");
            $sth->execute();
            $lastQuestion = $sth->fetch(PDO::FETCH_ASSOC);
            $this->lastQuestionEng = $lastQuestion['eng'];
            $this->lastQuestionRu = $lastQuestion['ru'];
            $this->lastQuestionUserAnswer = $_POST['answer'];
            // get lastQuestionStatistic
            $this->lastQuestionStatus = $_POST['lq_status'];
            if ($this->lastQuestionStatus == 'Новое слово') {
                $this->lastQuestionA = 0;
                $this->lastQuestionS = 0;
                $this->lastQuestionE = 0;
            } else {
                $sth = $dbh->prepare("select summary, success, errors from $this->progressTable where
                    user_id = $this->userId and
                    vocabulary_id = $this->lastQuestionId");
                $sth->execute();
                $lastQuestionStatistic = $sth->fetch(PDO::FETCH_ASSOC);
                $this->lastQuestionA = $lastQuestionStatistic['summary'];
                $this->lastQuestionS = (int)$lastQuestionStatistic['success'];
                $this->lastQuestionE = (int)$lastQuestionStatistic['errors'];
            }

            // try answer
            if ($_POST['answer'] == $this->lastQuestionRu) {
                $this->questionResult = true;
                if ($this->lastQuestionA > 0) {
                    $this->updateUserVocabulary(true, true);
                } else {
                    $this->updateUserVocabulary(true, false);
                }
            // false answer
            } else {
                $this->questionResult = false;
                if ($this->lastQuestionA > 0) {
                    //SSQ
                    if ($this->lastQuestionStatus == 'Хорошо знаю') {
                        $this->resetSSProgress();
                    } else {
                        $this->updateUserVocabulary(false, true);
                    }
                } else {
                    $this->updateUserVocabulary(false, false);
                }
            }
        }
        $this->changeVocabulary();
    }

    public function updateUserVocabulary($success, $oldQuestion)
    {
        $this->lastQuestionA++;
        if ($success) {
            $this->lastQuestionS++;
        } else {
            $this->lastQuestionE++;
        }
        $dbh = $this->getConnection();
        if ($oldQuestion) {
            $sth = $dbh->prepare(
                "update $this->progressTable set
                summary = $this->lastQuestionA, success = $this->lastQuestionS, errors = $this->lastQuestionE where
                user_id = $this->userId and vocabulary_id = $this->lastQuestionId"
            );
            $sth->execute();
        } else {
            $sth = $dbh->prepare(
                "insert into $this->progressTable (user_id, vocabulary_id, summary, success, errors)
                values ($this->userId, $this->lastQuestionId,
                $this->lastQuestionA, $this->lastQuestionS, $this->lastQuestionE)"
            );
            $sth->execute();
        }
        // today_count--
        $this->updateTodayCount();
    }
    public function resetSSProgress()
    {
        $this->lastSSQFailed = true;
        $dbh = $this->getConnection();
        // Неверно. old question
        $this->lastQuestionA = 1;
        $this->lastQuestionS = 1;
        $this->lastQuestionE = 0;

        $sth = $dbh->prepare(
            "update $this->progressTable set
            summary = $this->lastQuestionA, success = $this->lastQuestionS, errors = $this->lastQuestionE where
            user_id = $this->userId and vocabulary_id = $this->lastQuestionId");
        $sth->execute();
        // today_count--
        $this->updateTodayCount();
    }

    public function updateTodayCount()
    {
        $dbh = $this->getConnection();
        if ($this->todayCount > 0) {
            $this->todayCount--;
            $sth = $dbh->prepare("UPDATE users SET today_count = $this->todayCount WHERE id = $this->userId");
            $sth->execute();
        }
    }

    public function changeVocabulary()
    {
        if (isset($_POST['change-vocabulary'])) {
            $postCV = $_POST['change-vocabulary'];
            if ($postCV == '850' || $postCV == '5000') {
                $dbh = $this->getConnection();
                $sth = $dbh->prepare("UPDATE users SET type_vocabulary = $postCV WHERE id = $this->userId");
                $sth->execute();
            }
        }
    }

    public function getUserData($field = null)
    {
        $dbh = $this->getConnection();
        if ($field === null) {
            $sth = $dbh->prepare("select * from users where id = $this->userId");
            $sth->execute();
            return $sth->fetch(PDO::FETCH_ASSOC);
        } else {
            $sth = $dbh->prepare("select $field from users where id = $this->userId");
            $sth->execute();
            return $sth->fetch(PDO::FETCH_COLUMN);
        }
    }

    public function generateQuestion()
    {
        $todayCount      = $this->todayCount;
        $typeVocabulary  = $this->typeVocabulary;
        $progressCount   = $this->progressCount;
        $vocabularyCount = $this->vocabularyCount;
        $countSS         = $this->countSS;
        $countS          = $this->countS;
        $countE          = $this->countE;
        $countEE         = $this->countEE;
        $todayCheckG     = $this->todayNeedCheckGreen;
        $todayCheckY     = $this->todayNeedCheckYellow;

        if ($progressCount < $vocabularyCount) {
            // started quantity
            if ($progressCount < $this->minWords) {
                $this->getQuestion('new');
            // if first today visits
            } else if ($todayCount >= ($this->todayNeedCheck - 10)) {
                $this->getQuestion('new');
            // get SS
            } else if ($countSS >= 300 && $todayCount > $todayCheckY && $todayCount <= $todayCheckY + 10) {
                $this->getQuestion('SS');
            } else if ($countSS >= 50 && $todayCount > $todayCheckG && $todayCount <= $todayCheckG + 10) {
                $this->getQuestion('SS');
            // processing errors
            } else if ($countEE > $this->maxEE) {
                $this->getQuestion('EE');
            } else if ($countE > $this->maxE) {
                $this->getQuestion('E');
            } else if ($countS > $this->maxS) {
                $this->getQuestion('S');
            // next
            } else {
                $this->getQuestion('new');
            }
        } else {
            // get SS
            if ($countSS > 0 && $todayCount >= $this->todayNeedCheck - 10) {
                $this->getQuestion('SS');
            } else if ($countSS >= 300 && $todayCount > $todayCheckY && $todayCount <= $todayCheckY + 10) {
                $this->getQuestion('SS');
            } else if ($countSS >= 50 && $todayCount > $todayCheckG && $todayCount <= $todayCheckG + 10) {
                $this->getQuestion('SS');
            // processing errors
            } else if ($countEE > 0) {
                $this->getQuestion('EE');
            } else if ($countE > 0) {
                $this->getQuestion('E');
            } else if ($countS > 0) {
                $this->getQuestion('S');
            // WIN
            } else {
                $this->getQuestion('SS');
            }
        }
    }

    // @param $status = (str) new || errors || stableErrors || success || stableSuccess
    public function getQuestion($status)
    {
        $dbh = $this->getConnection();
        if ($status === 'new') {
            $this->questionType = 'Hовое слово';
            $this->questionTypeClass = 'dark';
            // get new question
            $vocabularyIds = $this->getVocabularyData('id');
            $progressIds = $this->getProgressData('vocabulary_id');
            $questionIds = array_diff($vocabularyIds, $progressIds);
        } else {
            // processing status
            if ($status === 'EE') {
                $this->questionType = 'He знаю';
                $this->questionTypeClass = 'red2';
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where
                    errors - success >= 2 and
                    user_id = $this->userId");
            }
            if ($status === 'E') {
                $this->questionType = 'Плохо знаю';
                $this->questionTypeClass = 'red';
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where
                    errors - success <= 1 and
                    errors - success >= 0 and
                    user_id = $this->userId");
            }
            if ($status === 'S') {
                $this->questionType = 'Знаю';
                $this->questionTypeClass = 'yellow';
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where
                    success - errors >= 1 and
                    success - errors <= 3 and
                    user_id = $this->userId");
            }
            if ($status === 'SS') {
                $this->questionType = 'Хорошо знаю';
                $this->questionTypeClass = 'green';
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where
                    success - errors >= 4 and
                    user_id = $this->userId");
            }
            $sth->execute();
            $questionIds = $sth->fetchAll(PDO::FETCH_COLUMN);
        }
        shuffle($questionIds);
        $questionId = array_shift($questionIds);
        // no repit Q
        if (isset($this->lastQuestionId)) {
            while ($this->lastQuestionId == $questionId) {
                $questionId = array_shift($questionIds);
            }
        }
        // get Q
        $sth = $dbh->prepare("select * from $this->vocabularyTable where id = $questionId");
        $sth->execute();
        $question = $sth->fetch(PDO::FETCH_ASSOC);
        $this->questionId = $question['id'];
        $this->questionEng = $question['eng'];
        $this->questionRu = $question['ru'];
        // get newquestion statistic
        if ($status == 'new') {
            $this->questionA = 0;
            $this->questionS = 0;
            $this->questionE = 0;
        } else {
            $sth = $dbh->prepare("select * from $this->progressTable where
                user_id = $this->userId and
                vocabulary_id = $this->questionId");
            $sth->execute();
            $questionStatistic = $sth->fetch(PDO::FETCH_ASSOC);
            $this->questionA = $questionStatistic['summary'];
            $this->questionS = $questionStatistic['success'];
            $this->questionE = $questionStatistic['errors'];
        }
        // get variants
        $this->variants = $this->getVariants($question);
    }

    public function getVocabularyData($field = null)
    {
        $dbh = $this->getConnection();
        if ($field == null) {
            $sth = $dbh->prepare("select * from $this->vocabularyTable");
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $sth = $dbh->prepare("select $field from $this->vocabularyTable");
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_COLUMN);
        }
    }

    public function getProgressData($field = null)
    {
        $dbh = $this->getConnection();
        if ($field == null) {
            $sth = $dbh->prepare("select * from $this->progressTable where user_id = $this->userId");
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $sth = $dbh->prepare("select $field from $this->progressTable where user_id = $this->userId");
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_COLUMN);
        }
    }

    public function getVariants($question)
    {
        $variants[0] = $question['ru'];
        // get false variants
        $vocabularyDataRu = $this->getVocabularyData('ru');
        $falseVariantsIds = array_rand($vocabularyDataRu, $this->countFalseVariants);
        $i = 1;
        foreach ($falseVariantsIds as $falseVariantId) {
            if ($falseVariantId != $question['id']) {
                $variants[$i] = $vocabularyDataRu[$falseVariantId];
                $i++;
            }
        }
        $i = null;
        shuffle($variants);
        return $variants;
    }
}
