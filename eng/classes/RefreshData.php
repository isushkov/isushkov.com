<?php
class RefreshData extends App
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
                if (isset($_POST['type_v']) && $_POST['type_v'] == '850') {
                    $dbh = $this->getConnection();
                    $sth = $dbh->prepare("delete from progress850 where user_id = '".$this->userId."'");
                    $sth->execute();
                    //add message
                    $_SESSION['mess_refresh'] = 1;

                    header("Location: profile.php");
                    exit();
                } else if (isset($_POST['type_v']) && $_POST['type_v'] == '5000') {
                    $dbh = $this->getConnection();
                    $sth = $dbh->prepare("delete from progress where user_id = '".$this->userId."'");
                    $sth->execute();
                    //add message
                    $_SESSION['mess_refresh'] = 1;

                    header("Location: profile.php");
                    exit();
                } else {
                    //add message
                    $this->errors[] = "Произошла ошибка, повторите попытку позже";
                }
            }
        }
    }
}
