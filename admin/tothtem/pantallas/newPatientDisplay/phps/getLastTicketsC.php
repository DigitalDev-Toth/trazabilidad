<?php
include ('../../../../inc/libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);


$zone = $_REQUEST['zone'];

$db = NEW DB();

$date = date('Y-m-d');

//Se consulta por el último ticket derivado para que no se incluya en la consulta de tickets normales
$sqlDer = "SELECT rut, datetime, m.name AS module, s.name AS submodule
        FROM logs l
        LEFT JOIN module m ON m.id=l.module
        LEFT JOIN submodule s ON s.id=l.sub_module
        WHERE datetime>'$date' AND description='Ticket Derivado'
        AND l.zone=$zone
        ORDER BY datetime DESC LIMIT 1";

$dataDer = $db->doSql($sqlDer);
$sqlPlus = "";
if($dataDer){
    $rutDer = $dataDer['rut'];
    $datetimeDer = $dataDer['datetime'];
    $sqlPlus = "AND NOT l.id=(SELECT id FROM logs WHERE description IN('Ticket ha venido')
                        AND rut='$rutDer' AND datetime>'$datetimeDer' LIMIT 1)";
//echo $sqlPlus;
}
//echo '<br/>';

$sql = "SELECT rut, datetime, m.name AS module, s.name AS submodule
        FROM logs l
        LEFT JOIN module m ON m.id=l.module
        LEFT JOIN submodule s ON s.id=l.sub_module
        WHERE datetime>'$date' AND description='Ticket ha venido'
        AND l.zone=$zone $sqlPlus
        ORDER BY datetime DESC LIMIT 3";

//echo $sql;
$data = $db->doSql($sql);

if($data){
    do{
        $rut = $data['rut'];
        $datetime = $data['datetime'];
        $module = $data['module'];
        $submodule = $data['submodule'];
        
        $db2 = NEW DB();

        $sql2 = "SELECT * FROM tickets t
                LEFT JOIN logs l ON l.id=t.logs
                WHERE rut='$rut' AND datetime>='$datetime' ORDER BY t.id LIMIT 1";
        $data2 = $db2->doSql($sql2);
        $ticket = $data2['ticket'];
        $type = 'normal';
        /*echo $rut.'<br/>';
        echo $data2['ticket'].'<br/>';
        echo $module.'<br/>';
        echo $submodule.'<br/>';
        */

        $tickets[] = array(
            'ticket' => $ticket,
            'module' => $module,
            'submodule' => $submodule,
            'type' => $type
        );
    }while($data = pg_fetch_assoc($db->actualResults));
    //echo json_encode($tickets);
}


$db3 = NEW DB();
$sql3 = "SELECT rut, datetime, m.name AS module, s.name AS submodule
        FROM logs l
        LEFT JOIN module m ON m.id=l.module
        LEFT JOIN submodule s ON s.id=l.sub_module
        WHERE description IN('Ticket ha venido')
        AND rut='$rutDer' AND datetime>'$datetimeDer' LIMIT 1";

$data3 = $db3->doSql($sql3);
if($data3){
    do{
        $rut = $data3['rut'];
        $datetime = $data3['datetime'];
        $module = $data3['module'];
        $submodule = $data3['submodule'];
        
        $db4 = NEW DB();

        $sql4 = "SELECT * FROM tickets t
                LEFT JOIN logs l ON l.id=t.logs
                WHERE rut='$rut' AND datetime>='$datetime' ORDER BY t.id LIMIT 1";
        $data4 = $db2->doSql($sql2);
        $ticket = $data4['ticket'];
        $type = 'derivado';
        /*echo $rut.'<br/>';
        echo $data2['ticket'].'<br/>';
        echo $module.'<br/>';
        echo $submodule.'<br/>';
        */

        $tickets[] = array(
            'ticket' => $ticket,
            'module' => $module,
            'submodule' => $submodule,
            'type' => $type
        );
    }while($data3 = pg_fetch_assoc($db3->actualResults));
    //echo json_encode($tickets);
}


if($tickets){
    echo json_encode($tickets);
}else{
    //echo "nananananananaBatman!";
    echo 0;
}




?>