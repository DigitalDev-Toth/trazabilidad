<?php

include ('../scripts/libs/db.class.php');
//get data

$modality = $_REQUEST['modality'];


//get last ticket
$db = NEW DB();
$sql = "SELECT last_ticket FROM tickets_last WHERE modality='$modality'";
$lastTicket = $db->doSql($sql);


if($lastTicket){
	$lastTicketT=$lastTicket["last_ticket"];
	echo $lastTicketT; 
}else{
	echo 0;
}
    




?>