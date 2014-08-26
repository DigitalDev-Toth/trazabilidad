<?php
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');
//get data

$submodule = $_REQUEST['submodule'];
$attention=$_REQUEST['attention'];
$tickets=$_REQUEST['tickets'];

try {
	$db1 = NEW DB();
	$sql1 = "SELECT t.ticket AS ticket, s.module AS module, l.rut AS rut_patient 
			FROM tickets t
			LEFT JOIN logs l ON l.id=t.logs
			LEFT JOIN submodule s ON s.module=l.module
			WHERE s.id=$submodule AND t.attention='waiting' AND CAST(t.ticket AS INT)>=$tickets ORDER BY t.id ASC LIMIT 1";
	$result1 = $db1->doSql($sql1);

	$lastTicket=$result1['ticket'];
	$module=$result1['module'];
	$rutPatient=$result1['rut_patient'];
//echo $lastTicket.' - '.$modalityN.' - '.$rutPatient;

	$db = NEW DB();
	$sql = "SELECT t.id 
			FROM tickets t
			LEFT JOIN logs l ON l.id=t.logs
			WHERE t.ticket='$lastTicket' AND l.module=$module AND l.rut='$rutPatient' AND t.attention='waiting'";
	$result = $db->doSql($sql);
	$id=$result['id'];

	//INSERCIÓN LOGS
	$rut = $rutPatient;
	$description = 'Ingreso a submódulo';
	$action = 'in';
	$cometType = 'subModule';
	$datetime = date("Y-m-d H:i:s");

	$sbIp = '192.168.0.122';

	$sqlZone="SELECT zone.id AS zone
			  FROM zone 
			  LEFT JOIN module ON module.zone = zone.id 
			  LEFT JOIN submodule ON submodule.module=module.id 
			  WHERE submodule.ip='$sbIp'";

	$dblog=NEW DB();
	$results=$dblog->doSql($sqlZone);
	$zone=$results['zone'];

	$sql = "INSERT INTO logs(rut,datetime,description,zone,action,sub_module,module) VALUES('$rut','$datetime','$description',$zone,'$action',$submodule,$module)";

	$dblog->doSql($sql);



	//Actualización de ticket con su estado de atención y nuevo log

	$dbLogNew = NEW DB();
	$sqlLogNew = "SELECT id FROM logs ORDER BY id DESC LIMIT 1";
	$resultLogNew = $dbLogNew->doSql($sqlLogNew);
	$log=$resultLogNew['id'];

	$db2 = NEW DB();
	$stringSql="UPDATE tickets SET attention='$attention', logs=$log WHERE id=$id";
	$db2->doSql($stringSql);


	//Comet es el encabezado que corresponde al tipo de comet a entregar: ejemplo : comet tipo tothtem , comet tipo gestion

	$returnComet = array('Comet' => $cometType,'rut' => $rut, 'datetime' => $datetime, 'description' => $description, 'zone' => $zone, 'action' => $action, 'subModule' => $submodule, 'module' => $module);
	echo json_encode($returnComet);

} catch (Exception $e) {
	//...
}


?>