<?php
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');
//get data
$submodule = $_REQUEST['submodule'];
$last = $_REQUEST['last'];

//get last ticket
$db = NEW DB();
$sql = "SELECT * 
		FROM tickets t
		LEFT JOIN logs l ON l.id=t.logs
		LEFT JOIN submodule s ON s.module=l.module
		WHERE s.id=$submodule AND CAST(t.ticket AS INT)>=$last ORDER BY t.id ASC LIMIT 5";
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

