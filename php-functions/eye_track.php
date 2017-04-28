<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 4/26/2017
 * Time: 6:38 PM
 */

include 'metadata.php';
//include '../configs/Config.php';

//Database Connection to Postgresql.

//insertDummyVideo();

$splitImgDirectory = "../vids/fakeVideo21!3/split_frames";

// Eye Track command usage ./bin/eyeLike <img>
$eyeTrackCommand = "../eyeLike/bin/eyeLike";

function eyeTrack($splitImgDirectory)
{
    global $eyeTrackCommand;

    $splitImagesArray = scandir($splitImgDirectory);

    for($splitImgCount = 2; $splitImgCount < sizeof($splitImagesArray); $splitImgCount++){
        //Call eye track command here

        exec($eyeTrackCommand . " " . $splitImagesArray[$splitImgCount]);
        //print_r($eyeTrackCommand . " " . $splitImagesArray[$splitImgCount]."<br>");

    }

}


eyeTrack($splitImgDirectory);

