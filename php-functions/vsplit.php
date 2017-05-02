<?php

/*
 * ffmpeg -i input.mov -r 0.25 output_%04d.png
 * ffmpeg -i main.* -r 1 output%04d.png </dev/null >/dev/null 2>&1 &
 */


function vsplit($vid, $fps) {
    $VID_DIR = $_SERVER['DOCUMENT_ROOT'].'/vids/'.$vid.'/';
    $VID_SPLIT_DIR = $VID_DIR.'split/';
    $V = $VID_DIR.'main.*';

    mkdir($VID_SPLIT_DIR);
    shell_exec('ffmpeg -i ' .$V. ' -r ' .$fps. ' ' .$VID_SPLIT_DIR.'/split_%04d.png </dev/null >/dev/null 2>&1 &');

    $connection = connect_db('postgres', '1', 'CS160');
    $query = "UPDATE videos SET split = 1 WHERE vid = " . $vid . ";";
    $result = pg_query($query);
    pg_close($connection);
}

?>