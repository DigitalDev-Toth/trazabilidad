<?php
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');
//get data
$idSubModule = $_REQUEST['idSubModule'];

//get modality name
$db = NEW DB();
$sql = "SELECT m.id AS module, m.name AS name 
    FROM module m
    LEFT JOIN submodule s ON s.module=m.id
    WHERE s.id=$idSubModule";

$module = $db->doSql($sql);
//get last ticket from selected modality
$db2 = NEW DB();

$sql2 = "SELECT t.ticket AS ticket
        FROM tickets t
        LEFT JOIN logs l ON l.id=t.logs
        WHERE t.attention='waiting' AND l.module=".$module['module']." ORDER BY t.id LIMIT 1";

//$sql2 = "SELECT ticket FROM tickets WHERE module='$idSubModule' and hour_end='NaN' order by id limit 1";
$moduleTicket = $db2->doSql($sql2);

$data = array('modalityName' => $module['name'], 'modalityTicket' => $moduleTicket['ticket']);
echo json_encode($data);

?>