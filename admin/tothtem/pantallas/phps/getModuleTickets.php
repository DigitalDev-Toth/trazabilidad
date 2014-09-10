<?php
include ('../scripts/libs/db.class.php');
//get data
$zone = $_REQUEST['zone'];

//get modality name
$db = NEW DB();
$sql = "SELECT m.id AS id, m.name AS name
		FROM module m
		LEFT JOIN module_type mt ON m.type=mt.id
		WHERE m.zone=$zone AND NOT mt.name='Tothtem'";

$modules = $db->doSql($sql);

if($modules){
    do{
		$sql2 = "SELECT t.ticket AS ticket
			FROM tickets t
			LEFT JOIN logs l ON l.id=t.logs
			WHERE l.module=".$modules['id']." AND t.attention IN ('waiting','derived') AND l.datetime>'2014-09-05' ORDER BY l.datetime LIMIT 1";
			
		$db2 = NEW DB();
        $moduleTickets = $db2->doSql($sql2);

        $data[] = array(
        	'moduleId' => $modules['id'],
            'moduleName' => $modules['name'],
            'moduleTicket' => $moduleTickets['ticket']
        );

    }while($modules = pg_fetch_assoc($db->actualResults));
}

echo json_encode($data);

?>