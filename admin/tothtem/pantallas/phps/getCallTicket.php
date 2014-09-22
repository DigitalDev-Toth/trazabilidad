<?php
header('Content-Type: text/html; charset=utf-8');
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');


$submodule = $_REQUEST['submodule'];

//get last ticket
$db = NEW DB();
$sql = "SELECT *, t.id AS ticketid 
		FROM tickets t
		LEFT JOIN logs l ON l.id=t.logs
		WHERE l.sub_module=$submodule AND t.attention IN('call','on_serve')";

//echo $sql;
$lastRecord = $db->doSql($sql);

if($lastRecord){
	$i=0;
	do {
	//fill tasks array
	foreach ($lastRecord as $field=>$value) {
		$tickets[$i][$field] = $value;
		//$tasks[$i][$field] = utf8_decode(htmlentities($value));
		if($field=='ticketid'){ //Trae datos último log (en espera)
			$db2 = NEW DB();
			$sql = "SELECT *
					FROM logs
					WHERE rut='".$tickets[$i]['rut']."' 
					AND zone=".$tickets[$i]['zone']." 
					AND module=".$tickets[$i]['module']." 
					AND description='Espera de atención consulta médica' 
					ORDER BY id ASC LIMIT 1";
			$waitingRecord = $db2->doSql($sql);
			$tickets[$i]['waitingDatetime'] = $waitingRecord['datetime'];
		}
	}
	$i++;
	} while($lastRecord=pg_fetch_assoc($db->actualResults));
	echo json_encode($tickets);
}else{
	echo 0;
}

?>

