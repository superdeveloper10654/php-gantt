<?php

date_default_timezone_set('Europe/London');
include ("../dbconfig.php");

$action = $_REQUEST['action'];
switch ($action) 
{
case "submit_contact_form":
  $created = time();
	$first_name = $_REQUEST['first_name'];
	$last_name = $_REQUEST['last_name'];
  $organisation = $_REQUEST['organisation'];
	$email_address_contact = $_REQUEST['email_address_contact'];
	$telephone_number = $_REQUEST['telephone_number'];
  $subject = $_REQUEST['subject'];
	$message = $_REQUEST['message'];
	$stmt = $db->prepare("INSERT INTO consulting(created,first_name,last_name,organisation,email_address_contact,telephone_number,subject,message) VALUES (?,?,?,?,?,?,?,?)");
	$stmt->bindParam(1, $created);
  $stmt->bindParam(2, $first_name);
  $stmt->bindParam(3, $last_name);
  $stmt->bindParam(4, $organisation);
  $stmt->bindParam(5, $email_address_contact);
  $stmt->bindParam(6, $telephone_number);
  $stmt->bindParam(7, $subject);
  $stmt->bindParam(8, $message);
	$stmt->execute();
	$payload = array("created" => true);
	echo json_encode($payload);
	break;
}
    
?>