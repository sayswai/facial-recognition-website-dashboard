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


    $psql = pg_query($query); //executes the query!

    //check if succeeded.
    if($psql)
    {
        echo '<script language="javascript">';
        echo 'alert("Thank You! You have now been registered!")';
        echo '</script>';
    }
    else{
        $errormsg = pg_last_error();
        echo "We have faced an error: ".$errormsg;
        exit();
    }
}
pg_close($conn1);
?>




