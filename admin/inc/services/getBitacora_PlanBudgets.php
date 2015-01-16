<?php
include("../libs/db.class.php");
header('Content-Type: text/html; charset=utf-8');

$rut = $_REQUEST['rut'];

//Registros
$sql = "SELECT * 
		FROM plan p, logs l
		WHERE plan_log=l.id
		AND rut ='$rut'
		AND description LIKE '%Fin de atención%' ORDER BY plan_log";

		/*WHERE (plan_log=l.id OR budget_log=l.id) 
		AND rut ='$rut'
		AND (description LIKE '%Fin de atención%' OR description LIKE '%Presupuesto%') ORDER BY plan_log";*/

$db = new DB();
$db2 = new DB();

$result = $db->doSql($sql);

if($result){
	do{
	/*	echo 'Origen: '.$result["origin"];
		echo '<br/>';
		echo 'Plan de Tratamiento N: '.$result["plan_number"];
		echo '<br/>';

		$medical = explode('Médico',$result["description"]);
		$medical = explode(', RUT',$medical[1]);

		echo 'Medico: '.$medical[0];
		echo '<br/>';
		echo 'Fecha y Hora: '.$result["datetime"];
		echo '<br/>';

		if($result['budget_log']){
			$budget = $db2->doSql("SELECT * FROM logs WHERE id=".$result['budget_log']);
			echo 'Presupuesto: OK';
			echo '<br/>';
			echo 'Fecha y Hora Presupuesto: '.$budget["datetime"];
			echo '<br/>';
		}

		echo '---------------------------------------------';
		echo '<br/>';
	*/
		$budget_time='';
		$medical = explode('Médico',$result["description"]);
		$medical = explode(', RUT',$medical[1]);

		if($result['budget_log']){
			$budget = $db2->doSql("SELECT * FROM logs WHERE id=".$result['budget_log']);
			$budget_time = $budget["datetime"];
		}

		$data[]=array(
			"origin" => $result["origin"],
			"medical" => $medical[0],
			"plan_number" => $result["plan_number"],
			"plan_time" => $result["datetime"],
			"budget_time" => $budget_time
		);


	} while($result=pg_fetch_assoc($db->actualResults));

	echo json_encode($data);
}else{
	echo 0;
}

?>