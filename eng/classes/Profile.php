<?php
class Profile extends App
{
    public $newUser = false;
    public $refreshMessage = false;

    function __construct() {
        $this->getTheme();

        $this->getNewUserMessage();
        $this->getRefreshMessage();

        $this->changeTheme();
    }

    public function getNewUserMessage() {
        if (isset($_SESSION['new_user'])) {
            if (isset($_SESSION['new_user'])) {
                $this->newUser = true;

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

    public function getLogin()
    {
        $dbh = $this->getConnection();
        $sth = $dbh->prepare("select login from users where id = $this->userId");
        $sth->execute();

        return $sth->fetch(PDO::FETCH_COLUMN);
    }

    public function getVocabularyCount($typeVoc)
    {
        if ($typeVoc == '850') {
            $vocabularyTable = 'vocabulary850';
        } else if ($typeVoc == '5000') {
            $vocabularyTable = 'vocabulary';
        }
        $dbh = $this->getConnection();
        $sth = $dbh->prepare("select count(*) from $vocabularyTable");
        $sth->execute();
        return (int)$sth->fetch(PDO::FETCH_COLUMN);
    }

    public function getProgressCount($typeVoc, $status = null)
    {
        $dbh = $this->getConnection();

        // processing all_vocabulary
        if ($status === null) {
            if ($typeVoc == '850') {
                $progressTable = 'progress850';
            } else if ($typeVoc == '5000') {
                $progressTable = 'progress';
            }
            $sth = $dbh->prepare("select count(*) from $progressTable where user_id = $this->userId");
            $sth->execute();

            return (int)$sth->fetch(PDO::FETCH_COLUMN);
        }

        // processing type_vocabulary
        if ($typeVoc == '850') {
            $progressTable = 'progress850';
        } else if ($typeVoc == '5000') {
            $progressTable = 'progress';
        }

        // processing type_status
        // EE
        if ($status == 'EE') {
            $sth = $dbh->prepare("select count(*) from $progressTable where 
                errors - success > 2 and
                user_id = $this->userId");
        // E
        } else if ($status == 'E') {
            $sth = $dbh->prepare("select count(*) from $progressTable where
                errors - success <= 2 and
                errors - success >= 0 and
                user_id = $this->userId");
        // S
        } else if ($status == 'S') {
            $sth = $dbh->prepare("select count(*) from $progressTable where
                success - errors = 0 and
                success - errors <= 3 and
                user_id = $this->userId");
        // SS
        } else if ($status == 'SS') {
            $sth = $dbh->prepare("select count(*) from $progressTable where
                success - errors > 3 and
                user_id = $this->userId");
        }
        $sth->execute();

        return (int)$sth->fetch(PDO::FETCH_COLUMN);
    }

    public function changeTheme()
    {
        if (isset($_POST['change-theme'])) {
            $dbh = $this->getConnection();
            if ($this->userTheme == 0) {
                $sth = $dbh->prepare("UPDATE users SET theme = 1 WHERE id = $this->userId");
            } else {
                $sth = $dbh->prepare("UPDATE users SET theme = 0 WHERE id = $this->userId");
            }
            $sth->execute();
            header("Location: profile.php");
            exit();
        }
    }
}
