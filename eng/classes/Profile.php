<?php
class Profile extends App
{
    function __construct()
    {
        $this->checkSession();
        $this->changeTheme();
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
        // processing type_vocabulary
        if ($typeVoc == '850') {
            $progressTable = 'progress850';
        } else if ($typeVoc == '5000') {
            $progressTable = 'progress';
        }
        $dbh = $this->getConnection();
        // processing all_vocabulary
        if ($status === null) {
            $sth = $dbh->prepare("select count(*) from $progressTable where user_id = $this->userId");
            $sth->execute();
            return (int)$sth->fetch(PDO::FETCH_COLUMN);
        }
        // processing status
        if ($status == 'EE') {
            $sth = $dbh->prepare("select count(*) from $progressTable where
                errors - success > 2 and
                user_id = $this->userId");
        } else if ($status == 'E') {
            $sth = $dbh->prepare("select count(*) from $progressTable where
                errors - success <= 2 and
                errors - success >= 0 and
                user_id = $this->userId");
        } else if ($status == 'S') {
            $sth = $dbh->prepare("select count(*) from $progressTable where
                success - errors > 0 and
                success - errors <= 3 and
                user_id = $this->userId");
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
            if ($this->userTheme == 'light') {
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
