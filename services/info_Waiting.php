<?php
include ('../admin/inc/libs/db.class.php');
include ('getTimeString.php');

$zone = $_REQUEST['zone'];
$date = date('Y-m-d');


$db = NEW DB();
$sql = "SELECT * 
		FROM tickets t
		LEFT JOIN logs l ON l.id=t.logs
		WHERE zone=$zone AND attention='waiting' AND datetime >= '2014-10-09' AND datetime < ('2014-10-09'::date + '1 day'::interval)";
$logs = $db->doSql($sql);

do{
    $data[] = array(
        "id" => $logs['id'],
        "rut" => $logs['rut'],
        "datetime" => $logs['datetime'],
        "description" => $logs['description'],
        "module" => $logs['module']
    );
} while($logs=pg_fetch_assoc($db->actualResults));



$waitingTimeTotal=0;
$waitingMaxTime=0;
$waitingMinTime=0;


for($i=0;$i<count($data);$i++){

	$waitingTime = strtotime(date('Y-m-d H:i:s')) - strtotime($data[$i]['datetime']);
	if($i==0) $waitingMinTime = $waitingTime;
	$waitingTimeTotal+= $waitingTime;
	if($waitingMaxTime<$waitingTime) $waitingMaxTime = $waitingTime;
	if($waitingMinTime>$waitingTime) $waitingMinTime = $waitingTime;

}

echo 'TOTAL ESPERA: '.count($data).'<br/>';
echo 'TIEMPO M&Aacute;XIMO: '.date('Y-m-d').' '.getTimeString($waitingMaxTime).'<br/>';
echo 'TIEMPO M&Iacute;NIMO: '.date('Y-m-d').' '.getTimeString($waitingMinTime).'<br/>';
echo 'PROMEDIO: '.date('Y-m-d').' '.getTimeString($waitingTimeTotal/count($data)).'<br/>';

$waitingCount = count($data);
$maxtime = date('Y-m-d').' '.getTimeString($waitingMaxTime);
$mintime = date('Y-m-d').' '.getTimeString($waitingMinTime);
$average = date('Y-m-d').' '.getTimeString($waitingTimeTotal/count($data));

$returnData = array('dbtype' => 0,'waiting' => $waitingCount, 'maxtime' => $maxtime,'mintime' => $mintime,'average' => $average);
echo json_encode($returnData);


?>