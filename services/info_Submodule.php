<?php
include ('../admin/inc/libs/db.class.php');
include ('getTimeString.php');
//get data
//TIEMPOS DE ZONA
$submodule = $_REQUEST['submodule'];
//$date = $_REQUEST['date'];
$date = date('Y-m-d');


$db = NEW DB();
$sql = "SELECT s.module AS id_module,u.name AS username,u.id AS id_user
		FROM submodule s 
		LEFT JOIN users u ON u.id=s.users
		WHERE s.id=$submodule";
$userData = $db->doSql($sql);

$sql = "SELECT datetime FROM logs WHERE users=".$userData['id_user']." AND description LIKE 'Inicio de Sesión Usuario: ".$userData['id_user']."' ORDER BY id DESC LIMIT 1";
$userLogin = $db->doSql($sql);



$sql = "SELECT * FROM tickets T
		LEFT JOIN logs l ON l.id=t.logs
		WHERE sub_module=$submodule AND attention IN ('waiting','on_serve','served') AND datetime >= '$date' AND datetime < ('$date'::date + '1 day'::interval)";
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
        "rut" => $logs['rut'],
        "datetime" => $logs['datetime'],
        "description" => $logs['description'],
        "attention" => $logs['attention']
    );
} while($logs=pg_fetch_assoc($db->actualResults));


for($i=0;$i<count($data);$i++){
	if($data[$i]['attention']=='on_serve'){
		//ATENCIÓN
		$servedTime = strtotime(date('Y-m-d H:i:s')) - strtotime($data[$i]['datetime']);
		if($servedCount==0)$servedMinTime = $servedTime;
		$servedTimeTotal =  $servedTimeTotal + $servedTime;
		if($servedMaxTime<$servedTime) $servedMaxTime = $servedTime;
		if($servedMinTime>$servedTime) $servedMinTime = $servedTime;
		$servedCount++;
	}

	if($data[$i]['attention']=='served'){

	}
}


echo 'ID_MODULE: '.$userData['id_module'].'<br/>';
echo 'ID_SUBMODULE: '.$submodule.'<br/>';
echo 'USUARIO: '.$userData['username'].'<br/>';
echo 'INICIO SESI&Oacute;N: '.$userLogin['datetime'].'<br/>';
echo 'PACIENTES ATENDIDOS: '.count($data).'<br/>';
echo 'TIEMPO M&Aacute;XIMO: '.date('Y-m-d').' '.getTimeString($servedMaxTime).'<br/>';
echo 'TIEMPO M&Iacute;NIMO: '.date('Y-m-d').' '.getTimeString($servedMinTime).'<br/>';
echo 'PROMEDIO: '.date('Y-m-d').' '.getTimeString($servedTimeTotal/$servedCount).'<br/>';

$module=$userData['id_module'];
$username=$userData['username'];
$usertime=$userLogin['datetime'];
$patients=count($data);
$maxtime=date('Y-m-d').' '.getTimeString($servedMaxTime);
$mintime=date('Y-m-d').' '.getTimeString($servedMinTime);
$average=date('Y-m-d').' '.getTimeString($servedTimeTotal/$servedCount);

$returnData = array('dbtype' => '0','module' => $module, 'submodule' => $submodule, 'user' => $username, 'session' => $usertime, 'patients' => $patients, 'maxtime' => $maxtime, 'mintime' => $mintime, 'average' => $average);
echo json_encode($returnData);
?>