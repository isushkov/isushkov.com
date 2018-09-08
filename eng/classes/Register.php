<?php
class Register extends App
{
    function __construct()
    {
        $this->checkSession();
        $this->register();
    }

    public function register()
    {
        if (isset($_POST['submit'])) {
            $login = $_POST['login'];
            $postPassword = $_POST['password'];

            $this->errors = array();
            // проверям логин
            if (strlen($login) < 3 || strlen($login) > 30) {
                $this->errors[] = "Логин должен быть от 3-х до 30 символов";
            } else if (!preg_match("/^[a-zA-Z0-9]+$/",$login)) {
                $this->errors[] = "Логин может состоять только из букв английского алфавита и цифр";
            }
            if (strlen($postPassword) < 3 || strlen($postPassword) > 30) {
                $this->errors[] = "Пароль должен быть от 3-х до 30 символов";
            }
            if ($postPassword !== $_POST['check-password']) {
                $this->errors[] = "Пароли не совпадают";
            }
            // Соединямся с БД
            $dbh = $this->getConnection();
            // проверяем, не сущестует ли пользователя с таким именем
            $sth = $dbh->prepare("select count(*) from users where login = '$login'");
            $sth->execute();
            $userCount = $sth->fetch(PDO::FETCH_COLUMN);
            if ($userCount > 0) {
                $this->errors[] = "Пользователь с таким логином уже существует";
            }
            if (count($this->errors) == 0) {
                // Убераем лишние пробелы и делаем шифрование
                $password = password_hash($postPassword, PASSWORD_DEFAULT);
                // добавляем в БД нового пользователя
                $sth = $dbh->prepare("insert into users set login = '$login', password = '$password'");
                $sth->execute();
                //add message
                $_SESSION['new_user'] = 1;
                // сразу авторизовать
                // Записываем в БД новый хеш авторизации
                $hash = password_hash($this->random_str(10), PASSWORD_DEFAULT);
                // get user_id
                $sth = $dbh->prepare("select id from users where login = '$login'");
                $sth->execute();
                $userId = $sth->fetch(PDO::FETCH_COLUMN);
                $sth = $dbh->prepare("UPDATE users set hash = '$hash' WHERE id = '$userId'");
                $sth->execute();
                // Ставим куки
                setcookie('hash', $hash, time() + 60*60*24*30);
                $_SESSION['user_id'] = $userId;
                header("Location: profile.php");
                exit();
            }
        }
    }
}
