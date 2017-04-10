<?php
/**
 * Created by PhpStorm.
 * User: saysw
 * Date: 4/10/2017
 * Time: 4:34 PM
 *
 *
 * Connect to local DB
 */

$dbusername = "postgres";
$dbpassword = "Heispostgres27";
$dbhost = "localhost";
$dbport = "5432";
$dbname = "CS160";



//Connection variable above
$connection = pg_connect("dbname=$dbname host=$dbhost port=$dbport user=$dbusername password=$dbpassword");
echo pg_host($connection);
echo pg_dbname($connection);


?>
