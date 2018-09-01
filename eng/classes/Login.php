<?php
class Login extends App
{
    public $newUser = null;
    public $sessionError = null;
    public $loginErrors = null;

    public function summary() {
        // checkRedirect
        if (isset($_COOKIE['hash']) && isset($_SESSION['user_id'])) { 
            header("Location: profile.php");
            exit();
        }

        $this->getDeleteUserMessage();
        $this->getErrorsMessages();

        if (isset($_POST['submit'])) {
            // Соединямся с БД
            $this->loginErrors = array();
            if (!$_POST['login']) {
                $this->loginErrors[] = "Укажите Ваш логин";
            }
            if (!$_POST['password']) {
                $this->loginErrors[] = "Укажите Ваш пароль";
            }
            if (count($this->loginErrors) == 0) {
                $dbh = $this->getConnection();
                $sth = $dbh->prepare("select id, password from users where login = '".$_POST['login']."' LIMIT 1");
                $sth->execute();
                $userData = $sth->fetchAll(PDO::FETCH_ASSOC);
                if (count($userData) == 1 && password_verify($_POST['password'], $userData[0]['password'])) {
                    // Генерируем случайное число и шифруем его
                    $hash = password_hash($this->random_str(10), PASSWORD_DEFAULT);
                    // Записываем в БД новый хеш авторизации
                    $sth = $dbh->prepare("UPDATE users set hash = '$hash' WHERE id = '".$userData[0]['id']."'");
                    $sth->execute();
                    // Ставим куки
                    setcookie('hash', $hash, time() + 60*60*24*30);
                    // setcookie('user_id', $userData['id'], time() + 60*60*24*30);
                    $_SESSION['user_id'] = $userData[0]['id'];
                    header("Location: profile.php");
                    exit();
                } else {
                    $this->loginErrors[] = "Hеверный логин или пароль";
                }
            }
        }
    }

    public function getDeleteUserMessage() {
        if (isset($_SESSION['delete_user'])) {
            if ($_SESSION['delete_user'] === 1) {
                $this->deleteUser = true;
            } else {
                echo 'session_delete_user status faled'; die;
            }
            unset($_SESSION['delete_user']);
        }
    }

    public function getErrorsMessages() {
        if (isset($_SESSION['error'])) {
            if ($_SESSION['error'] === 1) {
                $this->sessionError = 'Для входa необходимо авторизоваться';
                unset($_SESSION['error']);
            } else {
                $this->sessionError = 'Ошибка сессии. Пожалуйста, войдите снова';
                unset($_SESSION['error']);
            }
        }
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
