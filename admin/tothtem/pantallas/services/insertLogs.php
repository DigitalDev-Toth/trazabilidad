<?php
include ('../../../inc/libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);



//rut: description,action:,subModule:,cometType:

$rut = $_REQUEST['rut'];
$description =$_REQUEST['description'];
$action=$_REQUEST['action'];
$cometType=$_REQUEST['cometType'];
$subModule=$_REQUEST['subModule'];
$datetime = date("Y-m-d H:i:s");

$attentionOriginal = $_REQUEST['attentionOriginal'];
$attentionNew = $_REQUEST['attentionNew'];


//$sbIp = $_SERVER['REMOTE_ADDR'];
//Obtener Id Ticket para actualizar
/*$dbT = NEW DB();
$sql = "SELECT t.id 
		FROM tickets t
		LEFT JOIN logs l ON l.id=t.logs
		WHERE l.rut='$rut' AND t.attention='$attentionOriginal'";
$result = $dbT->doSql($sql);
$id=$result['id'];*/




$sqlZone="SELECT zone.id AS zone , submodule.id AS submodule, submodule.module AS module
		  FROM zone 
		  LEFT JOIN module ON module.zone = zone.id 
		  LEFT JOIN submodule ON submodule.module=module.id 
		  WHERE submodule.id='$subModule'";

$db2=NEW DB();
$results=$db2->doSql($sqlZone);
$zone=$results['zone'];
$subModule=$results['submodule'];
$module=$results['module'];

$db = NEW DB();
$sql = "INSERT INTO logs(rut,datetime,description,zone,action,sub_module,module) VALUES('$rut','$datetime','$description',$zone,'$action',$subModule,$module)";

$db->doSql($sql);


//Obtener LOG
$dbLogNew = NEW DB();
$sqlLogNew = "SELECT id FROM logs ORDER BY id DESC LIMIT 1";
$resultLogNew = $dbLogNew->doSql($sqlLogNew);
$log=$resultLogNew['id'];

$db3 = NEW DB();
$idTicket = $_REQUEST['ticketId'];
$sqlTicket = "UPDATE tickets SET attention='$attentionNew', logs=$log WHERE id=$idTicket";
$db3->doSql($sqlTicket);


//Comet es el encabezado que corresponde al tipo de comet a entregar: ejemplo : comet tipo tothtem , comet tipo gestion

$returnComet = array('comet' => $cometType,'rut' => $rut, 'datetime' => $datetime, 'description' => $description, 'zone' => $zone, 'action' => $action, 'submodule' => $subModule, 'module' => $module);
echo json_encode($returnComet);
?>