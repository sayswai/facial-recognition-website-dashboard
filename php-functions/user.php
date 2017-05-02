<?php

include 'db_connect.php';
//Database Connection to Postgresql.
$conn1 = connect_db("postgres", "1", "CS160");

if($conn1)
{
    echo "The connection is valid.";
    echo ":)";
}
else{
    echo "The connection is invalid.";
}

//fetch IP

if(isset($_POST["insert"]))
{//passing onto
    $username=$_POST["uname"];
    $password=$_POST["pas"];
    $firstname=$_POST["fname"];
    $lastname=$_POST["lname"];
    $ip=$_POST["ip"];
    $encrypt = 'BitchImBadANDBoujieWhipUpTheDopeWithAnUZI';
    $encryptedpass = crypt($password,$encrypt);

    $query="INSERT INTO users (username,password,firstname,lastname,ip) VALUES ('".$username."', '".$encryptedpass."', '".$firstname."','".$lastname."','".$ip."')";
    $psql = pg_query($query); //executes the query!

    //check if connection is there.
    if($psql)
    {
        print("");
        print("Thank you, you have been registered, please sign in!");
    }
    else{
        $errormsg = pg_last_error();
        echo "We have faced an error: ".$errormsg;
        exit();
    }
}
pg_close($conn1);
?>




