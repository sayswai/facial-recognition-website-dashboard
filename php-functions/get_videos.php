<?php

include 'db_connect.php';
include '../configs/Config.php';

//Database Connection to Postgresql.
$connection = connect_db(\dbUsername, \dbPassword, \dbDBname);

$sql = "SELECT vID FROM Videos ORDER BY time_upload DESC LIMIT 60"
$result = pg_query($sql) or die ("Cannot execute query :$query\n");
$array = pg_fetch_row($result) or die ("Cannot execute row fetch :$result\n");
pg_close($connection);

echo json_encode($array);
?>
