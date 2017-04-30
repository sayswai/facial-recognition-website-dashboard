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

function eyeTrack($splitImgDirectory,$videoID)
{
    global $eyeTrackCommand;
    
    /*
    //  Get video ID
    $fileStructure = explode("/",$splitImgDirectory);
    $videoID = $fileStructure[2];
    */

    // Get all files in directory
    $splitImagesArray = scandir($splitImgDirectory);

    for($splitImgCount = 2; $splitImgCount < sizeof($splitImagesArray); $splitImgCount++){
       
	// Call eye track command here

        $result = shell_exec($eyeTrackCommand . " " . $splitImgDirectory . $splitImagesArray[$splitImgCount] . " 2>&1");
	if($result == NULL){
		// No coordinates detected
	}else{
		$coords = explode(",",$result);
		insertEyeCoordinate($videoID, $splitImgCount, $coords[0], $coords[1], $coords[2], $coords[3]);
		print_r($coords);
        }
	print_r($eyeTrackCommand . " " . $splitImgDirectory . $splitImagesArray[$splitImgCount]."<br>");

    }

}

eyeTrack($splitImgDirectory, 20);

