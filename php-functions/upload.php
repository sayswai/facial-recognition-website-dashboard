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
$vtypes = array("mp4", "mpg", "mov", "doc");
$upload_dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/raw_upload/';


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

/**
 * WIll checl $upload_dir to see if the file exists already
 * @param $x filename
 * @return bool true if file exists, else false
 */
    function exists($x) {
        #global $upload_dir;
        #if(file_exists($upload_dir . $x)){
        if (file_exists($x)) {
            return true;
        }
        return false;
    }
/*
    function checkSize($x) {
        return null;
        //TODO implement file size checker
    }
*/


/*Post*/
    if( isset($_POST["submit"]) ) {
        $filename = $upload_dir . basename($_FILES["userUpload"]["name"]);
        echo "<br>". $filename . "<br>";

        if (isVideo($filename)){ // Checks for appropriate Format
            if (!exists($filename)) {// Checks if file exists
                if (move_uploaded_file($_FILES["userUpload"]["tmp_name"], $filename)){
                    echo "File successfully uploaded";
                }
            }else{
                echo "File already exists";
            }
        }else{
            echo "Invalid Format; Accepted Formats: <br>";
            foreach ($vtypes as $formats){
                echo "." . $formats . " ";
            }

        }

    }
?>

<body>
<form method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="userUpload" id="userUpload"/>
    <input type="submit" name="submit" value="Upload Image"/>
</form>
</body>
