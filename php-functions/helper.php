<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 4/26/2017
 * Time: 11:58 PM
 */

/*
 * Replace all spaces with commas in each element of array
 * @param $arrayOfPoints
 * */
function commaSepArray($arrayOfPoints){
    for($i = 0; $i < sizeof($arrayOfPoints); $i++){
        $arrayOfPoints[$i] = str_replace(" ", ",", $arrayOfPoints[$i]);
    }

    return $arrayOfPoints;
}
