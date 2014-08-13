<?php
include("libs/db.class.php");
$db = new DB("calendar", "id", "sm");
$db2 = new DB("calendar", "id", "sm");
$date = $_REQUEST['date_c'];
$room = $_REQUEST['room'];
$time = $_REQUEST['time'];
$userData = $db->doSql("SELECT users FROM calendar WHERE date_c='$date_c' AND room = $room ORDER BY hour_c ASC LIMIT 1");
$users = $userData['users'];
if($_REQUEST['room'] == '' || $_REQUEST['time'] == '' || $_REQUEST['date_c'] == ''){
	
	echo "Datos";
}
else
{
	$sql = "SELECT hour_c FROM calendar WHERE date_c='$date' and room=$room ORDER BY hour_c ASC";
	//echo $sql;
	$data = $db->doSql($sql);
	$sql = "SELECT * FROM schedule WHERE date_s='$date' AND room=$room AND type !='sobrecupo' AND users !=1";
	$rows = $db2->doSql($sql);
	if($rows['mi_hour']){
			$inicio1 = $rows['mi_hour'];
			$fin1 = $rows['me_hour'];
	}
	if($rows['ai_hour']) {
		$inicio2 = $rows['ai_hour'];
		$fin2 = $rows['ae_hour'];
	}
	do {
		if($inicio1){
			$timeInicio = strtotime($inicio1);
			$timeFin = strtotime($fin1);
			for ($timeInicio = $timeInicio; $timeInicio <= $timeFin ; $timeInicio = strtotime("+$time minutes", $timeInicio)) { 
				//$timeInicio = strtotime("time", $timeInicio);
				//echo "hola";
				//echo $timeInicio."<br>";
				//echo $data['hour_c']."<br>";
				$horario = date('H:i', $timeInicio);
				if($data['hour_c'] != $horario) echo "$horario<br>";
				$time = $time+15;
			}
		}
		//echo $data['hour_c']."<br>";
	} while ($data = pg_fetch_array($db->actualResults));
	/*$i=0;
	$sql = "SELECT mi_hour, me_hour AS manana FROM schedule WHERE date_s='$date' AND room=$room";
	$data = $db->doSql($sql);
	$horarioManana=  $data['manana'];
	$sql = "SELECT hour_c AS primera_agenda FROM calendar WHERE date_c='$date' AND room=$room LIMIT 1";
	$data = $db->doSql($sql);
	$primera_agenda = $data['primera_agenda'];
	if($horarioManana <=$primera_agenda) {
		$primera = $horarioManana;
		$fin = $horarioManana;
	}
	else {
		$primera = $primera_agenda;
	}
	$row[$i]['desde'] = $primera;*/

}
?>
