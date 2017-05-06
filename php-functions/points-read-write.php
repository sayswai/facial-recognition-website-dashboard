<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 5/3/2017
 * Time: 11:12 PM
 */

include 'queries.php';

//Example file name
//$fileName = "../vids/fakeVideo/detected_frames/output_0003_det_0.pts";

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

//Example directory
//$dirName = "../vids/fakeVideo/detected_frames/";

/*
 * Parse the points outputted by OpenFace and insert them into the database
 *
 * @param $directoryOfPoints directory where the detected frames are stored
 * @param $videoID video id of the video
 * */
function parsePointFilesAndInsert($directoryOfPoints, $videoID){

    // Get all the point files in the directory
    $pointFilesArray = scandir($directoryOfPoints);

    for($i = 2; $i < sizeof($pointFilesArray); $i++){

        $fileName = $pointFilesArray[$i];
        // Store the point file into an array
        $arrayOfPoints = getArrayPoints($directoryOfPoints . '/' . $fileName);

        //$stripFileName = str_replace("output_", "", $fileName);
        //$stripFileName = str_replace("_det_0.pts", "", $stripFileName);

        // Get the frame number from the file name
        preg_match_all('/output_(.*?)_det_/', $fileName, $out);

        $stripFileName = $out[1][0];

        // Remove leading zeros
        $frameNum = ltrim($stripFileName, '0');

        insertPoints($videoID, $frameNum,$arrayOfPoints);


    }
    
}

