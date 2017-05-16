<?php

include 'db_connect.php';
include "../configs/Config.php";
include 'queries.php';
/**
 * Created by PhpStorm.
 * Functions created by Ben & Wai
 * Date: 5/12/2017
 * Time: 2:32 AM
 */

/* VARIABLES */
$eyeTrackCommand = \cmdEyeLike;
$openFaceCommand = \cmdOpenFace;
$openCvCommand = \openCv;
$pa = \clP;
$root_loc = \cliR;


/* VSPLIT START */
function vsplit($vid, $fps) {
    global $root_loc;
    $VID_DIR = $root_loc.'/vids/'.$vid.'/';
    $VID_SPLIT_DIR = $VID_DIR.'split/';
    $V = $VID_DIR.'main.mp4';

    mkdir($VID_SPLIT_DIR);
    #shell_exec('ffmpeg -i ' .$V. ' -r ' .$fps. ' ' .$VID_SPLIT_DIR.'split_%04d.png </dev/null >/dev/null 2>&1 &');
    while(!file_exists($VID_DIR.'/done_mp4')){sleep(1);};//wait for mp4 conversion to finish
    shell_exec('ffmpeg -i ' .$V. ' -r ' .$fps. ' ' .$VID_SPLIT_DIR.'split_%04d.png </dev/null >/dev/null 2>&1 && > '.$VID_DIR.'done_split &');
    shell_exec('ffmpeg -i ' .$V. ' -vn -ab 256 ' .$VID_DIR. 'audio.mp3');

    $connection = connect_db(\dbUsername, \dbPassword, \dbDBname);
    $query = "UPDATE videos SET split = 1 WHERE vid = " . $vid . ";";
    $result = pg_query($query);
    pg_close($connection);
    return true;

}
/* VSPLIT END */

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
    $VID_DIR = $root_loc.'/vids/'.$videoID.'/';
    $splitImgDirectory = $root_loc.'/vids/'.$videoID.'/split/';
    while(!file_exists($VID_DIR.'/done_split')){sleep(2);};//wait for split to finish
    /*
    //  Get video ID
    $fileStructure = explode("/",$splitImgDirectory);
    $videoID = $fileStructure[2];
    */
    // Get all files in directory and store to array
    $splitImagesArray = scandir($splitImgDirectory);
    $conn1 = connect_db(\dbUsername, \dbPassword, \dbDBname);
    for($splitImgCount = 2; $splitImgCount < sizeof($splitImagesArray); $splitImgCount++){

        // Call eye track command here
        $result = shell_exec($eyeTrackCommand . " " . $splitImgDirectory . $splitImagesArray[$splitImgCount] . " 2>&1 && >".$VID_DIR."done_eye");
        if($result != NULL){
            $coords = explode(",",$result);
            insertEyeCoordinate($videoID, $splitImgCount - 1, $coords[0], $coords[1], $coords[2], $coords[3], $conn1);
        }

    }
    pg_close($conn1);

    return true;

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
    $VID_DIR = $root_loc.'/vids/'.$vID.'/';
    $SPLIT_DIR = $root_loc.'/vids/'.$vID.'/split/';
    $DET_DIR = $root_loc.'/vids/'.$vID.'/detected_frames/';

    while(!file_exists($VID_DIR.'done_split')){sleep(2);};//wait for split to finish

    $result = shell_exec($openFaceCommand . " -fdir " . '"'. $SPLIT_DIR . '"' . " -ofdir " . '"' . $DET_DIR . '" -q 2>&1 && > '.$VID_DIR.'done_openface');
    parsePointFilesAndInsert($vID);
    return true;
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
    $VID_DIR = $root_loc.'/vids/'.$videoID.'/';
    $directoryOfPoints = $root_loc.'/vids/'.$videoID.'/detected_frames/';

    while(!file_exists($VID_DIR.'done_openface')){sleep(2);};//wait for split to finish

    $pointFilesArray = scandir($directoryOfPoints);
    $conn1 = connect_db(\dbUsername, \dbPassword, \dbDBname);


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

        insertPoints($videoID,$frameNum,$arrayOfPoints,$conn1);


    }
    pg_close($conn1);
    shell_exec('> '.$VID_DIR.'done_openfacelog');
    return true;

}
/* OPENFACE END */

/* OPENCV START */
function openCv($vID){
    global $root_loc, $openCvCommand;
    $VID_DIR = $root_loc . '/vids/' .$vID. '/';

    while(!file_exists($VID_DIR.'done_openfacelog')){sleep(2);};//wait for split to finish

    $connection = connect_db(\dbUsername, \dbPassword, \dbDBname);
    $query = "SELECT * FROM openface WHERE vid = " .$vID;
    $result = pg_query($query);
    $arr = pg_fetch_all($result);
    #$query = "SELECT * FROM eye WHERE vid =" .$vID;
    #$result = pg_query($query);
   # $eyeArr = pg_fetch_all($result);
    pg_close($connection);


    $splitFiles = scandir($root_loc . '/vids/' . $vID . '/split/');
    $det_frame = 1;
    shell_exec('sudo chown -R www-data:www-data ' .$VID_DIR. '');
    shell_exec('sudo mkdir -p '.$VID_DIR.'finished_frames/');

    for($i = 2; $i < sizeof($splitFiles); $i++) {
        $frame = $i-1;
        if($arr[$det_frame-1]['framenum'] == $frame && ($det_frame - 1) < count($arr)){
            //frame is detected in both openface and eyelike
            shell_exec('sudo '.$openCvCommand.' '.$vID.' '.$frame);
            $det_frame++;
        }else{
            //frame is not detected, just move over file form split to finished_frame folder
            $outputName = str_replace('split_', 'output_', $splitFiles[$i]);
            shell_exec('cp ' .$VID_DIR. 'split/' .$splitFiles[$i]. ' '.$VID_DIR.'/finished_frames/' .$outputName);
        }
    }
    shell_exec('> ' .$VID_DIR.'done_opencv');
}
/* OPENCV END */

/* FFMPEG RECOMPILE START */
function imagesToVideo($videoID){

    global $root_loc;


    $VID_DIR = $root_loc.'/vids/'.$videoID.'/';
    $FINISHED_DIR = $VID_DIR.'finished_frames/';
    while(!file_exists($VID_DIR.'done_opencv')){sleep(1);};


    shell_exec('sudo ffmpeg -i ' . $FINISHED_DIR . 'output_%04d.png ' . $VID_DIR . 'final-nosound.mp4 2>&1 && sudo ffmpeg -i ' . $VID_DIR. 'final-nosound.mp4 -i ' . $VID_DIR . 'audio.mp3 -c:v copy -c:a aac -strict experimental ' .$VID_DIR. 'final.mp4 && >' .$VID_DIR. 'done_final');

}
/* FFMPEG RECOMPILE END */
?>