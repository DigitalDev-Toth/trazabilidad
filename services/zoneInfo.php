<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../admin/login.php"); }
include("../admin/inc/libs/db.class.php");

$zoneId = $_GET['zone'];
if($zoneId) {
	$zone = new DB();
	$module = new DB();
	$submodule = new DB();
	$sql = "SELECT id, name, seats FROM zone WHERE id=$zoneId";
	$rowZone = $zone->doSql($sql);
	$data = array("id"=>$rowZone['id'], "name"=>$rowZone['name'], "seats"=>$rowZone['seats']);
	$j = 0;
	$sql = "SELECT m.id, m.name, m.max_wait, m.position, t.color, t.shape FROM module AS m LEFT JOIN module_type AS t ON t.id=m.type WHERE m.zone=$zoneId";
	$mRows = $module->doSql($sql);
	if($mRows) {
		do {
<<<<<<< HEAD
			$data['modules'][$j] = array("id"=>$mRows['id'], "name"=>$mRows['name'], "max_wait"=>$mRows['max_wait'], "position"=>$mRows['position'], "max_wait"=>$mRows['max_wait'], 
													"color"=>$mRows['color'], "shape"=>$mRows['shape']);
			$sql = "SELECT s.id, s.name, s.state AS submodule_state, u.username, u.state AS user_state FROM submodule AS s LEFT JOIN users AS u ON u.id=s.users WHERE s.module=".$mRows['id']." ORDER BY s.id";
=======
			$data[$j] = array("id"=>$mRows['id'], "name"=>$mRows['name'], "max_wait"=>$mRows['max_wait'], "position"=>$mRows['position'], "max_wait"=>$mRows['max_wait'], 
													"color"=>$mRows['color'], "shape"=>$mRows['shape']);
			$sql = "SELECT s.id, s.name, s.state AS submodule_state, u.username, u.state AS user_state FROM submodule AS s LEFT JOIN users AS u ON u.id=s.users WHERE s.module=".$mRows['id'];
>>>>>>> 4f093acfc79f5ac08d754e50f66e87374801ab3c
			//echo $sql;
			$sRows = $submodule->doSql($sql);
			if($sRows) {
				$k = 0;
				do {
<<<<<<< HEAD
					$data['modules'][$j]['submodules'][$k] = array("id"=>$sRows['id'], "name"=>$sRows['name'], "submodule_state"=>$sRows['submodule_state'], "username"=>$sRows['username'], "user_state"=>$sRows['user_state']);
=======
					$data[$j]['submodules'][$k] = array("id"=>$sRows['id'], "name"=>$sRows['name'], "submodule_state"=>$sRows['submodule_state'], "username"=>$sRows['username'], "user_state"=>$sRows['user_state']);
>>>>>>> 4f093acfc79f5ac08d754e50f66e87374801ab3c
					$k++;
				} while($sRows=pg_fetch_assoc($submodule->actualResults));
			}
			$j++;
		} while($mRows=pg_fetch_assoc($module->actualResults));
		echo json_encode($data);
		/*echo '<pre>';
		var_dump($data);
		echo '</pre>';*/
	} else {
		echo "error";
	}
}
?>