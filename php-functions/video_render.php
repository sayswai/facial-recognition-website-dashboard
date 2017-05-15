<?php
session_start();
include 'video_render_functions.php';
/**
 * Created by PhpStorm.
 * User: Wai
 * Functions called are done by Ben (functions in video_render_functions.php)
 * Date: 5/12/2017
 * Time: 2:07 AM
 */

if($pa == $argv[2]){
    $vID = $argv[1];

    $connection = connect_db(\dbUsername, \dbPassword, \dbDBname);

    $query = "SELECT fps FROM videos WHERE vid = '" . $vID . "'";
    $result = pg_query($query);
    $arr = pg_fetch_all($result);
    $fps = $arr[0][fps];

    vsplit($vID, $fps);
    //eyeTrack and openFace are forked here
    $pid = pcntl_fork();
    if($pid == -1){
        error_log('Problem forking, VID - '.$vID);
        return false;
    }else if ($pid){
        eyeTrack($vID);
    }else{
        openFace($vID);
        openCv($vID);
        imagesToVideo($vID);
    }

}else{
    error_log('video_render.php accessed without post');
    ?>
    <script lang="javascript">
        window.location.href = "/index.php";
    </script>
    <?php
}
?>