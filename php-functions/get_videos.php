<?php

include 'db_connect.php';
include '../configs/Config.php';

//Database Connection to Postgresql.
$conn1 = connect_db(\dbUsername, \dbPassword, \dbDBname);

$sql = "SELECT vID FROM Videos ORDER BY time_upload DESC LIMIT 60"
$result = pg_query($sql);
$array = pg_fetch_row($result);

echo json_encode($array);
?>
