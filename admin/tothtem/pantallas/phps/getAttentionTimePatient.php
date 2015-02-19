<?php
//Se calcula el tiempo de atención de ejecutivo, considerando tiempos de pausa

include ('../../tothtem/scripts/libs/db.class.php');
include ('getTimeString.php');

//get data
$submodule = $_REQUEST['submodule'];


//Tiempo inicial de atención
$db = NEW DB();
$sql = "SELECT * FROM logs WHERE sub_module=$submodule AND description='Ticket ha venido' ORDER BY id DESC LIMIT 1";
$logs = $db->doSql($sql);

$timeInit = date($logs['datetime']); 
$timeNow = date("Y-m-d H:i:s");

$timeAttention = getTimeString(strtotime($timeNow) - strtotime($timeInit));

echo $timeAttention;
?>