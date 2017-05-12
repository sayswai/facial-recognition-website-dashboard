<?php
session_start();
include '../configs/Config.php';
/**
 * Created by PhpStorm.
 * User: Wai
 * Date: 5/12/2017
 * Time: 4:02 AM
 */


$pfFolder = \cliF;
$cmd = \cliC;
$vID = 2765153684;
$p = \clP;

shell_exec('cd ' .$pfFolder. ' && ' .$cmd. ' ' .$vID. ' ' .$p. " > /dev/null 2>/dev/null &");
echo 'done';

?>