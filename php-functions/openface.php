<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 5/3/2017
 * Time: 11:39 PM
 */

/*
 * OpenFace command
 * Usage:  ./OpenFace/build/bin/FaceLandmarkImg -fdir "../videos/" -ofdir "./demo_img/" -oidir "./demo_img/"
 *
 * To get sudo to work on apache I had to call sudo visudo and add www-data ALL=NOPASSWD: ALL
 *
 * Change this line to where OpenFace FaceLandmarkImg is installed
 * */
$openFaceCommand = "sudo /home/ben/Downloads/OpenFace/build/bin/FaceLandmarkImg";

//Directory of split images
$splitImgDirectory = "../vids/fakeVideo/split_frames/";

//Directory where detected landmarks should be written
$ofDir = "../vids/fakeVideo/detected_frames/";

$test = 'sudo /home/ben/Downloads/OpenFace/build/bin/FaceLandmarkImg -fdir "../vids/fakeVideo/split_frames/" -ofdir "../vids/fakeVideo/detected_frames/" -q 2>&1';
;


/*
 * Call OpenFace to detected points and save the points into a directory
 * */
function openFace($splitImgDirectory, $ofDir){

    global $openFaceCommand;

    $result = shell_exec($openFaceCommand . " -fdir " . '"'. $splitImgDirectory . '"' . " -ofdir " . '"' . $ofDir . '" -q 2>&1');

	printf($results);
}

/*
 * Parse .pts file and write the points into the database
 * */
function writePoint($ptsFile){

    $myfile = fopen($ptsFile, "r") or die("Unable to open file!");
    echo fread($myfile,filesize($ptsFile));
    fclose($myfile);

}

openFace($splitImgDirectory, $ofDir);


