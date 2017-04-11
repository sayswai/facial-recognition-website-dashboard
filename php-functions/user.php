<?php
/**
 * Created by PhpStorm.
 * User: BlackLabel
 * Date: 4/10/2017
 * Time: 3:59 PM
 * User Form + Functions
 * Username
 *
 * FirstName
 * LastName
 *
 * Password
 *
 *
 */

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

if(isset($_POST["insert"]))
{
    $username=$_POST["username"];
    $password=$_POST["password"];
    $firstname=$_POST["firstname"];
    $lastname=$_POST["lastname"];
    $ip=$_POST["ip"];


    $query="INSERT INTO users (username,password,firstname,lastname,ip) VALUES ('".$username."', '".$password."', '".$firstname."','".$lastname."','".$ip."')";
    $psql = pg_query($query);

    //check if connection is there.
    if($psql)
    {
        printf("The following was inserted into the CS160_Database: %s, %s, %s, %s, %s", $username, $password, $firstname, $lastname, $ip);
    }
    else{
        $errormsg = pg_last_error();
        echo "We have faced an error: ".$errormsg;
        exit();
    }
}
pg_close($conn1);
?>

<h1>New User Registration</h1>
<h2>Welcome! Please sign up below!</h2>
<form action = "user.php" method="post">

    Username: <input type="text" name="username" size="20" maxlength="50">
    <BR>
    First Name: <input type="text" name="firstname" size="20" maxlength="50">
    <BR>
    Last Name: <input type="text" name="lastname" size="20" maxlength="50">
    <br>
    Password: <input type="password" name="password" size="20" maxlength="128">
    <br>
    IP (lol): <input type="text" name="ip" size="20" maxlength="128">
    <br>
    <input type="submit" name="insert"></input>
</form>

