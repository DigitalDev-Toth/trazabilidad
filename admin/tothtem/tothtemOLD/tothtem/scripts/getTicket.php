<?php
include ('libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);
//get data
$modality = $_REQUEST['modality'];
$rut = $_REQUEST['rut'];
//get last ticket
$db = NEW DB();
$sql = "SELECT last_ticket FROM tickets_last WHERE modality='$modality'";
$lastTicket = $db->doSql($sql);
$newticket=(int)$lastTicket["last_ticket"]+1; 
if($newticket==1000){
    $newticket=1;
}
//set last ticket
$db2 = NEW DB();
$db2->doSql("UPDATE tickets_last SET last_ticket='$newticket' where modality='$modality'");
//insert new ticket patient
$db3 = NEW DB();
$date_t=date('d-m-y');
$hour_start = date('H:i');
$hour_end="NaN";
$db3->doSql("INSERT INTO tickets (last_ticket,modality,date_t,hour_start,hour_end,rut_patient,attention) VALUES ('$newticket',$modality,'$date_t','$hour_start','$hour_end','$rut','waiting')");
$ticketData = array('newticket' => $newticket, 'modality' => $modality, 'date_t' => $date_t, 'hour_start' => $hour_start, 'hour_end' => $hour_end, 'rut' => $rut);
echo json_encode($ticketData);
?>

