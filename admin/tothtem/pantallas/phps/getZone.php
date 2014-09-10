<?php
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');
//get data
$idZone = $_REQUEST['idZone'];

//get modality name
$db = NEW DB();
$sql = "SELECT * FROM zone WHERE id=$idZone";

$zone = $db->doSql($sql);

$data = array('zoneId'=> $zone['id'] ,'zoneName' => $zone['name']);
echo json_encode($data);

?>