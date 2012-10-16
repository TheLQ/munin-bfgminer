#!/usr/bin/php
<?php
include 'dump.utils.php';

//First, figure out what config mode were in
$arg = $argv[1];
if(strtolower($arg) == "config") {
	$output = array(
		""
	);
}





echo "\n\r";


//-----------

function output($array) {
	foreach($array as $key => $value)
		echo $key . " " . $value;
}
?>