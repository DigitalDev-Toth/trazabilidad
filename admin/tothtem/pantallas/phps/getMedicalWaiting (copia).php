<?php

$medical = $_REQUEST['medical']; //Id de usuario médico

$con = pg_connect("host=biopacs.com port=5432 dbname=es14b_hrt2 user=postgres password=justgoon") or die('NO HAY CONEXION: ' . pg_last_error());

$sql="SELECT *, c.id AS id_calendar
    FROM calendar c
    LEFT JOIN patient p ON p.id=c.patient
    WHERE c.users=$medical AND c.date_c='".date('Y-m-d')."'";

$resultado = pg_query($con, $sql);
$row = pg_numrows($resultado);


for($i=0;$i<$row; $i++){
    $line = pg_fetch_array($resultado, null, PGSQL_ASSOC);
    
    $patientData[$i]['ticketid'] = $line['id_calendar'];
    $patientData[$i]['date_c'] = $line['date_c'];
    $patientData[$i]['hour_c'] = $line['hour_c'];
    $patientData[$i]['users'] = $line['users'];
    $patientData[$i]['patient_rut'] = $line['rut'];
    $patientData[$i]['patient_name'] = $line['name'].' '.$line['lastname'];

}
if($patientData){
    echo json_encode($patientData);
}else{
    echo 0;
}

?>