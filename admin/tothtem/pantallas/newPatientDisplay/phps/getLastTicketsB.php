<?php
include ('../../../../inc/libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);


$zone = $_REQUEST['zone'];

$db = NEW DB();


$sql = "SELECT rut, datetime, m.name AS module, s.name AS submodule
        FROM logs l
        LEFT JOIN module m ON m.id=l.module
        LEFT JOIN submodule s ON s.id=l.sub_module
        WHERE datetime>'2015-02-23' AND description='Ticket ha venido'
        AND l.zone=$zone
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
            /*echo $rut.'<br/>';
            echo $data2['ticket'].'<br/>';
            echo $module.'<br/>';
            echo $submodule.'<br/>';
*/

            $tickets[] = array(
                'ticket' => $ticket,
                'module' => $module,
                'submodule' => $submodule
            );
        }while($data = pg_fetch_assoc($db->actualResults));
        echo json_encode($tickets);
    }else{
        //echo "nananananananaBatman!";
        echo 0;
    }




?>