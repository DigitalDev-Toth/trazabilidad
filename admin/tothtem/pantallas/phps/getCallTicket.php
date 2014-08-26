<?php
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');

$submodule = $_REQUEST['submodule'];

//get last ticket
$db = NEW DB();
$sql = "SELECT * 
		FROM tickets t
		LEFT JOIN logs l ON l.id=t.logs
		WHERE l.sub_module=$submodule AND t.attention='call'";

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
	return 0;
}



?>

