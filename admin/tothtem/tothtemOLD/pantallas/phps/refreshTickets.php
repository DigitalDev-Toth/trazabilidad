<?php
include ('../scripts/libs/db.class.php');
//get data

$modality = $_REQUEST['modality'];
$attention=$_REQUEST['attention'];
$tickets=$_REQUEST['tickets'];


try {
	$db1 = NEW DB();
	$sql1 = "SELECT * FROM tickets WHERE modality=$modality and hour_end='NaN' and CAST(last_ticket AS INT)>=$tickets ORDER BY id Asc limit 1";
	$result1 = $db1->doSql($sql1);
	$lastTicket=$result1['last_ticket'];
	$modalityN=$result1['modality'];
	$rutPatient=$result1['rut_patient'];
	$db = NEW DB();
	$sql = "SELECT id from tickets where last_ticket='$lastTicket' and modality=$modalityN and rut_patient='$rutPatient' and attention='waiting'";
	$result = $db->doSql($sql);
	$id=$result['id'];
	$hour_end = date('H:i');
	$db2 = NEW DB();
	$stringSql="UPDATE tickets SET hour_end='$hour_end' , attention='$attention' where id=$id";
	$db2->doSql($stringSql);
} catch (Exception $e) {
	//...
}


?>