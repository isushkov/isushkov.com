<?php
class Profile extends App
{
    public $userId = null;
    public $newUser = false;
    public $refreshMessage = false;

    public $smallAllVocabularyCount = null;
    public $smallUserVocabularyCount = null;
    public $bigAllVocabularyCount = null;
    public $bigUserVocabularyCount = null;

    public $smallUserSuccessCount = null;
    public $smallUserErorrsCount = null;
    public $bigUserSuccessCount = null;
    public $bigUserErrorsCount = null;

    function __construct() {
        $this->checkSession();
        $this->getUserData();
        $this->getTheme();
        $this->getNewUserMessage();
        $this->getRefreshMessage();

        $this->getProfileStatistic();
        $this->changeTheme();
    }

    public function getNewUserMessage() {
        if (isset($_SESSION['new_user'])) {
            if ($_SESSION['new_user'] === 1) {
                $this->newUser = true;
            } else {
                echo 'session_new_user status faled';
            }
            unset($_SESSION['new_user']);
        }
    }
    public function getRefreshMessage() {
        if (isset($_SESSION['mess_refresh'])) {
            if ($_SESSION['mess_refresh'] === 1) {
                $this->refreshMessage = true;
            } else {
                echo 'session_mess_refresh status faled';
            }
            unset($_SESSION['mess_refresh']);
            $this->getUserData();
        }
    }

    public function getProfileStatistic()
    {
        $dbh = $this->getConnection();

        $this->getSmallAllVocabulary($dbh);
        $this->getBigAllVocabulary($dbh);
        $this->getSmallUserVocabulary($dbh);
        $this->getBigUserVocabulary($dbh);

        $this->getSmallUserSuccessCount($dbh);
        $this->getSmallUserErrorsCount($dbh);
        $this->getBigUserSuccessCount($dbh);
        $this->getBigUserErrorsCount($dbh);
    }

    public function getSmallAllVocabulary($dbh)
    {
        $sth = $dbh->prepare("select * from vocabulary850");
        $sth->execute();
        $this->smallAllVocabularyCount = count($sth->fetchAll(PDO::FETCH_ASSOC));
    }
    public function getBigAllVocabulary($dbh)
    {
        $sth = $dbh->prepare("select * from vocabulary");
        $sth->execute();
        $this->bigAllVocabularyCount = count($sth->fetchAll(PDO::FETCH_ASSOC));
    }
    public function getSmallUserVocabulary($dbh)
    {
        $sth = $dbh->prepare("select * from progress850 where user_id = $this->userId");
        $sth->execute();
        $this->smallUserVocabularyCount = count($sth->fetchAll(PDO::FETCH_ASSOC));
    }
    public function getBigUserVocabulary($dbh)
    {
        $sth = $dbh->prepare("select * from progress where user_id = $this->userId");
        $sth->execute();
        $this->bigUserVocabularyCount = count($sth->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getSmallUserSuccessCount($dbh)
    {
        $sth = $dbh->prepare("select vocabulary_id from progress850 where user_id = $this->userId and success - errors > 0");
        $sth->execute();
        $this->smallUserSuccessCount = count($sth->fetchAll(PDO::FETCH_COLUMN));
    }
    public function getSmallUserErrorsCount($dbh)
    {
        $sth = $dbh->prepare("select vocabulary_id from progress850 where user_id = $this->userId and success - errors <= 0");
        $sth->execute();
        $this->smallUserErrorsCount = count($sth->fetchAll(PDO::FETCH_COLUMN));
    }

    public function getBigUserSuccessCount($dbh)
    {
        $sth = $dbh->prepare("select vocabulary_id from progress where user_id = $this->userId and success - errors > 0");
        $sth->execute();
        $this->bigUserSuccessCount = count($sth->fetchAll(PDO::FETCH_COLUMN));
    }
    public function getBigUserErrorsCount($dbh)
    {
        $sth = $dbh->prepare("select vocabulary_id from progress where user_id = $this->userId and success - errors <= 0");
        $sth->execute();
        $this->bigUserErrorsCount = count($sth->fetchAll(PDO::FETCH_COLUMN));
    }

    public function changeTheme()
    {
        if (isset($_POST['change-theme'])) {
            if ($this->userTheme == 0) {
                $dbh = $this->getConnection();
                $sth = $dbh->prepare("UPDATE users SET theme = 1 WHERE id = $this->userId");
                $sth->execute();

                header("Location: profile.php");
                exit();

            } else if ($this->userTheme == 1) {
                $dbh = $this->getConnection();
                $sth = $dbh->prepare("UPDATE users SET theme = 0 WHERE id = $this->userId");
                $sth->execute();

                header("Location: profile.php");
                exit();

            } else {
                echo 'uncorrect user_theme'; die;
            }
        }
    }
}
