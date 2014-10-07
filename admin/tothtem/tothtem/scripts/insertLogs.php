<?php
include ('libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);
$rut = $_REQUEST['rut'];
$description =$_REQUEST['description'];
$sbIp=$_REQUEST['ip'];
$action=$_REQUEST['action'];
$cometType=$_REQUEST['cometType'];
$datetime = date("Y-m-d H:i:s");

$sbIp = '192.168.0.122';
//$sbIp = $_SERVER['REMOTE_ADDR'];

$sqlZone="SELECT zone.id AS zone , submodule.id AS submodule, submodule.module AS module
		  FROM zone 
		  LEFT JOIN module ON module.zone = zone.id 
		  LEFT JOIN submodule ON submodule.module=module.id 
		  WHERE submodule.ip='$sbIp'";

$db2=NEW DB();
$results=$db2->doSql($sqlZone);
$zone=$results['zone'];
$subModule=$results['submodule'];
$module=$results['module'];


if($action=='lv'){
	$ticketid =$_REQUEST['ticketid'];
	$sqlTicket="UPDATE tickets SET attention='no_serve' WHERE id=$ticketid";

	$db1=NEW DB();
	$db1->doSql($sqlTicket);

	$sqlModule="SELECT l.module AS module
		  FROM tickets t
		  LEFT JOIN logs l ON l.id=t.logs 
		  WHERE t.id=$ticketid";

	$db3=NEW DB();
	$resultsModule=$db3->doSql($sqlModule);
	$module=$resultsModule['module'];
}


$db = NEW DB();
$sql = "INSERT INTO logs(rut,datetime,description,zone,action,sub_module,module) VALUES('$rut','$datetime','$description',$zone,'$action',$subModule,$module)";

$db->doSql($sql);


//Comet es el encabezado que corresponde al tipo de comet a entregar: ejemplo : comet tipo tothtem , comet tipo gestion

$returnComet = array('comet' => $cometType,'rut' => $rut, 'datetime' => $datetime, 'description' => $description, 'zone' => $zone, 'action' => $action, 'submodule' => $subModule, 'module' => $module);
echo json_encode($returnComet);
?>