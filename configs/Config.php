<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 4/26/2017
 * Time: 6:33 PM
 */

/*
Host
*/
define("dbHost", "localhost");

/*
Username
*/
define("dbUsername","postgres");

/*
Password
*/
define("dbPassword", "umyserver");

/*
Database name
*/
define("dbDBname","cs160");

/*
 * OpenFace Command
 * To get sudo to work on apache I had to call sudo visudo and add www-data ALL=NOPASSWD: ALL
 * I had to add the sudo because without it the program did not have the permissions to write point files
 *
 * Change this line to where OpenFace FaceLandmarkImg is installed
 */
define("cmdOpenFace", "sudo /home/ben/Downloads/OpenFace/build/bin/FaceLandmarkImg");

/*
 * EyeLike Command
 *
 * Change this line to where EyeLike is installed
 * */
define("cmdEyeLike","../eyeLike/build/bin/eyeLike");
