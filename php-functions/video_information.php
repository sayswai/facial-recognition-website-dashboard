<?php
session_start();
include 'db_connect.php';
include '../configs/Config.php';
/**
 * Created by PhpStorm.
 * User: Wai
 * Date: 5/12/2017
 * Time: 10:30 PM
 */
if(isset($_POST['submit']) && isset($_POST['vID']) && isset($_SESSION['uid'])){
#if(true){

    $conn = connect_db(\dbUsername, \dbPassword, \dbDBname);
    #$vID = 345702238;
    $vID = $_POST['vID'];


    $query = "SELECT vtitle, fps, width, height FROM videos WHERE vid = '".$vID."';";
    $result = pg_query($query);
    $arr = pg_fetch_all($result);
    pg_close($conn);


    #$arr[0]['progress'] = getProgress($vID);
    // $arr[0] ... [vtitle] [fps] [width] [height] [progress]
    $arr[0]['final'] = (file_exists($_SERVER['DOCUMENT_ROOT'].'/vids/'.$vID.'/final.mp4')) ? true : false;

    $js = json_encode($arr[0]);
    echo $js;
}elseif(isset($_POST['progress']) && isset($_POST['vID'])){
    echo getProgress($_POST['vID']);
}else{
    error_log('video_information.php accessed without post');
    ?>
    <script lang="javascript">
        window.location.href = "/index.php";
    </script>
    <?php
}

function getProgress($vID){
    $total = \operations;
    $VID_DIR = $_SERVER['DOCUMENT_ROOT'].'/vids/'.$vID.'/';

    $progress = glob($VID_DIR . 'done_*');
    if ($progress != false){
        $progress = count($progress);
        if ($progress >= $total){
            return 100;
        }
    }

    if (file_exists($VID_DIR.'done_split')){
        $splitFrames = glob($VID_DIR . 'split/*.png');//get # of split frames
        $splitFrames = count($splitFrames);
        $total += ($splitFrames * 2);

        $detectedFrames = glob($VID_DIR . 'detected_frames/*_det_0.pts');//get # of finished frames
        if ($detectedFrames != false){
            $detectedFrames = count($detectedFrames);
            $progress += $detectedFrames;
        }
        $finishedFrames = glob($VID_DIR . 'finished_frames/*.png');//get # of finished frames
        if ($finishedFrames != false){
            $finishedFrames = count($finishedFrames);
            $progress += $finishedFrames;
        }

    }


    return (($progress/$total)*100);
}
?>