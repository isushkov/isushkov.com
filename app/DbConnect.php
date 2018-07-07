<?php 
class DbConnect
{
    public function createConnection($db_server, $db_name, $db_user, $db_password) 
    {
        try {
            $dbh = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_password);
            // foreach ($dbh->query('SELECT * from products') as $row) {
            //     print_r($row);
            // }
            echo '|connection succses|';
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function closeConnection($dbh) 
    {
        echo '|connection closed|';
        $dbh = null;
    }
}
