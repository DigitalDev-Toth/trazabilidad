<?php
include("libs/db.class.php");
$db = new DB("calendar", "id", "sm");
$db_s = new DB("calendar", "id", "sm");
$db_c = new DB("calendar", "id", "sm");
$db_sc = new DB("schedule", "id", "sm");
$date_c = $_REQUEST['date_c'];
$room = $_REQUEST['room'];
$time = $_REQUEST['time'];
$userData = $db->doSql("SELECT users FROM calendar WHERE date_c='$date_c' AND room = '$room' ORDER BY hour_c ASC LIMIT 1");
$users = $userData['users'];
if($_REQUEST['room'] == '' || $_REQUEST['time'] == '' || $_REQUEST['date_c'] == ''){
	
	echo "Datos";
}
else
{
	$calendar = $db->doSql("SELECT hour_c from calendar where date_c='$date_c' and users = '$users' order by hour_c asc");
	$sch = $db_sc->doSql("SELECT mi_hour, ae_hour FROM schedule WHERE room='$room' and date_s='$date_c'");
	if($sch['mi_hour']){
		if($calendar['hour_c'])
		{
			$i=0;
			$c=0;
			$b=0;
			do{
				$cal = $db_c->doSql("SELECT hour_c from calendar where date_c='$date_c' and room = '$room' order by hour_c asc limit 1 offset $i");
				$primera = $cal['hour_c'];
				$fin = "23:59";
				$time_primera = strtotime($primera);
				$schedule = $db_s->doSql("SELECT hour_c from calendar where date_c='$date_c' and room = '$room' and hour_c <'$primera' order by hour_c desc limit 1");
				$primeraHora = $db_s->doSql("SELECT mi_hour from schedule where date_s='$date_c' and room = '$room'  order by mi_hour desc limit 1");;
				if($schedule['hour_c'] == ''){
					$schedule['hour_c'] = $primeraHora['mi_hour'];
					$inicio = $schedule['hour_c'];
					$time_inicio = strtotime($inicio);
				}
				else
				{
					$inicio = $schedule['hour_c'];
					$time_inicio_prueba = strtotime($inicio);
					$a = $c+1;
					$duration = $db_s->doSql("select exam.duration from calendar left join exam on exam.id=calendar.exam where hour_c='$inicio' and users ='$users' and date_c='$date_c'");
					$call = $db_c->doSql("select hour_c from calendar where date_c='$date_c' and users = '$users' order by hour_c asc limit 1 offset $a");
					$prueba = strtotime($call['hour_c']);
					$next = $duration['duration'];
					$time_inicio = strtotime("+$next minutes", $time_inicio_prueba);
				}
				$inicio = date("H:i", $time_inicio);
				$disponible = strtotime("-$time minutes", $time_primera);
				$hora_disponible = date("H:i", $disponible);
				if($disponible >= $time_inicio && $time_inicio != $prueba){
					//echo "\n\t".'<hora>';
					if($inicio == $hora_disponible){
						/*echo "\n\t\t".'<desde>'.$inicio.'</desde>';
						echo "\n\t".'</hora>';*/
						$row[$b]['desde'] = $inicio;
						$row[$b]['hasta'] = 'No';
						$b++;
					}
					else
					{				
						/*echo "\n\t\t".'<desde>'.$inicio.'</desde>';
						echo "\n\t\t".'<hasta>'.$hora_disponible.'</hasta>';
						echo "\n\t".'</hora>';*/
						$row[$b]['desde'] = $inicio;
						$row[$b]['hasta'] = $hora_disponible;
						$b++;
					}
				}
				$i++;
				$c++;				
			}while($i <= pg_fetch_array($db->actualResults));
		}
		else
		{
			$inicio = $sch['mi_hour'];
			$fin = $sch['ae_hour'];
			$row[0]['desde'] = $inicio;
			$row[0]['hasta'] = $fin;
		}
		echo json_encode($row);
	}
	else
	{
		echo "";
	}
}
?>
