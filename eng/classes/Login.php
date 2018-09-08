<?php
class Login extends App
{
    function __construct()
    {
        $this->checkSession();
        $this->login();
    }

    public function login()
    {
        $this->loginError = false;
        if (isset($_POST['submit'])) {
            // Соединямся с БД
            if (!$_POST['login']) {
                $this->loginError = "Укажите Ваш логин";
            }
            if (!$_POST['password']) {
                $this->loginError = "Укажите Ваш пароль";
            }
            if (!$this->loginError) {
                $dbh = $this->getConnection();
                $login = $_POST['login'];
                $sth = $dbh->prepare("select id, password from users where login = '$login'");
                $sth->execute();
                $userData = $sth->fetch(PDO::FETCH_NUM);
                if ($userData && password_verify($_POST['password'], $userData[1])) {
                    // Генерируем случайное число и шифруем его
                    $hash = password_hash($this->random_str(10), PASSWORD_DEFAULT);
                    // Записываем в БД новый хеш авторизации
                    $sth = $dbh->prepare("UPDATE users set hash = '$hash' WHERE id = '$userData[0]'");
                    $sth->execute();
                    // Ставим куки
                    setcookie('hash', $hash, time() + 60*60*24*30);
                    $_SESSION['user_id'] = $userData[0];
                    header("Location: profile.php");
                    exit();
                } else {
                    $this->loginError = "Hеверный логин или пароль";
                }
            }
        }
    }
}
