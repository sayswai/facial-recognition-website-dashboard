<?php

include 'db_connect.php';
//Database Connection to Postgresql.
$conn1 = connect_db("postgres", "1", "CS160");

if($conn1)
{
    echo "The connection is valid ";
    echo ":)";
}
else{
    echo "The connection is invalid.";
}
pg_close($conn1);
?>


<form id='login' action='login.php' method='post' accept-charset='UTF-8'>
    <fieldset>
        <legend>Login</legend>
        <input type='hidden' name='submitted' id='submitted' value='1'/>
        <label for='username' >UserName*:</label>
        <input type='text' name='username' id='username'  maxlength="50" />
        <label for='password' >Password*:</label>
        <input type='password' name='password' id='password' maxlength="50" />
        <input type='submit' name='Submit' value='Log in' />
    </fieldset>
</form>
<form action="user.php" method="get">
    <input type="submit" value="Click Here To Register!" name="submit" id="login_submit"/>
</form>

