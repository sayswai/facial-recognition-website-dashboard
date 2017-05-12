<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 5/3/2017
 * Time: 11:39 PM
 */

include "../configs/Config.php";
/*
 * /home/cs160/openFace/build/bin$ ./FaceLandmarkImg -fdir "/mnt/c/cs160/vids/2457131084/split/" -ofdir "/mnt/c/cs160/vids/2457131084/detected_frames" -q 2>&1
 */
/*
 * OpenFace command
 *
 * Usage:  ./OpenFace/build/bin/FaceLandmarkImg -fdir "../videos/" -ofdir "./demo_img/" -q
 *
 * -fdir directory of frames
 *
 * -ofdir output directory of points
 *
 * -q prevents window boxes from popping up (pop ups give errors)
 *
 * */
//$openFaceCommand = "sudo /home/ben/Downloads/OpenFace/build/bin/FaceLandmarkImg";
$openFaceCommand = \cmdOpenFace;

//Example directory of split images
//$splitImgDirectory = "../vids/fakeVideo/split_frames/";

//Example directory where detected landmarks should be written
//$ofDir = "../vids/fakeVideo/detected_frames/";

//Test
//$test = 'sudo /home/ben/Downloads/OpenFace/build/bin/FaceLandmarkImg -fdir "../vids/fakeVideo/split_frames/" -ofdir "../vids/fakeVideo/detected_frames/" -q 2>&1';

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

//Example function call
//openFace($splitImgDirectory, $ofDir);


