<?php
class Learn extends Profile
{
    public $countFalseVariants = 3;

    public $todayNeedCheck = 50;
    public $minWords = 20;
    public $maxS = 10;
    public $maxE = 5;
    public $maxEE = 5;
    public $lastSSQFailed = false;

    function __construct() 
    {
        $this->checkSession();
        $this->progressingPostData();
            $this->changeVocabulary();

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
            $lastQuestionId = $_POST['question-id'];
            // get last Question data
            $dbh = $this->getConnection();
            $sth = $dbh->prepare("select * from $this->vocabularyTable where id = $lastQuestionId");
            $sth->execute();
            $lastQuestion = $sth->fetch(PDO::FETCH_ASSOC);
            $this->lastQuestionId = $lastQuestion['id'];
            $this->lastQuestionEng = $lastQuestion['eng'];
            $this->lastQuestionRu = $lastQuestion['ru'];
            $this->lastQuestionUserAnswer = $_POST['answer'];
            // try answer
            if ($_POST['answer'] == $this->lastQuestionRu) {
                $this->questionResult = true;
                if ($this->questionA > 0) { 
                    $this->updateUserVocabulary(true, true);
                } else {
                    $this->updateUserVocabulary(true, false);
                }
            // false answer
            } else {
                $this->questionResult = false;
                if ($this->questionA > 0) { 
                    //SSQ
                    if (isset($_POST['lq_ss'])) {
                        $this->resetSSProgress();
                    } else {
                        $this->updateUserVocabulary(false, true);
                    }
                } else {
                    $this->updateUserVocabulary(false, false);
                }
            }
        }
    }

    public function updateUserVocabulary($success, $oldQuestion)
    {
        $this->questionA++;
        if ($success) {
            $this->questionS++;
        } else {
            $this->questionE++;
        }
        $dbh = $this->getConnection();
        if ($oldQuestion) {
            $sth = $dbh->prepare(
                "update $this->progressTable set
                summary = $questionA, success = $questionS, errors = $questionE where
                user_id = $this->userId and vocabulary_id = $this->questionId"
            );
            $sth->execute();
        } else {
            $sth = $dbh->prepare(
                "insert into $this->progressTable (user_id, vocabulary_id, summary, success, errors)
                values ($this->userId, $this->questionId, $questionA, $questionS, $questionE)"
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
        $this->questionA = 1;
        $this->questionS = 1;
        $this->questionE = 0;

        $sth = $dbh->prepare(
            "update $this->progressTable set
            summary = $questionA, success = $questionS, errors = $questionE where
            user_id = $this->userId and vocabulary_id = $this->questionId");
        $sth->execute();
        // today_count--
        $this->updateTodayCount();
    }

    public function updateTodayCount()
    {
        if ($this->todayCount > 0) {
            $this->todayCount--;
            $sth = $dbh->prepare("UPDATE users SET today_count = $this->todayCount WHERE id = $this->userId");
            $sth->execute();
        }
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

    // ==========================
    // ==========================
    // ==========================
    // ==========================
    
    public function getUserData($field = null)
    {
        $dbh = $this->getConnection();
        if ($field == null) {
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

        if ($progressCount < $vocabularyCount) {
            // started quantity
            if ($progressCount < $this->minWords) {
                $this->getQuestion('new');
            // if first today visits
            } else if ($todayCount >= ($this->todayNeedCheck - 10)) {
                $this->getQuestion('new');
            // if last today visits
            } else if ($todayCount > 0 && $todayCount <= 10 && $SScount >= 50) {
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
            // if first today visits
            if ($todayCount <= 140 && $todayCount >= 120 && $countSS > 0) {
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
        if ($status === 'new') {
            $this->questionType = 'Hовое слово';
            $this->questionTypeClass = 'dark';
            // get new question
            $vocabularyIds = $this->getVocabularyData('id');
            $progressIds = $this->getProgressData('id');
            $questionIds = array_diff($vocabularyIds, $progressIds);
        } else {
            // processing status
            $dbh = $this->getConnection();
            if ($status === 'EE') {
                $this->questionType = 'He знаю';
                $this->questionTypeClass = 'red2';
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where
                    errors - success > 2 and
                    user_id = $this->userId");
            }
            if ($status === 'E') {
                $this->questionType = 'Плохо знаю';
                $this->questionTypeClass = 'red';
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where
                    errors - success <= 2 and
                    errors - success >= 0 and
                    user_id = $this->userId");
            }
            if ($status === 'S') {
                $this->questionType = 'Знаю';
                $this->questionTypeClass = 'yellow';
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where
                    success - errors = 0 and
                    success - errors <= 3 and
                    user_id = $this->userId");
            }
            if ($status === 'SS') {
                $this->questionType = 'Хорошо знаю';
                $this->questionTypeClass = 'green';
                $sth = $dbh->prepare("select vocabulary_id from $this->progressTable where
                    success - errors > 3 and
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
            $sth = $dbh->prepare("select * from $this->progressTable");
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $sth = $dbh->prepare("select $field from $this->progressTable");
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_COLUMN);
        }
    }

    public function getVariants($question)
    {
        $variants[0] = $question['ru'];
        // get false variants
        $vocabularyDataRu = $this->getVocabularyData('ru');
        $falseVariantsRu = array_rand($vocabularyDataRu, $this->countFalseVariants);
        $i = 1;
        foreach ($falseVariantsRu as $falseVariantRu) {
            // if falseVariant = questionRu
            while ($falseVariantRu == $question['ru']) {
                $falseVariantRu = array_rand($vocabularyDataRu, 1);
            }
            $variants[$i] = $falseVariantRu;
            $i++;
        }
        $i = null;
        shuffle($variants);
        return $variants;
    }
}
