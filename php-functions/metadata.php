<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 4/26/2017
 * Time: 6:48 PM
 */
include 'db_connect.php';
include '../configs/Config.php';

//Database Connection to Postgresql.
$conn1 = connect_db(\dbUsername, \dbPassword, \dbDBname);


/*
 * Insert dummy values into database for testing
 */
function insertDummyVideo(){
    $sql = 'INSERT INTO Videos (vID,uID,frameCount,width,height,fps) VALUES (1, 2, 3, 4, 5, 6);';
    pg_query($sql);
}

/*
 * Get the fps of video from DB
 *
 * @param $videoID
 * @return fps
 * */
function getFPS($videoID)
{
    global $conn1;
    pg_prepare($conn1, "get_fps", 'SELECT fps FROM Videos WHERE vID = $1');
    $results = pg_execute($conn1, "get_fps", array($videoID));
    $myarray = pg_fetch_all($results)[0];
    return $myarray['fps'];
}

/*
 * Get the frame count of video from DB
 *
 * @param $videoID
 * @return frameCount
 * */
function getFrameCount($videoID)
{
    global $conn1;
    pg_prepare($conn1, "get_frameCount", 'SELECT frameCount FROM Videos WHERE vID = $1');
    $results = pg_execute($conn1, "get_frameCount", array($videoID));
    $myarray = pg_fetch_all($results)[0];
    return $myarray['frameCount'];
}