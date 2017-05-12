<?php
session_start();
include '../configs/Config.php';
/**
 * Created by PhpStorm.
 * User: Wai
 * Date: 5/12/2017
 * Time: 4:02 AM
 */

if(isset($_POST['submit']) && isset($_SESSION['uid']) && isset($_POST['vID'])){
    $pfFolder = \cliF;
    $cmd = \cliC;
    $vID = $_POST['vID'];
    $p = \clP;
    shell_exec('cd ' .$pfFolder. ' && ' .$cmd. ' ' .$vID. ' ' .$p. " > /dev/null 2>/dev/null &");
}else{
    error_log('call_v.php accessed without post');
    ?>
    <script lang="javascript">
        window.location.href = "/index.php";
    </script>
    <?php
}
?>