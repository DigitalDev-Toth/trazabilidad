<?php
header('Content-Type: text/html; charset=utf-8');
$medical = $_REQUEST['medical']; //Id de usuario médico
$submodule = $_REQUEST['submodule'];


////////////////////WEBSERVICE - AGENDAMIENTOS////////////////////////
$con = pg_connect("host=biopacs.com port=5432 dbname=es14b_hrt2 user=postgres password=justgoon") or die('NO HAY CONEXION: ' . pg_last_error());

$sql="SELECT *, c.id AS id_calendar
    FROM calendar c
    LEFT JOIN patient p ON p.id=c.patient
    WHERE c.users=$medical AND c.date_c='".date('Y-m-d')."'";

$resultado = pg_query($con, $sql);
$row = pg_numrows($resultado);

///////////////////////////////////////////////////////////////////////


include ('../../tothtem/scripts/libs/db.class.php');
//DATOS DE ZONA Y MÓDULO, PARA EL INICIO DE ESPERA DE ATENCIÓN
$sqlZone="SELECT zone.id AS zone, submodule.module AS module
          FROM zone 
          LEFT JOIN module ON module.zone = zone.id 
          LEFT JOIN submodule ON submodule.module=module.id 
          WHERE submodule.id=$submodule";
$dbZone=NEW DB();
$results=$dbZone->doSql($sqlZone);
$zone=$results['zone'];
$module=$results['module'];


for($i=0;$i<$row; $i++){
    $line = pg_fetch_array($resultado, null, PGSQL_ASSOC);
    
    //Se revisa si existe log (y por ende ticket)
    $sqlLog="SELECT COUNT(*) AS count
        FROM logs
        WHERE datetime='".$line['date_c'].' '.$line['hour_c']."' AND rut='".$line['rut']."'";
    $dblog = NEW DB();
    $resultLog = $dblog->doSql($sqlLog);

    if($resultLog['count']==0){
        //Ingresar ticket y log de ingreso
        $sqlInsertLog= "INSERT INTO logs(rut,datetime,description,zone,action,sub_module,module) VALUES('".$line['rut']."','".$line['date_c'].' '.$line['hour_c']."','Espera de atención consulta médica',$zone,'to',$submodule,$module)";
        $dbInsertlog = NEW DB();
        $dbInsertlog->doSql($sqlInsertLog);

        //Obtener LOG
        $dbLogNew = NEW DB();
        $sqlLogNew = "SELECT id FROM logs ORDER BY id DESC LIMIT 1";
        $resultLogNew = $dbLogNew->doSql($sqlLogNew);
        $log=$resultLogNew['id'];

        $db3 = NEW DB();
        $sqlTicket = "INSERT INTO tickets(logs,ticket,attention) VALUES($log,0,'waiting')";
        $db3->doSql($sqlTicket);

    }else{
        //Hacer nada
    }

    

}

/*Datos de agendamiento provisionales (directo, sin ticket ni verificación de log)

$patientData[$i]['ticketid'] = $line['id_calendar'];
$patientData[$i]['date_c'] = $line['date_c'];
$patientData[$i]['hour_c'] = $line['hour_c'];
$patientData[$i]['users'] = $line['users'];
$patientData[$i]['patient_rut'] = $line['rut'];
$patientData[$i]['patient_name'] = $line['name'].' '.$line['lastname'];

if($patientData){
    echo json_encode($patientData);
}else{
    echo 0;
}*/





//get module_type
$dbModule = NEW DB();
$sql = "SELECT mt.name
        FROM module_type mt
        LEFT JOIN module m ON m.type=mt.id
        LEFT JOIN submodule s ON s.module=m.id
        WHERE s.id=$submodule";
$module = $dbModule->doSql($sql);

$module_type = $module['name'];

//get last ticket
$db = NEW DB();
if($module_type!='Especial'){
    $sql = "SELECT *, t.id AS ticketid 
            FROM tickets t
            LEFT JOIN logs l ON l.id=t.logs
            LEFT JOIN submodule s ON s.module=l.module
            WHERE s.id=$submodule AND t.attention IN ('waiting','derived') AND l.datetime>'".date('Y-m-d')."' ORDER BY l.datetime ASC LIMIT 10";
}else{
    $sql = "SELECT *, t.id AS ticketid 
            FROM tickets t
            LEFT JOIN logs l ON l.id=t.logs
            LEFT JOIN submodule s ON s.module=l.module
            WHERE s.id=$submodule AND t.attention IN ('waiting','derived') AND l.datetime>'".date('Y-m-d')."' ORDER BY SUBSTR (ticket, Length (ticket)) ,l.datetime ASC LIMIT 10";
}

$lastRecord = $db->doSql($sql);

if($lastRecord){
    $i=0;
    do {
        //fill tasks array
        foreach ($lastRecord as $field=>$value) {
            $tickets[$i][$field] = $value;

            /////////PARTE DE WEBSERVICE, NOMBRE DE PACIENTE/////////////
            if($field=='rut'){
                $sql="SELECT * FROM patient WHERE rut='$value'";
                $resultadob = pg_query($con, $sql);
                $rowb = pg_numrows($resultadob);
                if($rowb){
                    $tickets[$i]['patient_name'] = pg_result($resultadob,0,2).' '.pg_result($resultadob,0,3);
                }else{
                    $tickets[$i]['patient_name'] = 'NADA';
                }
                    
            }
            /////////////////////////////////////////////////////////////
            //$tasks[$i][$field] = utf8_decode(htmlentities($value));
        }
        $i++;
    } while($lastRecord=pg_fetch_assoc($db->actualResults));
    echo json_encode($tickets);
}else{
    echo 0;
}

?>