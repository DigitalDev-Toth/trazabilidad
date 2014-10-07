<?php
include ('../libs/db.class.php');
//get data
//TIEMPOS DE ZONA
$zone = $_REQUEST['zone'];
//$date = $_REQUEST['date'];
$date = date('Y-m-d');


$db = NEW DB();
$sql = "SELECT * FROM tickets T
		LEFT JOIN logs l ON l.id=t.logs
		WHERE zone=$zone AND attention IN ('waiting','on_serve','served') AND datetime >= '$date' AND datetime < ('$date'::date + '1 day'::interval)";
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
	if($data[$i]['attention']=='waiting'){
		$waitingTime = strtotime(date('Y-m-d H:i:s')) - strtotime($data[$i]['datetime']);
		if($waitingCount==0)$waitingMinTime = $waitingTime;
		$waitingTimeTotal =  $waitingTimeTotal + $waitingTime;
		if($waitingMaxTime<$waitingTime) $waitingMaxTime = $waitingTime;
		if($waitingMinTime>$waitingTime) $waitingMinTime = $waitingTime;
		$waitingCount++;
	}

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

/*
for($i=0;$i<count($data);$i++){
	if($data[$i]['attention']=='waiting'){
		$waitingTime = strtotime(date('Y-m-d H:i:s')) - strtotime($data[$i]['datetime']);
		if($waitingCount==0)$waitingMinTime = $waitingTime;
		$waitingTimeTotal =  $waitingTimeTotal + $waitingTime;
		if($waitingMaxTime<$waitingTime) $waitingMaxTime = $waitingTime;
		if($waitingMinTime>$waitingTime) $waitingMinTime = $waitingTime;
		$waitingCount++;
	}
	if($data[$i]['attention']=='on_serve' || $data[$i]['attention']=='served'){
		//ATENCIÓN
		$servedTime = strtotime(date('Y-m-d H:i:s')) - strtotime($data[$i]['datetime']);
		if($servedCount==0)$servedMinTime = $servedTime;
		$servedTimeTotal =  $servedTimeTotal + $servedTime;
		if($servedMaxTime<$servedTime) $servedMaxTime = $servedTime;
		if($servedMinTime>$servedTime) $servedMinTime = $servedTime;
		$servedCount++;
	}
}
*/


echo 'Pacientes en espera: '.$waitingCount;
echo '<br/>Tiempo de espera m&aacute;ximo: '.getTimeString($waitingMaxTime);
echo '<br/>Tiempo de espera m&iacute;nimo: '.getTimeString($waitingMinTime);
echo '<br/>Tiempo de espera promedio: '.getTimeString($waitingTimeTotal/$waitingCount);

echo '<br/><br/>Pacientes atendidos: '.$servedCount;
echo '<br/>Tiempo de atenci&oacute;n m&aacute;ximo: '.getTimeString($servedMaxTime);
echo '<br/>Tiempo de atenci&oacute;n m&iacute;nimo: '.getTimeString($servedMinTime);
echo '<br/>Tiempo de atenci&oacute;n promedio: '.getTimeString($servedTimeTotal/$servedCount);














function getTimeString($timeSeconds){
	$seconds = ($timeSeconds%3600)%60;
	$minutes = (($timeSeconds%3600)-$seconds)/60;
	$hours = ($timeSeconds-($timeSeconds%3600))/3600;

	if($seconds<=9) $seconds = '0'.$seconds;
	if($minutes<=9) $minutes = '0'.$minutes;
	if($hours<=9) $hours = '0'.$hours;

	return $hours.':'.$minutes.':'.$seconds;
}


?>