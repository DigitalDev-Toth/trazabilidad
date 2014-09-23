<?php
include ('../admin/inc/libs/db.class.php');

//get data
$zone = $_REQUEST['zone'];

//get last ticket
$db = NEW DB();
$sql = "SELECT *
		FROM tickets t
		LEFT JOIN logs l ON l.id=t.logs
		WHERE t.attention IN('waiting','on_serve','limb','derived') AND l.zone=$zone AND l.datetime>'".date('Y-m-d')."'";
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

