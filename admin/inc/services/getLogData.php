<?php

include ('../libs/db.class.php');
include ('getTimeString.php');
//get data

$id = $_REQUEST['idLog'];


//get last ticket
$db = NEW DB();
$sql = "SELECT l.rut AS rut, l.datetime AS datetime, l.description AS description, z.name AS zone, m.name AS module, s.name AS submodule, u.username AS username
		FROM logs l
		LEFT JOIN zone z ON z.id=l.zone
		LEFT JOIN module m ON m.id=l.module
		LEFT JOIN submodule s ON s.id=l.sub_module
		LEFT JOIN users u ON u.id=l.users
		WHERE l.id=$id";
$logs = $db->doSql($sql);

if($logs){
	$dbDetail = NEW DB();

	if(stripos($logs['description'],'Retiro de Ticket')===0 || $logs["description"]=="Ticket Derivado" ){
		$waitingStart = $logs['datetime'];
		$sql = "SELECT datetime, description FROM logs WHERE rut='".$logs['rut']."' AND datetime>'".$logs['datetime']."' AND (description='Ticket ha venido' OR description='Ingreso de RUT Totem') ORDER BY id ASC LIMIT 1";
		$attentionStart = $dbDetail->doSql($sql);
		if($attentionStart['description']=='Ticket ha venido'){
			$attentionStart = $attentionStart['datetime'];
			$sql = "SELECT datetime FROM logs WHERE rut='".$logs['rut']."' AND datetime>'".$logs['datetime']."' AND description IN('Ticket Finalizado','Ticket Ausente') ORDER BY id ASC LIMIT 1";
			$attentionFinish = $dbDetail->doSql($sql);
			$attentionFinish = $attentionFinish['datetime'];
		}else{
			$secondDescription = $attentionStart['description'];
			$attentionStart = $attentionStart['datetime'];
			$attentionFinish = null;
		}
	}

	if($logs["description"]=="Ticket ha venido"){
		$sql = "SELECT datetime FROM logs WHERE rut='".$logs['rut']."' AND datetime<'".$logs['datetime']."' ORDER BY id DESC LIMIT 1";
		$waitingStart = $dbDetail->doSql($sql);
		$waitingStart = $waitingStart['datetime'];
		$attentionStart = $logs['datetime'];
		$sql = "SELECT datetime FROM logs WHERE rut='".$logs['rut']."' AND datetime>'".$logs['datetime']."' ORDER BY id ASC LIMIT 1";
		$attentionFinish = $dbDetail->doSql($sql);
		$attentionFinish = $attentionFinish['datetime'];
	}

	if($logs["description"]=="Ticket Finalizado" || $logs["description"]=="Ticket Ausente"){
		$sql = "SELECT datetime FROM logs WHERE rut='".$logs['rut']."' AND datetime<'".$logs['datetime']."' AND (description LIKE 'Retiro de Ticket' OR description='Ticket Derivado') ORDER BY id DESC LIMIT 1";
		$waitingStart = $dbDetail->doSql($sql);
		$waitingStart = $waitingStart['datetime'];
		$sql = "SELECT datetime FROM logs WHERE rut='".$logs['rut']."' AND datetime<'".$logs['datetime']."' AND description='Ticket ha venido' ORDER BY id DESC LIMIT 1";
		$attentionStart = $dbDetail->doSql($sql);
		$attentionStart = $attentionStart['datetime'];
		$attentionFinish = $logs['datetime'];
	}


	if($attentionStart!=null){
		$waitingTime = getTimeString(strtotime($attentionStart)-strtotime($waitingStart));
		if($attentionFinish!=null){
			$attentionTime = getTimeString(strtotime($attentionFinish)-strtotime($attentionStart));
		}else{
			$attentionTime = getTimeString(strtotime(date('Y-m-d H:i:s'))-strtotime($attentionStart));
		}
	}else{
		$waitingTime = getTimeString(strtotime(date('Y-m-d H:i:s'))-strtotime($waitingStart));
		$attentionTime = '-';
	}



	$logData = array(
		"rut" => $logs['rut'],
		"datetime" => $logs['datetime'],
		"description" => $logs['description'],
		"zone" => $logs['zone'],
		"module" => $logs['module'],
		"submodule" => $logs['submodule'],
		"username" => $logs['username'],
		"waitingStart" => $waitingStart,
		"attentionStart" => $attentionStart,
		"attentionFinish" => $attentionFinish,
		"waitingTime" => $waitingTime,
		"attentionTime" => $attentionTime,
		"secondDescription" => $secondDescription
		);

	
	echo json_encode($logData); 
}else{
	echo 0;
}
    




?>