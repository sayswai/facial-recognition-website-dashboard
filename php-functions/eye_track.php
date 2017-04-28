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

$splitImgDirectory = "../vids/fakeVideo/split_frames/";

// Eye Track command usage ./bin/eyeLike <img>
$eyeTrackCommand = "../eyeLike/build/bin/eyeLike";

$test = '../eyeLike/build/bin/eyeLike ../vids/fakeVideo/split_frames/output_0004.png 2>&1';

function eyeTrack($splitImgDirectory)
{
    global $eyeTrackCommand;
    global $test;

    $splitImagesArray = scandir($splitImgDirectory);

    for($splitImgCount = 2; $splitImgCount < sizeof($splitImagesArray); $splitImgCount++){
        //Call eye track command here

        $result = shell_exec($eyeTrackCommand . " " . $splitImgDirectory . $splitImagesArray[$splitImgCount] . " 2>&1");
	if($result == NULL){
	echo "none detected";	
	}else{
	print_r($result);
        }
	print_r($eyeTrackCommand . " " . $splitImgDirectory . $splitImagesArray[$splitImgCount]."<br>");

    }

	$out = shell_exec($result);
	print_r($out);
	echo "testy";

}


eyeTrack($splitImgDirectory);

