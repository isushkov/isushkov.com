<?php
class App
{
    public $userId = null;

    public function getConnection()
    {
        include('dbData.php');
        try {
            $dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $pass);
        } catch (PDOException $e) {
            die('PDOException');
        }
        return $dbh;
    }

    public function userLogin()
    {
        if (isset($_COOKIE['hash']) && isset($_SESSION['user_id'])) { 
            return true;
        }
        return false;
    }
    public function checkSession()
    {
        if (isset($_COOKIE['hash']) && isset($_SESSION['user_id'])) { 
            $dbh = $this->getConnection();
            $sth = $dbh->prepare("select id, hash from users where hash = '".$_COOKIE['hash']."'");
            $sth->execute();
            $userId = $sth->fetch(PDO::FETCH_COLUMN);
            // if cookie not correct
            if ($userId !== $_SESSION['user_id']) {
                setcookie("hash", "", time() - 3600*24*30*12, "/");
                unset($_SESSION['user_id']);
                // Ошибка сессии
                $_SESSION['error'] = 0;
                header("Location: login.php");
                exit();
            }
            $this->userId = $userId;
        }
    }

    public function getTheme()
    {
        // if user login
        if ($this->userLogin()) {
            $this->checkSession();
            // get userTheme
            $dbh = $this->getConnection();
            $sth = $dbh->prepare("select theme from users where id = $this->userId");
            $sth->execute();
            $this->userTheme = (int)$sth->fetch(PDO::FETCH_COLUMN);
            if ($this->userTheme == 1) {
                $this->userThemeRender = '<link rel="stylesheet" type="text/css" href="css/dark.css">';
            } else {
                $this->userThemeRender = '<link rel="stylesheet" type="text/css" href="css/light.css">';
            }
        // eslse set light
        } else {
            $this->userThemeRender = '<link rel="stylesheet" type="text/css" href="css/light.css">';
        }
    }

    public function showBlock($blockName)
    {
        if ($blockName === 'home' && $_SERVER['REQUEST_URI'] === '/eng/') {
            return false;
        }
        if (stristr($_SERVER['REQUEST_URI'], '/eng/'.$blockName.'.php') === false) {
            return true;
        }
        return false;
    }
}
