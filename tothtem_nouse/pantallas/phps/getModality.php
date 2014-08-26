<?php
include ('../scripts/libs/db.class.php');
//get data
$idModality = $_REQUEST['idModality'];

//get modality name
$db = NEW DB();
$sql = "SELECT name FROM modality WHERE id='$idModality'";

$modalityName = $db->doSql($sql);
//get last ticket from selected modality
$db2 = NEW DB();
$sql2 = "SELECT last_ticket FROM tickets WHERE modality='$idModality' and hour_end='NaN' order by id limit 1";
$modalityTicket = $db2->doSql($sql2);

$data = array('modalityName' => $modalityName['name'], 'modalityTicket' => $modalityTicket['last_ticket']);
echo json_encode($data);

?>