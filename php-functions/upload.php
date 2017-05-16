<?php
session_start();
include 'db_connect.php';
include '../configs/Config.php';

/**
 * Created by PhpStorm.
 * User: Wai
 * Date: 3/29/2017
 * Time: 1:16 PM
 * Purpose:
 *  Uploading video to webserver
 */


/* Global Variables*/
#$V_TYPES = array("mp4", "mpg", "mov", "m4a", "3gp", "3g2", "mj2");
$V_TYPES = array("mp4", "mpg", "mov", "mpeg", "avi", "wmv");
$META_OUTPUT = array("mov,mp4,m4a,3gp,3g2,mj2", "mpeg", "avi", "asf");
                        # "mov, mp4.."   "mpg, mpeg", "avi", "wmv"
$UPLOAD_DIR = $_SERVER['DOCUMENT_ROOT'].'/uploads/raw_upload/';
$VID_DIR = $_SERVER['DOCUMENT_ROOT'].'/vids/';
$MAX_FILE_SIZE = 10000000; // in bytes


/*Functions*/
/**
 * ExtensionName check
 * Validates whether the inputted file extension is any of the acceptable one listed in $vtypes using filename
 * @param $x inputted filename
 * @return bool true if file is any of the extensions listed in $vtypes
 */
function extName($x) {
    global $V_TYPES;
    if (in_array(pathinfo($x, PATHINFO_EXTENSION), $V_TYPES)){ //simple name extension check, if this fails, no reason to continue
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
    global $META_OUTPUT;
    $compr = trim(shell_exec('ffprobe "'.$x.'" 2>&1 | grep -oP "Input #0, \K.*(?=, from)"'));
    if (in_array($compr, $META_OUTPUT))
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

    /*
     * vid
     * uid
     * framecount integer
     * width integer
     * height integer
     * fps integer
     */
    global $VID_DIR;
    $output = json_decode(shell_exec('ffprobe -v quiet -print_format json -show_format -show_streams "'.$filedir.'" '), true);

    $framecount = $output["streams"]["0"]["nb_frames"];
    if ($framecount == ''){
        $framecount = shell_exec('ffprobe -v error -count_frames -select_streams v:0 -show_entries stream=nb_read_frames -of default=nokey=1:noprint_wrappers=1 "'.$filedir.'"');
    }
    $width = $output["streams"]["0"]["width"];
    $height = $output["streams"]["0"]["height"];
    $fps = (int)$output["streams"]["0"]["avg_frame_rate"];
    if ($fps > 100)  {
        $fps = (int)(trim(shell_exec('ffprobe "'.$filedir.'"  2>&1 | grep -oP "s, \K.*(?=fps,)"')));
    }
    $vtitle = $filename;
    $duration = $output["format"]["duration"];
    $size = $output["format"]["size"];
    $time = time();

    $vid = crc32("V:" . $vtitle . "D:" . $duration . "S" . $size . "T" . $time);
    $uid = $_SESSION['uid'];

    /*Finalize
    -Create folder: /root/vids/(vid)/
    -move uploaded video and renames it to "main": /root/vids/(vid)/main.mp4
    -save info in db
    */
    $newdir = $VID_DIR.$vid;
    $exten = pathinfo($filedir, PATHINFO_EXTENSION);

    shell_exec('mkdir "'.$newdir.'" && mv "'.$filedir.'" "'.$newdir.'"/main."'.$exten.'"');
    if (shell_exec('echo $?') != 0){
        return false;
    }
    if (strcmp($exten, 'mp4') != 0){ //for video display
        shell_exec('ffmpeg -i ' .$newdir. '/main.* -strict -2 -c:v libx264 -preset ultrafast ' .$newdir. '/main.mp4 && > ' .$newdir. '/done_mp4');
    }else{
        shell_exec(' > ' .$newdir. '/done_mp4');
    }
    //TODO  Very poor db control here, edit when you have the chance
    $connection = connect_db(\dbUsername, \dbPassword, \dbDBname);
    $query = "INSERT INTO videos (vtitle, vid, uid, framecount, width, height, fps, time_upload) VALUES ('".$vtitle."', $vid, $uid, $framecount, $width, $height, $fps, $time)"; //TODO needs to be tested on umy server
    $result = pg_query($query);
    $query = "SELECT uservids FROM users WHERE uid = '" . $uid . "'";
    $result1 = pg_query($query);
    if (pg_fetch_all($result1)[0][uservids] == ''){
        $query = "UPDATE users SET uservids = '{" . $vid . "}' WHERE uid = '". $uid ."'";
    }else{
        $query = "UPDATE users SET uservids = '{" . $vid . "}' || uservids WHERE uid = '" . $uid . "'";
    }
    $query .= "; UPDATE users SET uploaded = uploaded + 1 WHERE uid = '" . $uid . "'";
    $result2 = pg_query($query);
    pg_close($connection);
    if ($result == false || $result1 == false || $result2 == false){
        //if db query fails, everything fails; remove created directory
        shell_exec('rm -rf "' .$newdir.'"');
        return false;
    }

    /*FETCH ViDS FROM USER:
    $query = "SELECT uservids FROM users WHERE username = '" . $username . "'";
    $result = pg_query($query);
    $arr = pg_fetch_all($result);
    preg_match_all('/{(.*?)}/', $arr[0][uservids], $matches);
    echo $matches[1][0];
    */
    return $vid;
}



/*Post*/
/**Upload**/
if(isset($_POST["submit"]) && isset($_SESSION['username']) && isset($_SESSION['uid'])) {
    $filename = basename($_FILES["userUpload"]["name"]);
    $filedir = $UPLOAD_DIR . $filename;

    /**File name extension check**/
    if (extName($filedir)){ // Checks for appropriate Format
            /**Uploading**/
            if (move_uploaded_file($_FILES["userUpload"]["tmp_name"], $filedir)) {
                /**Meta data check**/
                if(metaCheck($filedir)) { //Check file video integrity
                    /**Extract meta data and store to DB**/
                    $vid = metaExtract($filename, $filedir);
                    if($vid != false) {
                        echo $vid;
                        return true;
                        #$result = $filename . " successfully uploaded";
                    }else{
                        unlink($filedir);
                        echo "1"; // returns 1"Something went wrong saving to DB, upload failed."
                        error_log("db update for video upload failed");
                        #$result = "Upload failed, try again!";
                    }
                }else{
                    unlink($filedir);
                    /* returns 2  "Changing the file extension in the filename won't work here. <br>An infraction has been added to your account. <br>One more and you'll be banned."*/
                    echo "2";
                    error_log("user ". $_SESSION['username']. "disguised item as movie and tried to upload");
                    $conn = connect_db(\dbUsername, \dbPassword, \dbDBname);
                    $query = "UPDATE users SET infraction = infraction + 1 WHERE uid = '" . $_SESSION['uid'] . "'";
                    pg_query($query);
                    pg_close($conn);
                }
            }else{
                echo "3"; // return 3 Something went wrong with the upload, please try again.
                error_log("Upload failed, apache related");
            }
    }else{
        error_log("user uploaded non-video flie");

    }
}else{
    error_log('upload.php accessed without post');


}?>
<script lang="javascript">
window.location.href = "/index.php";
    </script>
    <?php






//To get metadata we should use: ffprobe input.mp4

?>
