<?php
/**
 * Created by PhpStorm.
 * User: Wai
 * Date: 3/29/2017
 * Time: 1:16 PM
 * Purpose:
 *  Uploading video to webserver
 */

/* Global Variables*/
$vtypes = array("mp4", "mpg", "mov");
$upload_dir = $_SERVER['DOCUMENT_ROOT'].'\uploads\raw_upload';




/*Functions*/
/**
 * Validates whether the inputted file extension is any of the acceptable one listed in $vtypes using filename
 * @param $x inputted filename
 * @return bool true if file is any of the extensions listed in $vtypes
 */
    function isVideo($x) {
        global $vtypes;
        if (in_array(pathinfo($x, PATHINFO_EXTENSION), $vtypes)){
            return true;
        }
        return false;
    }
    echo $upload_dir;


?>

