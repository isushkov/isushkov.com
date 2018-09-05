<?php
// update today_count
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
