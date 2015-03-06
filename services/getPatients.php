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

				/*$con = pg_connect("host=biopacs.com port=5432 dbname=es14b_hrt2 user=postgres password=justgoon") or die('NO HAY CONEXION: ' . pg_last_error());

				$sql="SELECT * FROM patient WHERE rut='$rut'";
				$resultado = pg_query($con, $sql);
				$row = pg_numrows($resultado);
				if($row){
				    $tickets[$i]['name'] = pg_result($resultado,0,2).' '.pg_result($resultado,0,3);
				}else{
					$tickets[$i]['name'] = 'Paciente Nuevo';
				}*/
				if(substr($rut,-2,-1)=='-'){
				    $type=1;
				}else{
				    $type=3;
				}

				if($type==1){
				    $rutA = str_replace(".","", $rut);
				    $rutA = str_replace("-","", $rutA);
				    $rutA = substr_replace($rutA ,"",-1);
				}
				$out='';
				exec('curl --data "intTipoDoc='.$type.'&strNroDoc='.$rutA.'" http://201.238.201.37:84/Service.asmx/traeDatosPaciente', $out, $err);

				/*
				27 - Nombres
				28 - Apellido Paterno
				29 - Apellido Materno
				*/
				//var_dump($out);
				if($out[27]){
				    $tickets[$i]['name'] = $out[27].' '.$out[28].' '.$out[29];
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

