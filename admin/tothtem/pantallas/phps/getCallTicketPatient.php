<?php

include ('../../tothtem/scripts/libs/db.class.php');
$module = $_REQUEST['module'];
$db = NEW DB();
$sql = "SELECT *, t.id AS ticketid
		FROM tickets t
		LEFT JOIN logs l ON l.id=t.logs
		WHERE l.module=$module AND t.attention IN('call','on_serve')";

$lastRecord = $db->doSql($sql);
if($lastRecord){
	$i=0;
	do {
	foreach ($lastRecord as $field=>$value) {
		$tickets[$i][$field] = $value;
	}
	$i++;
	} while($lastRecord=pg_fetch_assoc($db->actualResults));
	echo json_encode($tickets);
}else{
	echo 0;
}

?>

