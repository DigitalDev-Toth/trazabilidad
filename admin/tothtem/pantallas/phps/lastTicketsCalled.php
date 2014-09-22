<?php
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');
//get data
error_reporting(E_ALL);
ini_set("display_errors", 1);
$module = $_REQUEST['module'];
/*$last = $_REQUEST['last'];
if($last==null){
	echo 1;
	exit();
}*/

//get module_type
$dbModule = NEW DB();
$sql = "SELECT name 
		from module_type 
		where id= (select type from module where id = 34)";
$modules = $dbModule->doSql($sql);

$module_type = $modules['name'];

//get last ticket
$db = NEW DB();
if($module_type!='Especial'){
	$sql = "SELECT *, t.id AS ticketid 
			FROM tickets t
			LEFT JOIN logs l ON l.id=t.logs
			LEFT JOIN module s ON s.id=l.module
			WHERE s.id=$module AND t.attention IN ('no_serve','served') AND l.datetime>'".date('Y-m-d')."' ORDER BY l.datetime DESC LIMIT 3";
			//WHERE s.id=$submodule AND CAST(t.ticket AS INT)>=$last AND t.attention IN ('waiting','derived') ORDER BY l.datetime ASC LIMIT 5";
}else{
	/*$sql = "SELECT *, t.id AS ticketid 
			FROM tickets t
			LEFT JOIN logs l ON l.id=t.logs
			LEFT JOIN submodule s ON s.module=l.module
			WHERE s.id=$submodule AND t.attention IN ('no_serve','served') AND l.datetime>'".date('Y-m-d')."' ORDER BY SUBSTR (ticket, Length (ticket)) ,l.datetime ASC LIMIT 3";
	*/
}

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

