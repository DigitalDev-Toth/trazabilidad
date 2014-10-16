<?php
include ('../admin/inc/libs/db.class.php');
include ('getTimeString.php');

$rut = $_REQUEST['rut'];
$date = date('Y-m-d');


$db = NEW DB();

$sql = "SELECT * FROM logs 
		LEFT JOIN module ON module.id=logs.module
		WHERE rut='$rut' AND datetime >= '$date' AND action='to' AND datetime < ('$date'::date + '1 day'::interval) ORDER BY logs.id DESC LIMIT 1";
$logs = $db->doSql($sql);

do{
    $data[] = array(
        "id" => $logs['id'],
        "rut" => $logs['rut'],
        "datetime" => $logs['datetime'],
        "description" => $logs['description'],
        "modulename" => $logs['name']
    );
} while($logs=pg_fetch_assoc($db->actualResults));

///////////////WEBSERVICE////////////////////////
$con = pg_connect("host=biopacs.com port=5432 dbname=es14b_hrt2 user=postgres password=justgoon") or die('NO HAY CONEXION: ' . pg_last_error());

$sql="SELECT name||' '||lastname FROM patient WHERE rut='$rut'";
$resultado = pg_query($con, $sql);
$row = pg_numrows($resultado);
if($row){
	$name = pg_result($resultado,0,0);
}else{
	$name = "-";
}

////////////////////////////////////////////////

/*echo '<br/>NOMBRE: '.$name.'<br/>';
echo 'RUT/DNI: '.$rut.'<br/>';
echo 'MOTIVO VISITA: '.$data[0]["modulename"].'<br/>';
echo 'INICIO DE ESPERA: '.$data[0]["datetime"].'<br/>';*/

$modulename = $data[0]["modulename"];
$datetime = $data[0]["datetime"];


$returnData = array('dbtype' => 0,'patient_rut' => $rut,'patient_name' => $name, 'modulename' => $modulename,'datetime' => $datetime);
echo json_encode($returnData);


?>