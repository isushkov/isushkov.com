<?php 
include('app/ConnectionData.php');
try {
    $dbh = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_password);
    foreach ($dbh->query('SELECT * from products') as $row) {
        print_r($row);
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
