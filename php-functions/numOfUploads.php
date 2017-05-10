<?php
session_start();
include 'db_connect.php';
include '../configs/Config.php';
/**
 * Created by PhpStorm.
 * User: saysw
 * Date: 5/9/2017
 * Time: 6:44 PM
 */
if (isset($_POST['submit']) && isset($_SESSION['username']))
#if(true)
{
    $connection = connect_db(\dbUsername, \dbPassword, \dbDBname);

    $query = "SELECT uploaded FROM users WHERE username = '" . $_SESSION['username'] . "'";
    $result = pg_query($query);
    $arr = pg_fetch_all($result);
    pg_close($connection);

    echo $arr[0][uploaded];
}else{
    error_log('numOfUploads.php accessed without post');
    ?>
    <script lang="javascript">
        window.location.href = "/index.php";
    </script>
    <?php
}

?>