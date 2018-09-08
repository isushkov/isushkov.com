<?php
class DeleteProfile extends App
{
    function __construct()
    {
        $this->checkSession();
        $this->deleteProfile();
    }

    public function deleteProfile() {
        $this->error = false;
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
                $sth = $dbh->prepare("delete from users where id = '$this->userId'");
                $sth->execute();
                $sth = $dbh->prepare("delete from progress where user_id = '$this->userId'");
                $sth->execute();
                $sth = $dbh->prepare("delete from progress850 where user_id = '$this->userId'");
                $sth->execute();
                //add message
                $_SESSION['delete_user'] = 1;

                setcookie('hash', '', time() - 60*60*24*3, '/');
                unset($_SESSION['user_id']);
                header("Location: login.php");
                exit();
            }
        }
    }
}
