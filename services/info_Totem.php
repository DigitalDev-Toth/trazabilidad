<?php
include ('../admin/inc/libs/db.class.php');
include ('getTimeString.php');

$submodule = $_REQUEST['submodule'];
$date = date('Y-m-d');


$db = NEW DB();
$sql = "SELECT * FROM logs WHERE sub_module=$submodule AND action='to' AND datetime >= '$date' AND datetime < ('$date'::date + '1 day'::interval) ORDER BY module";
$logs = $db->doSql($sql);

do{
    $data[] = array(
        "id" => $logs['id'],
        "rut" => $logs['rut'],
        "datetime" => $logs['datetime'],
        "description" => $logs['description'],
        "module" => $logs['module']
    );
} while($logs=pg_fetch_assoc($db->actualResults));

$servedMaxTime=0;
$servedMinTime=0;
for($i=0;$i<count($data);$i++){

	$moduleCount[$data[$i]['module']]++;
	$servedTime = strtotime($data[$i]['datetime']);
	if($i==0)$servedMinTime = $servedTime;
	if($servedMaxTime<$servedTime) $servedMaxTime = $servedTime;
	if($servedMinTime>$servedTime) $servedMinTime = $servedTime;

}

echo '<br/>DB_TYPE: 1<br/>';
echo 'TOTAL EMITIDOS: '.count($data).'<br/>';
echo 'PRIMER EMITIDO: '.getTimeString($servedMinTime-strtotime(date('Y-m-d'))).'<br/>';
echo '&Uacute;LTIMO EMITIDO: '.getTimeString($servedMaxTime-strtotime(date('Y-m-d'))).'<br/>';

$totaltickets=count($data);
$firstissued=getTimeString($servedMinTime-strtotime(date('Y-m-d')));
$lastissued=getTimeString($servedMaxTime-strtotime(date('Y-m-d')));

$returnData = array('dbtype' => 1,'total_tickets' => $totaltickets, 'modules' => $moduleCount,'first_ticket' => $firstissued,'last_ticket' => $lastissued);
echo json_encode($returnData);


?>