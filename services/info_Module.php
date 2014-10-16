<?php
include ('../admin/inc/libs/db.class.php');
include ('getTimeString.php');

$zone = $_REQUEST['zone'];
$date = date('Y-m-d');
$dbModules = NEW DB();
$sql = "SELECT * FROM module WHERE zone=$zone";
$modulesData = $dbModules->doSql($sql);

do{
	$module = $modulesData['id'];
	$moduleType = $modulesData['type'];
	if($modulesData['type']!=1){//SI NO ES TÓTEM
		
		$db = NEW DB();
		$sql = "SELECT * FROM logs 
				WHERE module=$module AND datetime >= '$date' 
				AND description IN ('Ticket Finalizado','Ticket Derivado','Ticket ha venido') 
				AND datetime < ('$date'::date + '1 day'::interval) ORDER BY rut,id";
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

		/*echo 'TOTAL ATENDIDOS: '.$servedCount.'<br/>';
		echo 'TIEMPO M&Aacute;XIMO: '.$date.' '.getTimeString($servedMaxTime).'<br/>';
		echo 'TIEMPO M&Iacute;NIMO: '.$date.' '.getTimeString($servedMinTime).'<br/>';
		if($servedCount==0){
			echo 'PROMEDIO: '.$date.' '.getTimeString($servedTimeTotal).'<br/>';
		}else{
			echo 'PROMEDIO: '.$date.' '.getTimeString($servedTimeTotal/$servedCount).'<br/>';
		}*/

		$maxtime = $date.' '.getTimeString($servedMaxTime);
		$mintime = $date.' '.getTimeString($servedMinTime);
		if($servedCount==0){
			$average=$date.' '.getTimeString($servedTimeTotal);
		}else{
			$average=$date.' '.getTimeString($servedTimeTotal/$servedCount);
		}
		$data=null;
		
		$returnData[] = array('dbtype' => $moduleType,'served_tickets' => $servedCount, 'maxtime' => $maxtime,'mintime' => $mintime,'average' => $average,'idModule' => $module);
	
	}else{



		$db = NEW DB();
		$sql = "SELECT * FROM logs WHERE sub_module=(SELECT id FROM submodule WHERE module=$module) AND action='to' AND datetime >= '$date' AND datetime < ('$date'::date + '1 day'::interval) ORDER BY module";
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

		$servedMaxTime=0;
		$servedMinTime=0;
		for($i=0;$i<count($data);$i++){

			$moduleCount[$data[$i]['module']]++;
			$servedTime = strtotime($data[$i]['datetime']);
			if($i==0)$servedMinTime = $servedTime;
			if($servedMaxTime<$servedTime) $servedMaxTime = $servedTime;
			if($servedMinTime>$servedTime) $servedMinTime = $servedTime;

		}

		/*echo '<br/>DB_TYPE: 1<br/>';
		echo 'TOTAL EMITIDOS: '.count($data).'<br/>';
		echo 'PRIMER EMITIDO: '.getTimeString($servedMinTime-strtotime($date)).'<br/>';
		echo '&Uacute;LTIMO EMITIDO: '.getTimeString($servedMaxTime-strtotime($date)).'<br/>';*/

		$totaltickets=count($data);
		$firstissued=$date.' '.getTimeString($servedMinTime-strtotime($date));
		$lastissued=$date.' '.getTimeString($servedMaxTime-strtotime($date));

		$returnData[] = array('dbtype' => $moduleType,'total_tickets' => $totaltickets, 'modules' => $moduleCount,'first_ticket' => $firstissued,'last_ticket' => $lastissued,'idModule' => $module);
	}

} while($modulesData=pg_fetch_assoc($dbModules->actualResults));


echo json_encode($returnData);


?>