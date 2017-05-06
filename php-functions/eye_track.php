<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 4/26/2017
 * Time: 6:38 PM
 */

include 'queries.php';

//Example directory
//$splitImgDirectory = "../vids/fakeVideo/split_frames/";

//Test
//$test = '../eyeLike/build/bin/eyeLike ../vids/fakeVideo/split_frames/output_0004.png 2>&1';

// Eye Track command usage ./bin/eyeLike <img>
//$eyeTrackCommand = "../eyeLike/build/bin/eyeLike";
$eyeTrackCommand = \cmdEyeLike;

/*
 * Execute eyeLike program to get the left and right pupil coordinates, then store into the database
 *
 *@param $splitImgDirectory the directory where the split images are stored
 * @param $videoID videoID of the split images
 * */
function eyeTrack($splitImgDirectory,$videoID)
{
    global $eyeTrackCommand;
    
    /*
    //  Get video ID
    $fileStructure = explode("/",$splitImgDirectory);
    $videoID = $fileStructure[2];
    */

    // Get all files in directory and store to array
    $splitImagesArray = scandir($splitImgDirectory);

    for($splitImgCount = 2; $splitImgCount < sizeof($splitImagesArray); $splitImgCount++){
       
	    // Call eye track command here
        $result = shell_exec($eyeTrackCommand . " " . $splitImgDirectory . $splitImagesArray[$splitImgCount] . " 2>&1");

        if($result == NULL){
		// No coordinates detected
	    }else{
            $coords = explode(",",$result);
		    insertEyeCoordinate($videoID, $splitImgCount - 1, $coords[0], $coords[1], $coords[2], $coords[3]);
		    //print_r($coords);
        }
	//print_r($eyeTrackCommand . " " . $splitImgDirectory . $splitImagesArray[$splitImgCount]."<br>");

    }

}

