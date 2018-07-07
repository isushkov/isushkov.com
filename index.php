<!DOCTYPE html>
<?php include('app/DbData.php') ?>
<?php include('app/DbConnect.php') ?>
<?php
$DbConnect = new DbConnect();
$DbConnect->createConnection($db_server, $db_name, $db_user, $db_password);
?>
<html>
    <?php include('head.php') ?>
    <body>
        <?php include('header.php') ?>
        <?php include('content.php') ?>
        <?php include('footer.php') ?>
    </body>
</html>
