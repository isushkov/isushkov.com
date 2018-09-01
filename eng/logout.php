<?php
session_start();
unset($_SESSION['error']);
unset($_SESSION['user_id']);
setcookie('hash', '', time() - 60*60*24*3, '/');
header("Location: home.php");
exit();
