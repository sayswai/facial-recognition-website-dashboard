<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 5/3/2017
 * Time: 11:39 PM
 */

/*
 * OpenFace command
 *
 * Usage:  ./OpenFace/build/bin/FaceLandmarkImg -fdir "../videos/" -ofdir "./demo_img/" -q
 *
 * -q prevents window boxes from popping up (pop ups give errors)
 *
 * To get sudo to work on apache I had to call sudo visudo and add www-data ALL=NOPASSWD: ALL
 * I had to add the sudo because without it the program did not have the permissions to write point files
 *
 * Change this line to where OpenFace FaceLandmarkImg is installed
 * */
$openFaceCommand = "sudo /home/ben/Downloads/OpenFace/build/bin/FaceLandmarkImg";

//Directory of split images
$splitImgDirectory = "../vids/fakeVideo/split_frames/";

//Directory where detected landmarks should be written
$ofDir = "../vids/fakeVideo/detected_frames/";

//Test
$test = 'sudo /home/ben/Downloads/OpenFace/build/bin/FaceLandmarkImg -fdir "../vids/fakeVideo/split_frames/" -ofdir "../vids/fakeVideo/detected_frames/" -q 2>&1';
;


/*
 * Call OpenFace to detected points and save the points into a directory
 *
 * @param $splitImgDirectory directory where the split images are stored
 * @param $ofDir directory where the landmark points will be written
 * */
function openFace($splitImgDirectory, $ofDir){

    global $openFaceCommand;

    $result = shell_exec($openFaceCommand . " -fdir " . '"'. $splitImgDirectory . '"' . " -ofdir " . '"' . $ofDir . '" -q 2>&1');

}

//Test
openFace($splitImgDirectory, $ofDir);


