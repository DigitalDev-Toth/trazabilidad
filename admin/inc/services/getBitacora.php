<?php
//include("libs/db.class.php");



$data = $_REQUEST['data'];

$data = strtoupper($data);
//$data = explode(" ", $data);

if(count($data) == 2){
    $sql="SELECT * FROM patient WHERE (name LIKE '%$data[0]%' and lastname LIKE '%$data[1]%')";    
}else{
    $sql="SELECT * FROM patient WHERE (rut = '$data' OR name LIKE '%$data%' OR lastname LIKE '%$data%')";


}

$con1 = pg_connect("host=biopacs.com port=5432 dbname=es14b_hrt2 user=postgres password=justgoon") or die('NO HAY CONEXION: ' . pg_last_error());



$resultado = pg_query($con1, $sql);
$row = pg_numrows($resultado);
if($row){

    for($i=0;$i<$row;$i++){
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
}else{
	echo "0";
}
    
?>