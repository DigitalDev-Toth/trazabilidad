<?php
//Se calcula el tiempo de atención de ejecutivo, considerando tiempos de pausa

include ('../../tothtem/scripts/libs/db.class.php');
include ('getTimeString.php');

//get data
$idUser = $_REQUEST['user'];


//Tiempo inicial de atención
$db = NEW DB();
$sql = "SELECT * FROM logs WHERE users=$idUser AND description like 'Inicio de Sesión Usuario: $idUser' ORDER BY id DESC LIMIT 1";
$logs = $db->doSql($sql);

$timeInit = date($logs['datetime']);
$timeNow = date("Y-m-d H:i:s");

//Tiempo de pausas
$sql = "SELECT * FROM logs WHERE users=$idUser AND ( description like 'Pausa de Sesión Usuario: $idUser%' or description like'Re-inicio de Sesión Usuario: $idUser%' ) AND datetime>'".$logs['datetime']."' ORDER BY id";
$logsPause = $db->doSql($sql);


$timePaused = 0;
$pauseNow = 0;
do{

	if($logsPause['action']=='to'){
		$pauseNow = strtotime($logsPause['datetime']);
	}else{
		$timePaused = $timePaused + (strtotime($logsPause['datetime']) - $pauseNow);
		$pauseNow = 0;
	}

}while($logsPause = pg_fetch_assoc($db->actualResults));

$timeAttention = getTimeString(strtotime($timeNow) - (strtotime($timeInit) + $timePaused));

echo $timeAttention;
?>