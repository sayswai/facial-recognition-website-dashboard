<?php

include 'db_connect.php';
include '../configs/Config.php';

//Database Connection to Postgresql.
$connection = connect_db(\dbUsername, \dbPassword, \dbDBname);
$vID = $_POST["vID"];

$sql = "SELECT vID, vtitle FROM Videos WHERE vID = " + $vID;
$result = pg_query($sql) or die ("Cannot execute query :$query\n");
$array = pg_fetch_row($result) or die ("Cannot execute row fetch :$result\n");
pg_close($connection);

echo json_encode($array);
?>
