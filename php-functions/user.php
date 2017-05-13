<?php

include 'db_connect.php';
include '../configs/Config.php';

//Database Connection to Postgresql.
$conn1 = connect_db(\dbUsername, \dbPassword, \dbDBname);

/*
if($conn1)
{
    echo "The connection is valid.";
    echo ":)";
}
else{
    echo "The connection is invalid.";
}*/

if (! empty( $_SERVER ['HTTP_X_FORWARDED_FOR'])) $fetchip = $_SERVER['HTTP_X_FORWARDED_FOR'];
else if (! empty ( $_SERVER['HTTP_CLIENT_IP'])) $fetchip = $_SERVER['HTTP_CLIENT_IP'];
else $fetchip = $_SERVER['REMOTE_ADDR'];


function failed()
{
    echo "Your login has failed!";
}

if(isset($_POST["insert"]))
{
    $username=$_POST["uname"];
    $password=$_POST["pas"];
    $firstname=$_POST["fname"];
    $lastname=$_POST["lname"];

    trim($encryptedpass = hash("sha512",$password));



    $query="INSERT INTO users (username,password,firstname,lastname,ip) VALUES ('".$username."', '".$encryptedpass."', '".$firstname."','".$lastname."','".$fetchip."')";


    #$psql = pg_query($query); //executes the query!
    $pgsql = pg_send_query($conn1, $query);
    $resp = pg_get_result($conn1);
    echo pg_result_error_field($resp, PGSQL_DIAG_SQLSTATE);
}
pg_close($conn1);

?>




