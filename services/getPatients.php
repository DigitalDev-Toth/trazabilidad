<?php
include ('../admin/inc/libs/db.class.php');

//get data
$zone = $_REQUEST['zone'];

//get last ticket
$db = NEW DB();
$sql = "SELECT *
		FROM tickets t
		LEFT JOIN logs l ON l.id=t.logs
		WHERE t.attention IN('waiting','on_serve','limb','derived') AND l.zone=$zone AND l.datetime>'".date('Y-m-d')."'";
$lastRecord = $db->doSql($sql);

if($lastRecord){
	$i=0;
	do {
	//fill tasks array
	foreach ($lastRecord as $field=>$value) {
		$tickets[$i][$field] = $value;
		//$tasks[$i][$field] = utf8_decode(htmlentities($value));


		if($field=='rut'){
			$rut = $value;

			$con = pg_connect("host=biopacs.com port=5432 dbname=es14b_hrt2 user=postgres password=justgoon") or die('NO HAY CONEXION: ' . pg_last_error());

			$sql="SELECT * FROM patient WHERE rut='$rut'";
			$resultado = pg_query($con, $sql);
			$row = pg_numrows($resultado);
			if($row){
			    $tickets[$i]['name'] = pg_result($resultado,0,2).' '.pg_result($resultado,0,3);
			}else{
				$tickets[$i]['name'] = 'Paciente Nuevo';
			}
		}
	}
	$i++;
	} while($lastRecord=pg_fetch_assoc($db->actualResults));
	echo json_encode($tickets);
}else{
	echo 0;
}



?>

