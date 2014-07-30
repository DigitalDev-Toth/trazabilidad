<?php
include("libs/db.class.php");
$db = new DB("calendar", "id", "sm");
$db2 = new DB("calendar", "id", "sm");
$date = $_REQUEST['date_c'];
$room = $_REQUEST['room'];
$time_exam = $_REQUEST['time'];
$userData = $db->doSql("SELECT users FROM calendar WHERE date_c='$date_c' AND room = $room ORDER BY hour_c ASC LIMIT 1");
$users = $userData['users'];
if($_REQUEST['room'] == '' || $_REQUEST['time'] == '' || $_REQUEST['date_c'] == ''){
	
	echo "Datos";
}
else
{	$sql = "SELECT mi_hour AS manana, me_hour AS tarde FROM schedule WHERE date_s='$date' AND room=$room";
	$data = $db->doSql($sql);
	$horarioManana=  $data['manana'];
	$tarde=  $data['tarde'];
	$sql = "SELECT hour_c AS primera_agenda FROM calendar WHERE date_c='$date' AND room=$room ORDER BY hour_c ASC LIMIT 1";
	$data = $db->doSql($sql);
	$primera_agenda = $data['primera_agenda'];
	if($horarioManana != $primera_agenda && $horarioManana < $primera_agenda) {
		$primera = $horarioManana;
	}
	else {
		$primera = $primera_agenda;
	}
	$time = strtotime($primera);
	$timeTarde = strtotime($tarde);
	$i=0;
	do {
		$primera = date('H:i', $time);
		$sql = "SELECT calendar.id, hour_c, exam.duration FROM calendar LEFT JOIN exam On exam.id=calendar.exam WHERE hour_c ='$primera' AND room=$room AND date_c='$date'";
		$row = $db->doSql($sql);
		$hour = $row['hour_c'];
		if(!$hour) {
			$rows[$i]['desde'] = $primera;
			$i++;
		}
		$duration = $row['duration'];
		$timeHour = strtotime($hour);
		$next = strtotime("+$duration minutes", $timeHour);
		$next = date('h:i', $next);
		$primera = $primera_agenda;
		$time = strtotime("+$time_exam minutes", $time);
	} while ($time <=$timeTarde);
	echo json_encode($rows);
}
?>
