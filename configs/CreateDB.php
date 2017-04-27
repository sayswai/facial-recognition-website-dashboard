<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 4/26/2017
 * Time: 6:23 PM
 */

include '../php-functions/db_connect.php';
include 'Config.php';


//Database Connection to Postgresql.
$conn1 = connect_db(\dbUsername, \dbPassword, \dbDBname);

$sqlList = [

    'CREATE TABLE IF NOT EXISTS Users (
      uID BIGSERIAL,
      username  CHAR(50) NOT NULL UNIQUE,
      password CHAR(128) NOT NULL,
      firstName VARCHAR(50) NOT NULL,
      lastName VARCHAR(50) NOT NULL,
      IP VARCHAR(39) NOT NULL,
      PRIMARY KEY (uID)
    )',

    'CREATE TABLE IF NOT EXISTS Videos (
      vID bigserial,
      uID int not null,
      frameCount int not null,
      width int not null,
      height int not null,
      fps int not null,
      PRIMARY KEY (vID)
    )',

    'CREATE TABLE IF NOT EXISTS Eye (
      vID bigserial,
      rightEye POINT,
      leftEye POINT,
      PRIMARY KEY (vID)
    )',

    'CREATE TABLE IF NOT EXISTS OpenFace (
      vID INT NOT NULL,
      frameNum INT NOT NULL,
      yaw INT NOT NULL,
      pitch INT NOT NULL,
      roll INT NOT NULL,
      openfaceLeftPupil POINT NOT NULL,
      openfaceRightPupil POINT NOT NULL,
      fabiantimmLeftPupil POINT NOT NULL,
      fabiantimmRightPupil POINT NOT NULL,
      point1 POINT NOT NULL,
      point2 POINT NOT NULL,
      point3 POINT NOT NULL,
      point4 POINT NOT NULL,
      point5 POINT NOT NULL,
      point6 POINT NOT NULL,
      point7 POINT NOT NULL,
      point8 POINT NOT NULL,
      point9 POINT NOT NULL,
      point10 POINT NOT NULL,
      point11 POINT NOT NULL,
      point12 POINT NOT NULL,
      point13 POINT NOT NULL,
      point14 POINT NOT NULL,
      point15 POINT NOT NULL,
      point16 POINT NOT NULL,
      point17 POINT NOT NULL,
      point18 POINT NOT NULL,
      point19 POINT NOT NULL,
      point20 POINT NOT NULL,
      point21 POINT NOT NULL,
      point22 POINT NOT NULL,
      point23 POINT NOT NULL,
      point24 POINT NOT NULL,
      point25 POINT NOT NULL,
      point26 POINT NOT NULL,
      point27 POINT NOT NULL,
      point28 POINT NOT NULL,
      point29 POINT NOT NULL,
      point30 POINT NOT NULL,
      point31 POINT NOT NULL,
      point32 POINT NOT NULL,
      point33 POINT NOT NULL,
      point34 POINT NOT NULL,
      point35 POINT NOT NULL,
      point36 POINT NOT NULL,
      point37 POINT NOT NULL,
      point38 POINT NOT NULL,
      point39 POINT NOT NULL,
      point40 POINT NOT NULL,
      point41 POINT NOT NULL,
      point42 POINT NOT NULL,
      point43 POINT NOT NULL,
      point44 POINT NOT NULL,
      point45 POINT NOT NULL,
      point46 POINT NOT NULL,
      point47 POINT NOT NULL,
      point48 POINT NOT NULL,
      point49 POINT NOT NULL,
      point50 POINT NOT NULL,
      point51 POINT NOT NULL,
      point52 POINT NOT NULL,
      point53 POINT NOT NULL,
      point54 POINT NOT NULL,
      point55 POINT NOT NULL,
      point56 POINT NOT NULL,
      point57 POINT NOT NULL,
      point58 POINT NOT NULL,
      point59 POINT NOT NULL,
      point60 POINT NOT NULL,
      point61 POINT NOT NULL,
      point62 POINT NOT NULL,
      point63 POINT NOT NULL,
      point64 POINT NOT NULL,
      point65 POINT NOT NULL,
      point66 POINT NOT NULL,
      point67 POINT NOT NULL,
      point68 POINT NOT NULL,
      PRIMARY KEY(vID, frameNum)
    );'];

// execute each sql statement to create new tables
foreach ($sqlList as $sql) {
    pg_query($sql);
}