<?php
include 'queries.php';
include "../configs/Config.php";
/**
 * Created by PhpStorm.
 * Functions created by Ben
 * Date: 5/12/2017
 * Time: 2:32 AM
 */

/* VARIABLES */
$eyeTrackCommand = \cmdEyeLike;
$openFaceCommand = \cmdOpenFace;
$pa = \clP;
$root_loc = \cliR;


/* EYE TRACK START */
/*
 * Execute eyeLike program to get the left and right pupil coordinates, then store into the database
 *
 *@param $splitImgDirectory the directory where the split images are stored
 * @param $videoID videoID of the split images
 * */
function eyeTrack($videoID)
{
    global $eyeTrackCommand, $root_loc;
    $splitImgDirectory = $root_loc.'/vids/'.$videoID.'/split';
    /*
    //  Get video ID
    $fileStructure = explode("/",$splitImgDirectory);
    $videoID = $fileStructure[2];
    */

    // Get all files in directory and store to array
    $splitImagesArray = scandir($splitImgDirectory);

    for($splitImgCount = 2; $splitImgCount < sizeof($splitImagesArray); $splitImgCount++){


        // Call eye track command here
        $result = shell_exec($eyeTrackCommand . " " . $splitImgDirectory ."/". $splitImagesArray[$splitImgCount] . " 2>&1");

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
/* EYE TRACK END */

/* OPENFACE START */
/*
 * Call OpenFace to detected points and save the points into a directory
 *
 * @param $splitImgDirectory directory where the split images are stored
 * @param $ofDir directory where the landmark points will be written
 * */
function openFace($vID){

    global $openFaceCommand, $root_loc;
    $SPLIT_DIR = $root_loc.'/vids/'.$vID.'/split/';
    $DET_DIR = $root_loc.'/vids/'.$vID.'/detected_frames/';

    $result = shell_exec($openFaceCommand . " -fdir " . '"'. $SPLIT_DIR . '"' . " -ofdir " . '"' . $DET_DIR . '" -q 2>&1');
}

/*
 * Function to extract the 68 points into an array
 *
 * @param $fileName the location/name of where the point file is
 * @return $arrayPoints an array with the 68 points
 * */
function getArrayPoints($fileName)
{
    // Contents of file into a string
    $fileContent = file_get_contents($fileName);
    // Put everything in a curly brace into an array
    preg_match_all('/{([^}]*)}/', $fileContent, $curlyArray);

    //Each index is x and y coordinates
    $arrayPoints = explode("\n", $curlyArray[0][0]);

    //print_r($arrayPoints);

    return $arrayPoints;
}

/*
 * Parse the points outputted by OpenFace and insert them into the database
 *
 * @param $directoryOfPoints directory where the detected frames are stored
 * @param $videoID video id of the video
 * */
function parsePointFilesAndInsert($videoID){
    global $root_loc;
    // Get all the point files in the directory
    $directoryOfPoints = $root_loc.'/vids/'.$videoID.'/detected_frames/';
    $pointFilesArray = scandir($directoryOfPoints);

    for($i = 2; $i < sizeof($pointFilesArray); $i++){

        $fileName = $pointFilesArray[$i];
        // Store the point file into an array
        $arrayOfPoints = getArrayPoints($directoryOfPoints . $fileName);

        //$stripFileName = str_replace("split_", "", $fileName);
        //$stripFileName = str_replace("_det_0.pts", "", $stripFileName);

        // Get the frame number from the file name
        preg_match_all('/split_(.*?)_det_/', $fileName, $out);

        $stripFileName = $out[1][0];

        // Remove leading zeros
        $frameNum = ltrim($stripFileName, '0');

        insertPoints($videoID, $frameNum,$arrayOfPoints);


    }

}
/* OPENFACE END */
?>