<?php
class App
{
    public $userId = null;

    function __construct() {
        $this->getTheme();
    }

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

    public function checkSession()
    {
        if (isset($_COOKIE['hash']) && isset($_SESSION['user_id'])) { 
            $dbh = $this->getConnection();
            $sth = $dbh->prepare("select * from users where hash = '".$_COOKIE['hash']."'");
            $sth->execute();
            $userData = $sth->fetchAll(PDO::FETCH_ASSOC);
            // if cookie not correct
            if ($userData[0]['hash'] !== $_COOKIE['hash']) {
                setcookie("hash", "", time() - 3600*24*30*12, "/");
                unset($_SESSION['user_id']);
                // Ошибка сессии
                $_SESSION['error'] = 0;
                header("Location: login.php");
                exit();
            }
            $this->userId = $userData[0]['id'];
        // if not isset cookie
        } else {
            setcookie("hash", "", time() - 3600*24*30*12, "/");
            unset($_SESSION['user_id']);
            // Ошибка сессии. isset куки
            $_SESSION['error'] = 1;
            header("Location: login.php");
            exit();
        }
    }

    public function userLogin()
    {
        if (isset($_COOKIE['hash']) && isset($_SESSION['user_id'])) { 
            return true;
        } else {
            return false;
        }
    }

    public function showBlock($blockName)
    {
        if ($blockName === 'home') {
            if ($_SERVER['REQUEST_URI'] === '/eng/') {
                return false;
            }
        }
        if (stristr($_SERVER['REQUEST_URI'], '/eng/'.$blockName.'.php') === false) {
            return true;
        }
        return false;
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

    public function getTheme()
    {
        // if user login
        if ($this->userLogin()) {

            $this->checkSession();
            $this->getUserData();

            // get userTheme
            if ($this->userTheme == 0) {
                $this->userThemeRender = '<link rel="stylesheet" type="text/css" href="css/light.css">';
            } else if ($this->userTheme == 1) {
                $this->userThemeRender = '<link rel="stylesheet" type="text/css" href="css/dark.css">';
            } else {
                echo 'uncorrect user_theme'; die;
            }
        // eslse set light
        } else {
            $this->userThemeRender = '<link rel="stylesheet" type="text/css" href="css/light.css">';
        }
    }
}
