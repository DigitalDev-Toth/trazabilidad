<?php
include ('../libs/db.class.php');
//get data

$rut = $_REQUEST['rut'];
$date = $_REQUEST['date'];


$db = NEW DB();
$sql = "SELECT * FROM logs 
		WHERE rut='$rut' AND datetime >= '$date' AND datetime < ('$date'::date + '1 day'::interval)";
$logs = $db->doSql($sql);

$waitingTime = 0;
$attentionTime = 0;

do{
    $data[] = array(
        "id" => $logs['id'],
        "rut" => $logs['rut'],
        "datetime" => $logs['datetime'],
        "description" => $logs['description']
    );
} while($logs=pg_fetch_assoc($db->actualResults));


for($i=0;$i<count($data);$i++){
	if(stripos($data[$i]['description'],'Retiro de Ticket')===0){
		//if(isset($data[$i+1]){
			$waitingTime =  $waitingTime + (strtotime($data[$i+1]['datetime']) - strtotime($data[$i]['datetime']));
		//}
	}
	if(stripos($data[$i]['description'],'Ticket ha venido')===0){
		//if(isset($data[$i+1]){
			$attentionTime =  $attentionTime + (strtotime($data[$i+1]['datetime']) - strtotime($data[$i]['datetime']));
		//}
	}
}
//Tiempo de espera
$wSeconds = ($waitingTime%3600)%60;
$wMinutes = (($waitingTime%3600)-$wSeconds)/60;
$wHours = ($waitingTime-($waitingTime%3600))/3600;

//Tiempo de atención
$aSeconds = ($attentionTime%3600)%60;
$aMinutes = (($attentionTime%3600)-$aSeconds)/60;
$aHours = ($attentionTime-($attentionTime%3600))/3600;

if($wSeconds>=0 && $wSeconds<=9) $wSeconds = '0'.$wSeconds;
if($wMinutes>=0 && $wMinutes<=9) $wMinutes = '0'.$wMinutes;
if($wHours>=0 && $wHours<=9) $wHours = '0'.$wHours;
if($aSeconds>=0 && $aSeconds<=9) $aSeconds = '0'.$aSeconds;
if($aMinutes>=0 && $aMinutes<=9) $aMinutes = '0'.$aMinutes;
if($aHours>=0 && $aHours<=9) $aHours = '0'.$aHours;

echo 'RUT/DNI: '.$rut.' - Fecha: '.$date.'<br/>';
echo 'Tiempo de espera Total: '.$wHours.':'.$wMinutes.':'.$wSeconds;
echo '<br/>';
echo 'Tiempo de atenci&oacute;n Total: '.$aHours.':'.$aMinutes.':'.$aSeconds;


?>