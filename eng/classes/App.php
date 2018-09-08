<?php
class App
{
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
        $request = $_SERVER['REQUEST_URI'];
        // processing from login
        if (isset($_COOKIE['hash']) && isset($_SESSION['user_id'])) {
            // check cookie
            $dbh = $this->getConnection();
            $sth = $dbh->prepare("select id, hash from users where hash = '".$_COOKIE['hash']."'");
            $sth->execute();
            $userId = $sth->fetch(PDO::FETCH_COLUMN);
            // if cookie not correct
            if ($userId !== $_SESSION['user_id']) {
                setcookie("hash", "", time() - 3600*24*30*12, "/");
                unset($_SESSION['user_id']);
                // add message "session error"
                $_SESSION['error'] = 0;
                header("Location: login.php");
                exit();
            }
            $this->userId = $userId;
            // need redirect?
            if (!stristr($request, '/eng/login') == false
                || !stristr($request, '/eng/register') == false)
            {
                header("Location: profile.php");
                exit();
            }
            // get theme
            $sth = $dbh->prepare("select theme from users where id = $this->userId");
            $sth->execute();
            $userTheme = (int)$sth->fetch(PDO::FETCH_COLUMN);
            if ($userTheme == 1) {
                $this->userTheme = 'dark';
            } else {
                $this->userTheme = 'light';
            }
        // processing from not login
        } else {
            // ckeck cookie
            setcookie("hash", "", time() - 3600*24*30*12, "/");
            unset($_SESSION['user_id']);
            // need redirect?
            if (!stristr($request, '/eng/profile') == false
                || !stristr($request, '/eng/learn') == false
                || !stristr($request, '/eng/delete-profile') == false
                || !stristr($request, '/eng/refresh-data') == false)
            {
                // add message "need auch"
                $_SESSION['error'] = 1;
                header("Location: login.php");
                exit();
            }
            // get theme
            $this->userTheme = 'light';
        }

        $this->getAllMessages();
    }

    public function getAllMessages()
    {
        $this->sessionError = false;
        if (isset($_SESSION['error'])) {
            if ($_SESSION['error'] === 0) {
                $this->sessionError = 'Ошибка сессии. Войдите снова';
            }
            if ($_SESSION['error'] === 1) {
                $this->sessionError = 'Для входa необходимо авторизоваться';
            }
        }
        unset($_SESSION['error']);

        $this->pageNotFound = false;
        if (isset($_SESSION['page_not_found'])) {
            $this->pageNotFound = true;
        }
        unset($_SESSION['page_not_found']);

        $this->newUser= false;
        if (isset($_SESSION['new_user'])) {
            $this->newUser = true;
        }
        unset($_SESSION['new_user']);

        $this->refreshMessage= false;
        if (isset($_SESSION['mess_refresh'])) {
            $this->refreshMessage = true;
        }
        unset($_SESSION['mess_refresh']);

        $this->deleteUser = false;
        if (isset($_SESSION['delete_user'])) {
            $this->deleteUser = true;
        }
        unset($_SESSION['delete_user']);
    }

    public function isLogin()
    {
        if (isset($this->userId)) {
            return true;
        }
        return false;
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

    protected function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[rand(0, $max)];
        }
        return $str;
    }
}
