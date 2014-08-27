<?php
//include ('../scripts/libs/db.class.php');
include ('../libs/db.class.php');
//get data
$module = $_REQUEST['module'];

//get last ticket
$db = NEW DB();
$sql = "SELECT ticket FROM last_tickets WHERE module=$module";
$lastTicket = $db->doSql($sql);


if($lastTicket){
	$last=$lastTicket["ticket"];
}
    
//get last ticket
$db = NEW DB();
$sql = "SELECT * 
		FROM tickets t
		LEFT JOIN logs l ON l.id=t.logs
		LEFT JOIN module s ON s.id=l.module
		WHERE s.id=$module AND t.attention='waiting' AND CAST(t.ticket AS INT)>=$last ORDER BY t.id ASC ";
//echo $sql;
$lastRecord = $db->doSql($sql);


//get last ticket
$db2 = NEW DB();
$sql = "SELECT * 
		FROM submodule s
		LEFT JOIN module m ON s.module=m.id
		LEFT JOIN logs l ON l.sub_module=s.id
		LEFT JOIN tickets t ON t.logs=l.id
		WHERE s.module=$module AND s.state='activo' AND m.type!=1 AND t.attention='on_serve' ORDER BY s.id DESC ";
//echo $sql;
$submoduleRecords = $db2->doSql($sql);


if($lastRecord || $submoduleRecords){
	$i=0;
	do {
		//fill tasks array
		if($lastRecord){
			$tickets[$i]['typeJson'] = 'ticket';
			foreach ($lastRecord as $field=>$value) {
				$tickets[$i][$field] = $value;
				if($field=='datetime'){
					//$waitingTime=strtotime('now') - strtotime($value);
					$today = date('Y-m-d H:i:s');
					$waiting = new DateTime("$value");
					$today = new DateTime("$today");
					$dif = $waiting->diff($today);
					$waitingTime = $dif->format('%H:%I');					


					$tickets[$i]['waitingTime'] = $waitingTime;
				}
				//$tasks[$i][$field] = utf8_decode(htmlentities($value));
			}
			$i++;
		}
	} while($lastRecord=pg_fetch_assoc($db->actualResults));


	do {
		//fill tasks array
		if($submoduleRecords){
			$tickets[$i]['typeJson'] = 'submodule';
			foreach ($submoduleRecords as $field=>$value) {
				$tickets[$i][$field] = $value;
				if($field=='datetime'){
					//$waitingTime=strtotime('now') - strtotime($value);
					$today = date('Y-m-d H:i:s');
					$waiting = new DateTime("$value");
					$today = new DateTime("$today");
					$dif = $waiting->diff($today);
					$waitingTime = $dif->format('%H:%I');					

					$tickets[$i]['waitingTime'] = $waitingTime;
				}
				//$tasks[$i][$field] = utf8_decode(htmlentities($value));
			}
			$i++;
		}
		
	} while($submoduleRecords=pg_fetch_assoc($db2->actualResults));


	echo json_encode($tickets);
}else{
	return 0;
}



?>

