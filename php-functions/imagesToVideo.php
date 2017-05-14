<?php
/*
	Call FFMPEG to turn images into video
	@param vid Video Id 
*/

//TODO change output_%04d to match what we have

$root_loc = "/var/www/html/CS160_FiveGuysOneCode";

function imagesToVideo($videoID){

global $root_loc;

    $VID_DIR = $root_loc.'/vids/'.$videoID.'/finished_frames/';

    shell_exec('sudo ffmpeg -i ' . $VID_DIR . 'output_%04d.png ' . $VID_DIR . 'out.mp4 2>&1');

}

//Test
imagesToVideo('vid2');

