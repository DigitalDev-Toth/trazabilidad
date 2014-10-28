<?php
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');
//get data
error_reporting(E_ALL);
ini_set("display_errors", 1);
$submodule = $_REQUEST['submodule'];
$type = $_REQUEST['type'];

if($type=="no_serve"){
	//get last ticket
	$db = NEW DB();
	$sql = "SELECT *, t.id AS ticketid 
			FROM tickets t
			LEFT JOIN logs l ON l.id=t.logs
			LEFT JOIN submodule s ON s.module=l.module
			WHERE s.id=$submodule AND t.attention='no_serve' AND datetime>'".date('Y-m-d')."' ORDER BY t.id ASC LIMIT 10";
	$lastRecord = $db->doSql($sql);

	if($lastRecord){
		$i=0;
		do {
		//fill tasks array
		foreach ($lastRecord as $field=>$value) {
			$tickets[$i][$field] = $value;
			//$tasks[$i][$field] = utf8_decode(htmlentities($value));
		}
		$i++;
		} while($lastRecord=pg_fetch_assoc($db->actualResults));
		echo json_encode($tickets);
	}else{
		echo 0;
	}
}elseif($type="exception"){
	//get last ticket
	$db = NEW DB();
	/*$sql = "SELECT *, t.id AS ticketid 
			FROM tickets t
			LEFT JOIN logs l ON l.id=t.logs
			LEFT JOIN submodule s ON s.module=l.module
			WHERE s.id=$submodule AND t.attention='waiting' AND datetime>'".date('Y-m-d')."' ORDER BY t.id ASC";*/
	//get module_type
	$dbModule = NEW DB();
	$sql = "SELECT mt.name AS name, m.id AS id
			FROM module_type mt
			LEFT JOIN module m ON m.type=mt.id
			LEFT JOIN submodule s ON s.module=m.id
			WHERE s.id=$submodule";
	$moduleType = $dbModule->doSql($sql);

	$module_type = $moduleType['name'];
	$moduleId = $moduleType['id'];

	$sql =	"SELECT *, t.id AS ticketid 
			FROM tickets t
			LEFT JOIN logs l ON l.id=t.logs
			LEFT JOIN submodule s ON s.module=l.module
			LEFT JOIN module_special ss ON ss.alias = SUBSTR (ticket, Length (ticket))
			WHERE s.id=$submodule AND ss.module=$moduleId AND t.attention IN ('waiting','derived') AND l.datetime>'".date('Y-m-d')."' ORDER BY SUBSTR (ticket, Length (ticket)) ,l.datetime ASC LIMIT 10";

	$lastRecord = $db->doSql($sql);

	if($lastRecord){
		$i=0;
		do {
		//fill tasks array
		foreach ($lastRecord as $field=>$value) {
			$tickets[$i][$field] = $value;
			//$tasks[$i][$field] = utf8_decode(htmlentities($value));
		}
		$i++;
		} while($lastRecord=pg_fetch_assoc($db->actualResults));
		echo json_encode($tickets);
	}else{
		echo 0;
	}
}


?>

