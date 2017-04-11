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
$conn1 = connect_db("postgres", "1", "CS160");


?>

<h1>New User Registration</h1>
<h2>Welcome! Please sign up below!</h2>
<form>

    <div class = "user-div>
        <label for="username">Username:</label>
        <input type="text" class="user-div" id="username">
    </div>

    <div class = "fname-div>
        <label for="fname">First Name:</label>
    <input type="text" class="fname-div" id="fname">
    </div>

    <div class = "lname-div>
        <label for="lname">Last Name:</label>
    <input type="text" class="lname-div" id="lname">
    </div>


    <div class = "pass-div">
        <label for="pass">Password:</label>
        <input type="password" class="pass-div" id="pass">
    </div>

    <button type="submit" class="regbutt">Register!</button>
</form>

</form>




