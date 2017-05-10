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
      uid bigserial not null
		constraint users_pkey
			primary key,
	username char(50) not null
		constraint users_username_key
			unique,
	password char(128) not null,
	firstname varchar(50) not null,
	lastname varchar(50) not null,
	ip varchar(39) not null,
	uservids text,
	uploaded integer default 0,
	infraction integer default 0
    )',

    'CREATE TABLE IF NOT EXISTS Videos (
      vid bigserial not null
		constraint videos_pkey
			primary key,
	uid integer not null,
	framecount integer not null,
	width integer not null,
	height integer not null,
	fps integer not null,
	vtitle text not null,
	time_upload integer not null,
	split integer default 0 not null
    )',

    'CREATE TABLE IF NOT EXISTS Eye (
     vid bigint not null,
	framenum integer not null,
	rightpupilx integer,
	rightpupily integer,
	leftpupilx integer,
	leftpupily integer,
	constraint eye_pkey
		primary key (vid, framenum)
    )',

    'CREATE TABLE IF NOT EXISTS OpenFace (
      vid bigint not null,
	framenum integer not null
		constraint openface_pkey
			primary key,
	point1 point not null,
	point2 point not null,
	point3 point not null,
	point4 point not null,
	point5 point not null,
	point6 point not null,
	point7 point not null,
	point8 point not null,
	point9 point not null,
	point10 point not null,
	point11 point not null,
	point12 point not null,
	point13 point not null,
	point14 point not null,
	point15 point not null,
	point16 point not null,
	point17 point not null,
	point18 point not null,
	point19 point not null,
	point20 point not null,
	point21 point not null,
	point22 point not null,
	point23 point not null,
	point24 point not null,
	point25 point not null,
	point26 point not null,
	point27 point not null,
	point28 point not null,
	point29 point not null,
	point30 point not null,
	point31 point not null,
	point32 point not null,
	point33 point not null,
	point34 point not null,
	point35 point not null,
	point36 point not null,
	point37 point not null,
	point38 point not null,
	point39 point not null,
	point40 point not null,
	point41 point not null,
	point42 point not null,
	point43 point not null,
	point44 point not null,
	point45 point not null,
	point46 point not null,
	point47 point not null,
	point48 point not null,
	point49 point not null,
	point50 point not null,
	point51 point not null,
	point52 point not null,
	point53 point not null,
	point54 point not null,
	point55 point not null,
	point56 point not null,
	point57 point not null,
	point58 point not null,
	point59 point not null,
	point60 point not null,
	point61 point not null,
	point62 point not null,
	point63 point not null,
	point64 point not null,
	point65 point not null,
	point66 point not null,
	point67 point not null,
	point68 point not null
    );'];

// execute each sql statement to create new tables
foreach ($sqlList as $sql) {
    pg_query($sql);
}
