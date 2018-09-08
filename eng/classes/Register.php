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
            $this->errors = array();
            // проверям логин
            if (strlen($_POST['login']) < 3 || strlen($_POST['login']) > 30) {
                $this->errors[] = "Логин должен быть от 3-х до 30 символов";
            } else if (!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login'])) {
                $this->errors[] = "Логин может состоять только из букв английского алфавита и цифр";
            }
            if (strlen($_POST['password']) < 3 || strlen($_POST['password']) > 30) {
                $this->errors[] = "Пароль должен быть от 3-х до 30 символов";
            }
            if ($_POST['password'] !== $_POST['check-password']) {
                $this->errors[] = "Пароли не совпадают";
            }
            // Соединямся с БД
            $dbh = $this->getConnection();
            // проверяем, не сущестует ли пользователя с таким именем
            $sth = $dbh->prepare("select id from users where login = '".$_POST['login']."'");
            $sth->execute();
            $userCount = count($sth->fetchAll(PDO::FETCH_COLUMN));
            if ($userCount > 0) {
                $this->errors[] = "Пользователь с таким логином уже существует";
            }
            if (count($this->errors) == 0) {
                $login = $_POST['login'];
                // Убераем лишние пробелы и делаем шифрование
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                // добавляем в БД нового пользователя
                $sth = $dbh->prepare("insert into users set login = '$login', password = '$password'");
                $sth->execute();
                //add message
                $_SESSION['new_user'] = 1;

                // сразу авторизовать
                // Генерируем случайное число и шифруем его
                $hash = password_hash($this->random_str(10), PASSWORD_DEFAULT);
                // Записываем в БД новый хеш авторизации
                $sth = $dbh->prepare("select id, password from users where login = '".$_POST['login']."' LIMIT 1");
                $sth->execute();
                $userData = $sth->fetchAll(PDO::FETCH_ASSOC);
                $sth = $dbh->prepare("UPDATE users set hash = '$hash' WHERE id = '".$userData[0]['id']."'");
                $sth->execute();
                // Ставим куки
                setcookie('hash', $hash, time() + 60*60*24*30);
                // setcookie('user_id', $userData['id'], time() + 60*60*24*30);
                $_SESSION['user_id'] = $userData[0]['id'];
                header("Location: profile.php");
                exit();
            }
        }
    }
}
