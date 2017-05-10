<?php
session_start();
include 'db_connect.php';
include '../configs/Config.php';
/**
 * Created by PhpStorm.
 * User: Wai
 * Date: 5/9/2017
 * Time: 10:55 PM
 */

if(isset($_POST['submit']) && isset($_POST['vID']) && isset($_SESSION['uid'])){

    $conn = connect_db(\dbUsername, \dbPassword, \dbDBname);

    $vID = $_POST['vID'];
    $uid = $_SESSION['uid'];
    $VID_DIR = $_SERVER['DOCUMENT_ROOT'].'/vids/' .$vID;
    echo $VID_DIR;

    $query = "DELETE FROM videos WHERE vid = '" . $vID .  "'; DELETE FROM eye WHERE vid = '" . $vID .  "'; DELETE FROM openface WHERE vid = '" . $vID .  "';";
    $query .= "UPDATE users SET uservids = replace(uservids, '{".$vID."}', '') WHERE uid = '".$uid."';";
    $query .= "UPDATE users SET uploaded = uploaded - 1 WHERE uid = '" . $uid . "';";
    shell_exec('rm -rf '.$VID_DIR);
    pg_query($query);
    pg_close($conn);
}else{
    error_log('delete_video.php accessed without post');
    ?>
    <script lang="javascript">
        window.location.href = "/index.php";
    </script>
    <?php
}

?>