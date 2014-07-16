<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../admin/login.php"); }
include("../admin/inc/libs/db.class.php");

$zoneId = $_GET['zone'];
if($zoneId) {
	$j = 0; $k = 0;
	$zone = new DB();
	$sql = "SELECT id, name, seats FROM zone WHERE id=$zoneId";
	$rowZone = $zone->doSql($sql);
	$data = array("id"=>$rowZone['id'], "name"=>$rowZone['name'], "seats"=>$rowZone['seats']);

	$module = new DB();
	$sql = "SELECT m.id, m.name, m.max_wait, m.position, t.color, t.shape FROM module AS m LEFT JOIN module_type AS t ON t.id=m.type WHERE m.zone=$zoneId";
	$mRows = $module->doSql($sql);
	do {
		$data[$j] = array("id"=>$mRows['id'], "name"=>$mRows['name'], "max_wait"=>$mRows['max_wait'], "position"=>$mRows['position'], "max_wait"=>$mRows['max_wait'], 
												"color"=>$mRows['color'], "shape"=>$mRows['shape']);
		$submodule = new DB();
		$sql = "SELECT s.id, s.name, s.state AS submodule_state, u.username, u.state AS user_state FROM submodule AS s LEFT JOIN users AS u ON u.id=s.users WHERE s.id=".$mRows['id'];
		$sRows = $submodule->doSql($sql);
		do {
			$data[$j][$k] = array("id"=>$sRows['id'], "name"=>$sRows['name'], "submodule_state"=>$sRows['submodule_state'], "username"=>$sRows['username'], "user_state"=>$sRows['user_state']);
			$k++;
		} while($sRows=pg_fetch_assoc($submodule->actualResults));
		$j++;
	} while($mRows=pg_fetch_assoc($module->actualResults));
}
echo json_encode($data);
?>