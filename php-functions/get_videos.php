<?php
session_start();
include 'db_connect.php';
include '../configs/Config.php';

#if(isset($_POST['submit']) && isset($_SESSION['username'])){
if(true){
    $connection = connect_db(\dbUsername, \dbPassword, \dbDBname);

    $query = "SELECT uservids FROM users WHERE username = '" . $_SESSION['username'] . "'";
    $result = pg_query($query);
    $arr = pg_fetch_all($result);
    preg_match_all('/{(.*?)}/', $arr[0][uservids], $matches);
    pg_close($connection);

    if (isset($matches[1])) {
        echo json_encode($matches[1]);
    }
}else{
    error_log('get_videos.php accessed without post');
    ?>
    <script lang="javascript">
        window.location.href = "/index.php";
    </script>
    <?php
}

?>
