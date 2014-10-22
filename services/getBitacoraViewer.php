<?php
//include("libs/db.class.php");
$rut = $_REQUEST['rut'];

$con = pg_connect("host=biopacs.com port=5432 dbname=es14b_hrt2 user=postgres password=justgoon") or die('NO HAY CONEXION: ' . pg_last_error());

$sql = "SELECT * "
        ."FROM patient "
        ."WHERE rut = '$rut'";

$resultado = pg_query($con, $sql);
$row = pg_numrows($resultado);

if ($row) {
    for($i = 0; $i < $row; $i++) {
        $patientData[$i] = array(
            'id' =>  pg_result($resultado,$i,0),
            'rut' =>  pg_result($resultado,$i,1),
            'name' =>  pg_result($resultado,$i,2),
            'lastname' =>  pg_result($resultado,$i,3),
            'birthdate' =>  pg_result($resultado,$i,4),
            'gender' =>  pg_result($resultado,$i,5),
            'address' =>  pg_result($resultado,$i,6)
        );
    }
   
    echo json_encode($patientData);
} else {
    echo "0";
}