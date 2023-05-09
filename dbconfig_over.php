<?php

//$url_root = "ibex.software/tarmac_tests555/public/";
$url_root = "Richard/tarmac_tests555/mmb-basic/";
//$url_root = "ibex.software/tarmac_tests555/mmb-basic-andrew-22.06.21/";
$db_username = 'root';
// $db_password = 'dfsah$qd23)6823vCDn%2d';
$db_password = '';
$db_database = 'mmb_basic2'; //'tarmac';

try {
	$db = new PDO("mysql:host=localhost;dbname={$db_database};charset=utf8", $db_username, $db_password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::  ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e){
	echo $e->getMessage();
}
