<?php

$rut = $_REQUEST['rut'];

if(substr($rut,-2,-1)=='-'){
    $type==1;
}else{
    $type==3;
}

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

?>