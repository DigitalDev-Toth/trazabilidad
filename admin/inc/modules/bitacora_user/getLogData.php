<?php

include ('../../libs/db.class.php');
include ('../../services/getTimeString.php');
//get data


$user = $_REQUEST['user'];
$date1 = $_REQUEST['date1'];
$date2 = $_REQUEST['date2'];


$db1 = NEW DB();
$sql = "SELECT id FROM logs WHERE users = $user AND rut = '$user' AND datetime BETWEEN '$date1' AND '$date2'";

$ids = $db1->doSql($sql);

if($ids){
	do{
	    $data[] = array(
	        "id" => $ids['id'],
	    );
	} while($ids=pg_fetch_assoc($db1->actualResults));

	for ($i=0; $i < count($data); $i++) { 

		$id=$data[$i]['id'];


		$db = NEW DB();
		$sql = "SELECT l.rut AS rut, l.datetime AS datetime, l.description AS description, z.name AS zone, m.name AS module, s.name AS submodule, 
				u.username AS username
				FROM logs l
				LEFT JOIN zone z ON z.id=l.zone
				LEFT JOIN module m ON m.id=l.module
				LEFT JOIN submodule s ON s.id=l.sub_module
				LEFT JOIN users u ON u.id=l.users
				WHERE l.id=$id";
		$logs = $db->doSql($sql);

		if($logs){
			$dbDetail = NEW DB();

			if(stripos($logs['description'],'Inicio de Sesión Usuario')===0){
				//Tiempo de inicio y fin
				$attentionStart = $logs['datetime'];
				$sql = "SELECT datetime
						FROM logs 
						WHERE users=$user 
						AND rut='$user' 
						AND datetime>'".$logs['datetime']."' 
						AND description='Cierre de Sesión Usuario: $user' ORDER BY datetime ASC LIMIT 1";
				$attentionEndLog = $dbDetail->doSql($sql);
				$attentionEnd = $attentionEndLog['datetime'];
				
				//Pausas
				if(isset($attentionEnd)) $timeFinish=$attentionEnd; //Obtención de tiempo final, se considera el actual en caso de no existir finalización
				else $timeFinish=date("Y-m-d H:i:s");

				$sql = "SELECT * 
						FROM logs 
						WHERE users=$user AND description IN('Pausa de Sesión Usuario: $user','Re-inicio de Sesión Usuario: $user') 
						AND datetime BETWEEN '".$attentionStart."' AND '".$timeFinish."' ORDER BY id";
				$logsPause = $db->doSql($sql);

				$timePaused = 0;
				$pauseNow = 0;
				$countPauseStart = 0;
				$countPauseFinish = 0;
				do{

					if($logsPause['action']=='to'){
						$pauseNow = strtotime($logsPause['datetime']);
						$countPauseStart++;
					}else{
						$timePaused = $timePaused + (strtotime($logsPause['datetime']) - $pauseNow);
						$pauseNow = 0;
						if($timePaused!=0)$countPauseFinish++;
					}

				}while($logsPause = pg_fetch_assoc($db->actualResults));
				//echo $countPauseStart.' - '.$countPauseFinish.'<br/>';
				if($countPauseStart!=$countPauseFinish) $timePaused = $timePaused + (strtotime(date("Y-m-d H:i:s")) - $pauseNow);

				$timeAttention = getTimeString(strtotime($timeFinish) - (strtotime($attentionStart) + $timePaused));
				$timePaused = getTimeString($timePaused);
				//echo $countPauseStart.' - '.$timePaused.' - '.$timeAttention.'<br>';
			}



			if(stripos($logs['description'],'Cierre de Sesión Usuario')===0){
				$attentionEnd = $logs['datetime'];
				$sql = "SELECT datetime
						FROM logs 
						WHERE users=$user 
						AND rut='$user' 
						AND datetime<'".$logs['datetime']."' 
						AND description='Inicio de Sesión Usuario: $user' ORDER BY datetime DESC LIMIT 1";
				$attentionStartLog = $dbDetail->doSql($sql);
				$attentionStart = $attentionStartLog['datetime'];
			}
			
			if(stripos($logs['description'],'Pausa de Sesión Usuario')===0 || stripos($logs['description'],'Re-inicio de Sesión Usuario')===0){
				$sql = "SELECT datetime
						FROM logs 
						WHERE users=$user 
						AND rut='$user' 
						AND datetime<'".$logs['datetime']."' 
						AND description='Inicio de Sesión Usuario: $user' ORDER BY datetime DESC LIMIT 1";
				$attentionStartLog = $dbDetail->doSql($sql);
				$attentionStart = $attentionStartLog['datetime'];

				$sql = "SELECT datetime
						FROM logs 
						WHERE users=$user 
						AND rut='$user' 
						AND datetime>'".$logs['datetime']."' 
						AND description='Cierre de Sesión Usuario: $user' ORDER BY datetime ASC LIMIT 1";
				$attentionEndLog = $dbDetail->doSql($sql);
				$attentionEnd = $attentionEndLog['datetime'];
			}


			$logData[] = array(
				"rut" => $logs['rut'],
				"datetime" => $logs['datetime'],
				"description" => $logs['description'],
				"zone" => $logs['zone'],
				"module" => $logs['module'],
				"submodule" => $logs['submodule'],
				"attentionStart" => $attentionStart,
				"attentionEnd" => $attentionEnd,
				"attentionPauses" => $countPauseStart,
				"timePauses" => $timePaused,
				"timeAttention" => $timeAttention
				);
			$attentionEnd = '-';
			
			
		}else{
			echo 0;
		}
	    
	}
	echo json_encode($logData); 


}else{
	echo 0;
}


?>