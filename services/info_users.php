<?php
include ('../admin/inc/libs/db.class.php');


function getTimeString($timeSeconds){
	$timeSeconds = round($timeSeconds);
	$seconds = ($timeSeconds%3600)%60;
	$minutes = (($timeSeconds%3600)-$seconds)/60;
	$hours = ($timeSeconds-($timeSeconds%3600))/3600;
	
	if($seconds<=9) $seconds = '0'.$seconds;
	if($minutes<=9) $minutes = '0'.$minutes;
	if($hours<=9) $hours = '0'.$hours;

	return $hours.':'.$minutes.':'.$seconds;
}

$submodule = $_REQUEST['submodule'];
$user = $_REQUEST['user'];
$date = date('Y-m-d');



for ($i=0; $i < 4; $i++) { 
	
}


//change date!!
$db = NEW DB();
$sql = "SELECT * FROM logs 
		WHERE sub_module=$submodule AND datetime >= '2014-10-08' 
		AND length(cast (rut as text))>10
		AND users=$user
		AND description IN ('Ticket Finalizado','Ticket Derivado','Ticket ha venido') 
		AND datetime < ('2014-10-09'::date + '2 day'::interval) ORDER BY rut,id";

$logs = $db->doSql($sql);

do{
    $data[] = array(
        "id" => $logs['id'],
        "rut" => $logs['rut'],
        "datetime" => $logs['datetime'],
        "description" => $logs['description'],
        "submodule" => $logs['sub_module']
    );
} while($logs=pg_fetch_assoc($db->actualResults));


$servedCount=0;
$servedTimeTotal=0;
$servedMaxTime=0;
$servedMinTime=0;


for($i=0;$i<count($data);$i++){
	if($data[$i]['description']=='Ticket ha venido' || $data[$i]['description']=='Ticket Derivado' && $i+1<count($data)){
		if($data[$i]['rut']==$data[$i+1]['rut'] && ($data[$i+1]['description']=='Ticket Derivado' || $data[$i+1]['description']=='Ticket Finalizado')){
			$servedTime = strtotime($data[$i+1]['datetime']) - strtotime($data[$i]['datetime']);
			if($servedCount==0)$servedMinTime = $servedTime;
			$servedTimeTotal =  $servedTimeTotal + $servedTime;
			if($servedMaxTime<$servedTime) $servedMaxTime = $servedTime;
			if($servedMinTime>$servedTime) $servedMinTime = $servedTime;
			$servedCount++;
		}
	}

}



$maxtime = date('Y-m-d').' '.getTimeString($servedMaxTime);
$mintime = date('Y-m-d').' '.getTimeString($servedMinTime);
if($servedCount==0){
	$average=date('Y-m-d').' '.getTimeString($servedTimeTotal);
}else{
	$average=date('Y-m-d').' '.getTimeString($servedTimeTotal/$servedCount);
}

$returnData = array('served_tickets' => $servedCount, 'maxtime' => $maxtime,'mintime' => $mintime,'average' => $average);
echo json_encode($returnData);


?>