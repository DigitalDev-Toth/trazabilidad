<?php
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');
//get data
error_reporting(E_ALL);
ini_set("display_errors", 1);
$submodule = $_REQUEST['submodule'];

//get last ticket
$db = NEW DB();
$sql = "SELECT *, t.id AS ticketid 
		FROM tickets t
		LEFT JOIN logs l ON l.id=t.logs
		LEFT JOIN submodule s ON s.module=l.module
		WHERE s.id=$submodule AND t.attention='derived' AND l.datetime>'".date('Y-m-d')."'ORDER BY datetime";
//echo $sql;
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



?>

