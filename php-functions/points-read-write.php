<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 5/3/2017
 * Time: 11:12 PM
 */

include 'queries.php';

$fileName = "../vids/fakeVideo/detected_frames/output_0003_det_0.pts";

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

    //Each index is two coordinates
    $arrayPoints = explode("\n", $curlyArray[0][0]);

    //print_r($arrayPoints);

    return $arrayPoints;
}
//Test
//getArrayPoints($fileName);

$arrayOfPoints = getArrayPoints($fileName);
insertPoints(20,20,$arrayOfPoints);