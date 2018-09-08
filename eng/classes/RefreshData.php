<?php
class RefreshData extends App
{
    function __construct()
    {
        $this->checkSession();
        $this->refreshData();
    }

    public function refreshData() {
        $this->error = false;
        // get type_vocabulary
        if (isset($_GET['vocabulary']) && $_GET['vocabulary'] === '5000') {
            $this->pageTitle = 'Сброс данных - словарь 2';
            $progressTable = 'progress';
        } else {
            $this->pageTitle = 'Сброс данных - словарь 1';
            $progressTable = 'progress850';
        }
        // processing refresh
        if (isset($_POST['submit'])) {
            if ($_POST['password'] !== $_POST['check-password']) {
                $this->error = "Пароли не совпадают";
            } else {
                $dbh = $this->getConnection();
                $hash = $_COOKIE['hash'];
                $sth = $dbh->prepare("select password from users where hash = '$hash'");
                $sth->execute();
                $userPassword = $sth->fetch(PDO::FETCH_COLUMN);
                if (!password_verify($_POST['password'], $userPassword)) {
                    $this->error = "Пароль не верный";
                }
            }
            if (!$this->error) {
                $dbh = $this->getConnection();
                $sth = $dbh->prepare("delete from $progressTable where user_id = '$this->userId'");
                $sth->execute();
                //add message
                $_SESSION['mess_refresh'] = 1;
                header("Location: profile.php");
                exit();
            }
        }
    }
}
