<?php
include ('../admin/inc/libs/db.class.php');
include ('getTimeString.php');
//get data
//TIEMPOS DE ZONA
//$submodule = $_REQUEST['submodule'];//POR ZONA, devolver arreglo con todos los submódulos
$zone = $_REQUEST['zone'];
//$date = $_REQUEST['date'];
$date = date('Y-m-d');


$db1 = NEW DB();

$sql = "SELECT s.id AS id_submodule, s.module AS id_module,u.name AS username,u.id AS id_user
		FROM submodule s 
		LEFT JOIN users u ON u.id=s.users
		LEFT JOIN module m ON m.id=s.module
		WHERE m.zone=$zone";
$userData = $db1->doSql($sql);

do{
	$db = NEW DB();
	$submodule = $userData['id_submodule'];
	if(!isset($userData['id_user'])) $userData['id_user'] = 0;

	$sql = "SELECT datetime FROM logs WHERE users=".$userData['id_user']." AND description LIKE 'Inicio de Sesión Usuario: ".$userData['id_user']."' ORDER BY id DESC LIMIT 1";
	$userLogin = $db->doSql($sql);


	/*$sql = "SELECT *, T.id AS id_ticket, l.id AS id_log FROM tickets T
			LEFT JOIN logs l ON l.id=t.logs
			WHERE sub_module=$submodule AND attention IN ('on_serve','served') AND datetime >= '$date' AND datetime < ('$date'::date + '1 day'::interval)";
	*/
	$sql = "SELECT * FROM logs 
			WHERE sub_module=$submodule AND datetime >= '$date' 
			AND description IN ('Ticket Finalizado','Ticket Derivado','Ticket ha venido') 
			AND datetime < ('$date'::date + '1 day'::interval) ORDER BY rut,id";

	$logs = $db->doSql($sql);


	$waitingCount = 0;
	$waitingTimeTotal = 0;
	$waitingMaxTime = 0;
	$waitingMinTime = 0;

	$servedCount = 0;
	$servedTimeTotal = 0;
	$servedMaxTime = 0;
	$servedMinTime = 0;

	do{
	    $data[] = array(
	        "id" => $logs['id'],
	        //"id_log" => $logs['id_log'],
	        "rut" => $logs['rut'],
	        "datetime" => $logs['datetime'],
	        "description" => $logs['description']/*,
	        "attention" => $logs['attention']*/
	    );
	} while($logs=pg_fetch_assoc($db->actualResults));
	
	if($data[0]['id']!=null){
		$patients=count($data);
	}else{
		$patients=0;
	}


	for($i=0;$i<$patients;$i++){/*
		if($data[$i]['description']=='on_serve'){
			//ATENCIÓN
			$servedTime = strtotime(date('Y-m-d H:i:s')) - strtotime($data[$i]['datetime']);
			if($servedCount==0)$servedMinTime = $servedTime;
			$servedTimeTotal =  $servedTimeTotal + $servedTime;
			if($servedMaxTime<$servedTime) $servedMaxTime = $servedTime;
			if($servedMinTime>$servedTime) $servedMinTime = $servedTime;
			$servedCount++;
		}

		if($data[$i]['attention']=='served'){
			//ATENDIDOS
			$sql = "SELECT datetime FROM logs WHERE rut='".$data[$i]['rut']."' AND id<'".$data[$i]['id_log']."' AND description='Ticket ha venido' ORDER BY id DESC LIMIT 1";
			$datetimeLog = $db->doSql($sql);

			$servedTime = strtotime($data[$i]['datetime']) - strtotime($datetimeLog['datetime']);
			if($servedCount==0)$servedMinTime = $servedTime;
			$servedTimeTotal =  $servedTimeTotal + $servedTime;
			if($servedMaxTime<$servedTime) $servedMaxTime = $servedTime;
			if($servedMinTime>$servedTime) $servedMinTime = $servedTime;
			$servedCount++;
		}*/

		//REVISAR Y AGREGAR LOS CASOS DERIVADOS
		//for($i=0;$i<$patients;$i++){
			if($data[$i]['description']=='Ticket ha venido' && $i+1<count($data)){
				if($data[$i]['rut']==$data[$i+1]['rut'] && ($data[$i+1]['description']=='Ticket Derivado' || $data[$i+1]['description']=='Ticket Finalizado')){
					$servedTime = strtotime($data[$i+1]['datetime']) - strtotime($data[$i]['datetime']);
					if($servedCount==0)$servedMinTime = $servedTime;
					$servedTimeTotal =  $servedTimeTotal + $servedTime;
					if($servedMaxTime<$servedTime) $servedMaxTime = $servedTime;
					if($servedMinTime>$servedTime) $servedMinTime = $servedTime;
					$servedCount++;
				}else{
					$servedTime = strtotime(date('Y-m-d H:i:s')) - strtotime($data[$i]['datetime']);
					if($servedCount==0)$servedMinTime = $servedTime;
					$servedTimeTotal =  $servedTimeTotal + $servedTime;
					if($servedMaxTime<$servedTime) $servedMaxTime = $servedTime;
					if($servedMinTime>$servedTime) $servedMinTime = $servedTime;
					$servedCount++;
				}
			}

		//}
	}


	/*echo 'ID_MODULE: '.$userData['id_module'].'<br/>';
	echo 'ID_SUBMODULE: '.$submodule.'<br/>';
	echo 'USUARIO: '.$userData['username'].'<br/>';
	echo 'INICIO SESI&Oacute;N: '.$userLogin['datetime'].'<br/>';
	echo 'PACIENTES ATENDIDOS: '.$servedCount.'<br/>';
	echo 'TIEMPO M&Aacute;XIMO: '.$date.' '.getTimeString($servedMaxTime).'<br/>';
	echo 'TIEMPO M&Iacute;NIMO: '.$date.' '.getTimeString($servedMinTime).'<br/>';
	if($servedCount==0){
		echo 'PROMEDIO: '.$date.' '.getTimeString($servedTimeTotal).'<br/>';
	}else{
		echo 'PROMEDIO: '.$date.' '.getTimeString($servedTimeTotal/$servedCount).'<br/>';
	}
	echo '<br/>';
*/

	$module=$userData['id_module'];
	$usernameX=$userData['username'];
	$usertime=$userLogin['datetime'];
	
	$maxtime=$date.' '.getTimeString($servedMaxTime);
	$mintime=$date.' '.getTimeString($servedMinTime);
	if($servedCount==0){
		$average=$date.' '.getTimeString($servedTimeTotal);
	}else{
		$average=$date.' '.getTimeString($servedTimeTotal/$servedCount);
	}
	
	$returnData[] = array('dbtype' => '0','module' => $module, 'submodule' => $submodule, 'user' => $usernameX, 'session' => $usertime, 'patients' => $patients, 'maxtime' => $maxtime, 'mintime' => $mintime, 'average' => $average);
	
	$data = null;
} while($userData=pg_fetch_assoc($db1->actualResults));

//$returnData = array('dbtype' => '0','module' => $module, 'submodule' => $submodule, 'user' => $usernameX, 'session' => $usertime, 'patients' => $patients, 'maxtime' => $maxtime, 'mintime' => $mintime, 'average' => $average);
echo json_encode($returnData);
?>