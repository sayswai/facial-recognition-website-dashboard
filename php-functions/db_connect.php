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

function connect_db($dbusername, $dbpassword, $dbname, $dbhost = "localhost", $dbport = "5432")
{
    //Connection variable above
    $connection = pg_connect("dbname=$dbname host=$dbhost port=$dbport user=$dbusername password=$dbpassword") or die('Connection failed');
    return $connection;
}


$db = connect_db("postgres", "Heispostgres27", "CS160");
echo pg_host($db);
?>
