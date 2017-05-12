<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 4/26/2017
 * Time: 6:48 PM
 */
include 'db_connect.php';
include '../configs/Config.php';
include  'helper.php';


//Database Connection to Postgresql.
$conn1 = connect_db(\dbUsername, \dbPassword, \dbDBname);


/*
 * Insert dummy values into database for testing
 */
function insertDummyVideo()
{
    $sql = 'INSERT INTO Videos (vID,uID,frameCount,width,height,fps) VALUES (1, 2, 3, 4, 5, 6);';
    pg_query($sql);
}

/*
 * Get the fps of video from DB
 *
 * @param $videoID
 * @return fps
 * */
function getFPS($videoID)
{
    global $conn1;
    pg_prepare($conn1, "get_fps", 'SELECT fps FROM Videos WHERE vID = $1');
    $results = pg_execute($conn1, "get_fps", array($videoID));
    $myarray = pg_fetch_all($results)[0];
    return $myarray['fps'];
}

/*
 * Get the frame count of video from DB
 *
 * @param $videoID
 * @return frameCount
 * */
function getFrameCount($videoID)
{
    global $conn1;
    pg_prepare($conn1, "get_frameCount", 'SELECT frameCount FROM Videos WHERE vID = $1');
    $results = pg_execute($conn1, "get_frameCount", array($videoID));
    $myarray = pg_fetch_all($results)[0];
    return $myarray['frameCount'];
}

/*
 * Insert pupil coordinate into DB
 *
 * @param $videoID
 * @param $frameNum
 * @param $rightX X-coord of right eye
 * @param $rightY Y-coord of right eye
 * @param $leftX X-coord of left eye
 * @param $leftY Y-coord of left eye
 */
function insertEyeCoordinate($videoID, $frameNum, $rightX, $rightY, $leftX, $leftY)
{
    global $conn1;
    pg_prepare($conn1, "insert_eyeCoordinate", 'INSERT INTO Eye (vid, framenum, rightpupilx, rightpupily, leftpupilx, leftpupily) VALUES ($1, $2, $3, $4, $5, $6)');
    pg_execute($conn1, "insert_eyeCoordinate", array($videoID, $frameNum, $rightX, $rightY, $leftX, $leftY));

}

/*
 * Insert 68 points into the DB
 * @param $videoID
 * @param $frameNum
 * @param $arrayOfPoints
 * */
function insertPoints($videoID, $frameNum, $arrayOfPoints)
{
    global $conn1;

    $arrayOfPoints = commaSepArray($arrayOfPoints);

    pg_query($conn1, "DEALLOCATE ALL");

    pg_prepare($conn1, "insert_points", 'INSERT INTO OpenFace (vid, framenum, 
point1,
point2,
point3,
point4,
point5,
point6,
point7,
point8,
point9,
point10,
point11,
point12,
point13,
point14,
point15,
point16,
point17,
point18,
point19,
point20,
point21,
point22,
point23,
point24,
point25,
point26,
point27,
point28,
point29,
point30,
point31,
point32,
point33,
point34,
point35,
point36,
point37,
point38,
point39,
point40,
point41,
point42,
point43,
point44,
point45,
point46,
point47,
point48,
point49,
point50,
point51,
point52,
point53,
point54,
point55,
point56,
point57,
point58,
point59,
point60,
point61,
point62,
point63,
point64,
point65,
point66,
point67,
point68
) 
VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17, $18, $19, $20, $21, $22, $23, $24, $25,
 $26, $27, $28, $29, $30, $31, $32, $33, $34, $35, $36, $37, $38, $39, $40, $41, $42, $43, $44, $45, $46, $47, $48, $49, $50, 
 $51, $52, $53, $54, $55, $56, $57, $58, $59, $60, $61, $62, $63, $64, $65, $66, $67, $68, $69, $70)');

    pg_execute($conn1, "insert_points", array($videoID, $frameNum,
        $arrayOfPoints[1], $arrayOfPoints[2], $arrayOfPoints[3], $arrayOfPoints[4],
        $arrayOfPoints[5],$arrayOfPoints[6],$arrayOfPoints[7],$arrayOfPoints[8],
    $arrayOfPoints[9],$arrayOfPoints[10],$arrayOfPoints[11],$arrayOfPoints[12],
$arrayOfPoints[13],$arrayOfPoints[14],$arrayOfPoints[15],$arrayOfPoints[16],
$arrayOfPoints[17],$arrayOfPoints[18],$arrayOfPoints[19],$arrayOfPoints[20],
$arrayOfPoints[21],$arrayOfPoints[22],$arrayOfPoints[23],$arrayOfPoints[24],
$arrayOfPoints[25],$arrayOfPoints[26],$arrayOfPoints[27],$arrayOfPoints[28],
$arrayOfPoints[29],$arrayOfPoints[30],$arrayOfPoints[31],$arrayOfPoints[32],
$arrayOfPoints[33],$arrayOfPoints[34],$arrayOfPoints[35],$arrayOfPoints[36],
$arrayOfPoints[37],$arrayOfPoints[38],$arrayOfPoints[39],$arrayOfPoints[40],
$arrayOfPoints[41],$arrayOfPoints[42],$arrayOfPoints[43],$arrayOfPoints[44],
$arrayOfPoints[45],$arrayOfPoints[46],$arrayOfPoints[47],$arrayOfPoints[48],
$arrayOfPoints[49],$arrayOfPoints[50],$arrayOfPoints[51],$arrayOfPoints[52],
$arrayOfPoints[53],$arrayOfPoints[54],$arrayOfPoints[55],$arrayOfPoints[56],
$arrayOfPoints[57],$arrayOfPoints[58],$arrayOfPoints[59],$arrayOfPoints[60],
$arrayOfPoints[61],$arrayOfPoints[62],$arrayOfPoints[63],$arrayOfPoints[64],
$arrayOfPoints[65],$arrayOfPoints[66],$arrayOfPoints[67],$arrayOfPoints[68]
));

}
