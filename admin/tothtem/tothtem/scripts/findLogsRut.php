<?php
include ('libs/db.class.php');
$rut = $_REQUEST['rut'];
$db = NEW DB();
$sql = "SELECT *, t.id AS ticketid
		FROM tickets t
		LEFT JOIN logs l ON l.id=t.logs
		WHERE l.rut='$rut' AND NOT t.attention IN('served','no_serve') AND l.datetime>'".date('Y-m-d')."'";
$row = $db->doSql($sql);

if($row){
	echo json_encode($row);
}else{
	echo 0;
}

?>