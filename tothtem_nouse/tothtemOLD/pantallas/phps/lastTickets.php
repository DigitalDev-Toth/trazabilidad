<?php
include ('../scripts/libs/db.class.php');
//get data
$modality = $_REQUEST['modality'];
$last = $_REQUEST['last'];

//get last ticket
$db = NEW DB();
$sql = "SELECT * FROM tickets WHERE modality=$modality and hour_end='NaN' and CAST(last_ticket AS INT)>=$last ORDER BY id Asc limit 5";
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

