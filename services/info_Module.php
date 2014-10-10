<?php
include ('../admin/inc/libs/db.class.php');
include ('getTimeString.php');

$module = $_REQUEST['module'];
$date = date('Y-m-d');


$db = NEW DB();
$sql = "SELECT * FROM logs 
		WHERE module=$module AND datetime >= '2014-10-09' 
		AND description IN ('Ticket Finalizado','Ticket Derivado','Ticket ha venido') 
		AND datetime < ('2014-10-09'::date + '1 day'::interval) ORDER BY rut,id";
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

echo 'TOTAL ATENDIDOS: '.$servedCount.'<br/>';
echo 'TIEMPO M&Aacute;XIMO: '.date('Y-m-d').' '.getTimeString($servedMaxTime).'<br/>';
echo 'TIEMPO M&Iacute;NIMO: '.date('Y-m-d').' '.getTimeString($servedMinTime).'<br/>';
echo 'PROMEDIO: '.date('Y-m-d').' '.getTimeString($servedTimeTotal/$servedCount).'<br/>';

$maxtime = date('Y-m-d').' '.getTimeString($servedMaxTime);
$mintime = date('Y-m-d').' '.getTimeString($servedMinTime);
$average = date('Y-m-d').' '.getTimeString($servedTimeTotal/$servedCount);

$returnData = array('dbtype' => 0,'served_tickets' => $servedCount, 'maxtime' => $maxtime,'mintime' => $mintime,'average' => $average);
echo json_encode($returnData);


?>