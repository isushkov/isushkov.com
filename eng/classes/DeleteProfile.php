<?php
class DeleteProfile extends App
{
    public $errors = null;

    public function summary() {
        if (isset($_POST['submit'])) {
            $this->errors = array();
            if ($_POST['password'] !== $_POST['check-password']) {
                $this->errors[] = "Пароли не совпадают";
            } else {
                $dbh = $this->getConnection();
                $sth = $dbh->prepare("select * from users where hash = '".$_COOKIE['hash']."'");
                $sth->execute();
                $userData = $sth->fetchAll(PDO::FETCH_ASSOC);

                if (!password_verify($_POST['password'], $userData[0]['password'])) {
                    $this->errors[] = "Пароль не верный";
                }
            }
            if (count($this->errors) == 0) {
                $dbh = $this->getConnection();
                $sth = $dbh->prepare("delete from users where id = '".$this->userId."'");
                $sth->execute();
                $sth = $dbh->prepare("delete from progress where user_id = '".$this->userId."'");
                $sth->execute();
                $sth = $dbh->prepare("delete from progress850 where user_id = '".$this->userId."'");
                $sth->execute();
                //add message
                $_SESSION['delete_user'] = 1;

                unset($_SESSION['error']);
                unset($_SESSION['user_id']);
                setcookie('hash', '', time() - 60*60*24*3, '/');
                header("Location: login.php");
                exit();
            }
        }
    }
}
