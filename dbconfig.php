<?php
session_start();

// DB constructor

$DB_gantt_name = "mmb-basic";
$DB_gantt_host = "localhost";
$DB_gantt_user = "root";
$DB_gantt_pass = "835f4df86047416c588c1dbf8d322f0dc7b68be129d712b5";
$db_gantt;

try
{
	$db = new PDO("mysql:host={$DB_gantt_host};dbname={$DB_gantt_name}",$DB_gantt_user,$DB_gantt_pass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo $e->getMessage();
}

?>