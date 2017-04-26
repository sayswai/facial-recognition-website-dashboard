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
$vtypes = array("mp4", "mpg", "mov", "m4a", "3gp", "3g2", "mj2");
$metaOutput = "mov,mp4,m4a,3gp,3g2,mj2";
$upload_dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/raw_upload/';
$MAX_FILE_SIZE = 500000000; // in bytes


/*Functions*/
/**
 * ExtensionName = extName
 * Validates whether the inputted file extension is any of the acceptable one listed in $vtypes using filename
 * @param $x inputted filename
 * @return bool true if file is any of the extensions listed in $vtypes
 */
    function extName($x) {
        global $vtypes;
        if (in_array(pathinfo($x, PATHINFO_EXTENSION), $vtypes)){ //simple name extension check, if this fails, no reason to continue
            return true;
        }
        return false;
    }


/**
 * @param $x video id
 * needed metadata:
 * vid, uid, framecount, width, height, fps
 * @return true if ffprobe returns a true video else false
 */
    function metaCheck($x) {
        /*ffprobe $x 2>&1 | grep -oP "Input #0, \K.*(?=, from)"
        * output: mov,mp4,m4a,3gp,3g2,mj2
        */
        global $metaOutput;
        $compr = trim(shell_exec('ffprobe "'.$x.'" 2>&1 | grep -oP "Input #0, \K.*(?=, from)"'));
        if (strcmp($compr, $metaOutput) == 0)
        {
            return true;
        }
        return false;
    }

/**
 * @param $filename
 * @param $filedir
 */
    function metaExtract($filename, $filedir) {
        //TODO fetch user information from session and store information accordingly to user's ID
        shell_exec('ffprobe "'.$filename.'" 2>&1 | grep -oP "Input #0, \K.*(?=, from)"');
        echo shell_exec('echo $?');
        return true;
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



/*Post*/
    /**Upload**/
    if( isset($_POST["submit"]) ) {
        $filename = basename($_FILES["userUpload"]["name"]);
        $filedir = $upload_dir . $filename;

        /**File name extension check**/
        if (extName($filedir)){ // Checks for appropriate Format
            echo "file extension passed" . PHP_EOL;
            /**File size check**/
            if ($_FILES["userUpload"]["size"] < $MAX_FILE_SIZE) { // checks file size, set file size in bytes
                echo "file size passed" . PHP_EOL;
                /**Uploading**/
                //TODO webserver's apache settings limit file size uploads to 2M, find a way to change this
                if (move_uploaded_file($_FILES["userUpload"]["tmp_name"], $filedir)) {
                    echo "file uploaded passed" . PHP_EOL;
                    /**Meta data check**/
                    if(metaCheck($filedir)) { //Check file video integrity
                        /**Extract meta data and store to DB**/
                        if(metaExtract($filename, $filedir)) {
                            echo $filename . " successfully uploaded";
                        }else{
                            echo "Something went wrong";
                        }
                    }else{
                        echo "Good try trying to disguise this file as a video format...";
                        unlink($filedir);
                    }
                }else{
                    echo "Upload failed";
                }
            }else{
                echo "File size exceeds " . $MAX_FILE_SIZE / 1000000 . "MB";
            }
        }else{
            echo "Invalid Format; Accepted Formats: <br>";
            foreach ($vtypes as $formats){
                echo "." . $formats . " ";
            }

        }
    }






    //To get metadata we should use: ffprobe input.mp4

?>

<body>
<form method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="userUpload" id="userUpload"/>
    <input type="submit" name="submit" value="Upload Image"/>
</form>
</body>
