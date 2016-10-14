<?php

require_once("consts.php");
$connection = mysqli_connect(HOST, USER, PASS, DB);
$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = 'lss' 
        AND ENGINE = 'MyISAM'";

$rs = mysqli_query($connection,$sql);

while ($row = mysqli_fetch_array($rs)) {
    $tbl = $row[0];
    $sql = "ALTER TABLE `$tbl` ENGINE=INNODB";
    mysqli_query($connection,$sql);
}
echo "<h1>DONE</h1>";
mysqli_close($connection);
?>