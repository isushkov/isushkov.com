<?php
// check php-cli
if (PHP_SAPI != 'cli'){
    session_start();
    $_SESSION['page_not_found'] = 1;
    header("Location: home.php");
    exit();
} else {
    // cron update today_count
    include('dbData.php');
    try {
        $dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $pass);
    } catch (PDOException $e) {
        die('PDOException');
    }
    $sth = $dbh->prepare("UPDATE users SET today_count = :today_count WHERE id > -1");
    $sth->execute(array(
        'today_count' => 150
    ));
}
