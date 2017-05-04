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
 * */
$openFaceCommand = "./OpenFace/build/bin/FaceLandmarkImg";

//Directory of split images
$splitImgDirectory = "";

//Directory where detected landmarks should be written
$ofDir = "";

/*
 * Call OpenFace to detected points and save the points into a directory
 * */
function openFace($splitImgDirectory, $ofDir){

    global $openFaceCommand;

    $result = shell_exec($openFaceCommand . " -fdir " . "\"". $splitImgDirectory ."\"" . "-ofdir" . "\"" . $ofDir . "\"");

}

/*
 * Parse .pts file and write the points into the database
 * */
function writePoint($ptsFile){

    $myfile = fopen($ptsFile, "r") or die("Unable to open file!");
    echo fread($myfile,filesize($ptsFile));
    fclose($myfile);

}