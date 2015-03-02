<?php
//include("libs/db.class.php");



$data = $_REQUEST['data'];

$data = strtoupper($data);
//$data = explode(" ", $data);
echo count($data);
if(count($data) == 2){
    $sql="SELECT * FROM patient WHERE (name LIKE '%$data[0]%' and lastname LIKE '%$data[1]%')"; 
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
}else{
    //$sql="SELECT * FROM patient WHERE (rut = '$data' OR name LIKE '%$data%' OR lastname LIKE '%$data%')";

    if(substr($data,-2,-1)=='-'){
        $type=1;
    }else{
        $type=3;
    }

    if($type==1){
        $rutA = str_replace(".","", $data);
        $rutA = str_replace("-","", $rutA);
        $rutA = substr_replace($rutA ,"",-1);
    }

    exec('curl --data "intTipoDoc='.$type.'&strNroDoc='.$rutA.'" http://201.238.201.37:84/Service.asmx/traeDatosPaciente', $out, $err);

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
//var_dump($out);
    if($out[27]){
   
        $patientData[] = array(
            'rut' => $data,
            'name' =>  $out[27],
            'lastname' =>  $out[28].' '.$out[29],
            'birthdate' =>  $out[30],
            'gender' =>  $out[32],
            'address' =>  ' -'
        );
        echo json_encode($patientData);
    }
}

/*$con1 = pg_connect("host=biopacs.com port=5432 dbname=es14b_hrt2 user=postgres password=justgoon") or die('NO HAY CONEXION: ' . pg_last_error());
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
*/


/*if($out[25]=='<NewDataSet xmlns="">'){
    echo json_encode($patientData);

}else{
	echo "0";
}*/
    
?>