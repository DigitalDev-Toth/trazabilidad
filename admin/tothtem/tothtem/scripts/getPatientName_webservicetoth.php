<?php
//include("libs/db.class.php");
$rut = $_REQUEST['rut'];

//$type = $_GET['type'];
$type = 1;
//$rut = $_GET['rut'];
$xml ="";

if($type==1){
    $rut = str_replace(".","", $rut);
    $rut = str_replace("-","", $rut);
    $rut = substr_replace($rut ,"",-1);
}

exec('curl --data "intTipoDoc='.$type.'&strNroDoc='.$rut.'" http://201.238.201.37:84/Service.asmx/traeDatosPaciente', $out, $err);

/*
27 - Nombres
28 - Apellido Paterno
29 - Apellido Materno
30 - Fecha de nacimiento
31 - Edad
32 - Género
33 - Tipo de Identificador
34 - Identificador (DNI o RUT sin verificador)
*/

$patientData[] = array(
    'name' =>  $out[27],
    'lastname' =>  $out[28].' '.$out[29],
    'birthdate' =>  $out[30],
    'gender' =>  $out[32],
    'address' =>  ' -'
);

echo json_encode($patientData);

/*for ($i=27; $i <= COUNT($out)-5; $i++) {
    $xml .= $out[$i];
    echo $out[$i].'<br/>';
    $patientData[0]['id'] = $out[$i]
}*/

/*
$con = pg_connect("host=biopacs.com port=5432 dbname=es14b_hrt2 user=postgres password=justgoon") or die('NO HAY CONEXION: ' . pg_last_error());

$sql="SELECT * FROM patient WHERE rut='$rut'";
$resultado = pg_query($con, $sql);
$row = pg_numrows($resultado);
if($row){
    $patientData[] = array(
        'id' =>  pg_result($resultado,0,0),
        'rut' =>  pg_result($resultado,0,1),
        'name' =>  pg_result($resultado,0,2),
        'lastname' =>  pg_result($resultado,0,3),
        'birthdate' =>  pg_result($resultado,0,4),
        'gender' =>  pg_result($resultado,0,5),
        'address' =>  pg_result($resultado,0,6)
    );
   
    echo json_encode($patientData);
}else{
	echo "0";
}
*/    

/*
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$rows = pg_numrows($result);
for($i=0;$i<$rows; $i++){
	$line = pg_fetch_array($result, null, PGSQL_ASSOC);
	$name =  $line['name'].' '.$line['lastname'];

}
pg_free_result($result);
// Closing connection
pg_close($dbconn);
echo $name;*/
?>