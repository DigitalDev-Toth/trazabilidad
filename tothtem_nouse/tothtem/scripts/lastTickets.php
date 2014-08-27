<?php
include ('libs/db.class.php');
//get data
$modality = $_REQUEST['modality'];
$last = $_REQUEST['last'];

//get last ticket
$db = NEW DB();
$sql = "SELECT * FROM tickets WHERE modality='$modality' and CAST(last_ticket AS INT)>=$last ORDER BY id Asc limit 6";
$lastRecord = $db->doSql($sql);
echo json_encode($lastRecord);
/*while($lastRecord = pg_fetch_assoc($db->actualResults)){
	//var_dump($lastRecord);
	$id=$lastRecord['id'];
	$last_ticket=$lastRecord['last_ticket'];
	$modality=$lastRecord['modality'];
	$date_t=$lastRecord['date_t'];
	$hour_start=$lastRecord['hour_start'];
	$hour_end=$lastRecord['hour_end'];
	$rut_patient=$lastRecord['rut_patient'];
	
	$ticketData = array('id' => $id, 'last_ticket' => $last_ticket,'modality' => $modality, 'date_t' => $date_t, 'hour_start' => $hour_start, 'hour_end' => $hour_end, 'rut_patient' => $rut_patient);

 }
 var_dump($ticketData);*/
 //echo json_encode($ticketData);
//echo $lastRecord;

?>

