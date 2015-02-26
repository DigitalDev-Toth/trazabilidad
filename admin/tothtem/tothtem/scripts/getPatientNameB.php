<?php
//include("libs/db.class.php");
$rut = $_REQUEST['rut'];

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