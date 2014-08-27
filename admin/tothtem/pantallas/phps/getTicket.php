<?php
include ('../scripts/libs/db.class.php');
//get data
$modality = $_REQUEST['modality'];
$rut = $_REQUEST['rut'];

//get last ticket
$db = NEW DB();
$sql = "SELECT last_ticket FROM tickets_last WHERE modality=$modality";
$lastTicket = $db->doSql($sql);
$newticket=(int)$lastTicket["last_ticket_a"]+1; 


if($newticket==1000){
    $newticket=1;
}
//set last ticket
$db2 = NEW DB();
$db2->doSql("UPDATE tickets_last SET last_ticket=$newticket where modality=$modality");


//insert new ticket patient
$db3 = NEW DB();
$date_t=date('m/d/Y');
$hour_start = date('H:i');
$hour_end="NaN";
$db3->doSql("INSERT INTO tickets (last_ticket,modality,date_t,hour_start,hour_end,rut_patient) VALUES ($newticket,$modality,$date_t,$hour_start,$hour_end,$rut)");
//return ticket
echo $newticket;

?>