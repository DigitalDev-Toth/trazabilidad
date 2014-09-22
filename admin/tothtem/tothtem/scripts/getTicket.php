<?php
include ('libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);
//get data
$module = $_REQUEST['module'];
$rut = $_REQUEST['rut'];
$moduleSpecial = $_REQUEST['moduleSpecial'];
$cometType = 'module';
//$sbIp=$_REQUEST['ip'];

if($moduleSpecial=='0'){
	//get last ticket
	$db = NEW DB();
	$sql = "SELECT ticket FROM last_tickets WHERE module='$module'";
	$lastTicket = $db->doSql($sql);
	$newticket=(int)$lastTicket["ticket"]+1; 
	if($newticket==1000){
	    $newticket=1;
	}
	//set last ticket
	$db2 = NEW DB();
	$db2->doSql("UPDATE last_tickets SET ticket='$newticket' WHERE module='$module'");
	$sbIp='192.168.0.122';
}else{
	//get last ticket
	$db = NEW DB();
	$sql = "SELECT last_ticket FROM module_special WHERE id='$moduleSpecial'";
	$lastTicket = $db->doSql($sql);
	$newticket=(int)$lastTicket["last_ticket"]+1; 
	if($newticket==1000){
	    $newticket=1;
	}
	//set last ticket
	$db2 = NEW DB();
	$db2->doSql("UPDATE module_special SET last_ticket=$newticket WHERE id='$moduleSpecial'");
	$sbIp='192.168.0.123';
}
//INSERCIÓN DE LOGS
//$sbIp='192.168.0.123';

$sqlZone="SELECT zone.id AS zone , submodule.id AS submodule 
          FROM zone 
          LEFT JOIN module ON module.zone = zone.id 
          LEFT JOIN submodule ON submodule.module=module.id 
          WHERE submodule.ip='$sbIp'";

$db3=NEW DB();
$results=$db3->doSql($sqlZone);
$zone=$results['zone'];
$subModule=$results['submodule'];

$datetime = date('Y-m-d H:i:s');
$description = "Retiro de ticket Nº $newticket , Módulo $module";
$dbLog = NEW DB();
$sql = "INSERT INTO logs(rut,datetime,description,zone,action,sub_module,module) VALUES('$rut','$datetime','$description',$zone,'to',$subModule,$module)";
$dbLog->doSql($sql);

$sql = "SELECT id FROM logs ORDER BY id DESC LIMIT 1";
$idLog = $dbLog->doSql($sql);


if($moduleSpecial=='0'){
	//Return module alias
	$dbAlias = NEW DB();
	$sql = "SELECT alias FROM module WHERE id=$module";
	$alias = $dbAlias->doSql($sql);
}else{
	//Return module alias
	$dbAlias = NEW DB();
	$sql = "SELECT alias FROM module_special WHERE id=$moduleSpecial";
	$alias = $dbAlias->doSql($sql);
}

//insert new ticket patient
$newticket=$newticket.$alias['alias'];
$db4 = NEW DB();
$db4->doSql("INSERT INTO tickets(logs,ticket,attention) VALUES (".$idLog['id'].",'$newticket','waiting')");

//$ticketData = array('newticket' => $newticket, 'modality' => $module, 'rut' => $rut);
//$ticketData[0] = array('newticket' => $newticket, 'modality' => $module, 'date_t' => date("Y-m-d"), 'hour_start' => date("H:i:s"), 'hour_end' => 'NaN', 'rut' => $rut);
//COMET para visualización
//$ticketData[1] = array('comet' => $cometType,'rut' => $rut, 'datetime' => $datetime, 'description' => $description, 'zone' => $zone, 'action' => 'to', 'submodule' => $subModule, 'module' => $module);
$ticketData = array('comet' => $cometType,'rut' => $rut, 'datetime' => $datetime, 'description' => $description, 'zone' => $zone, 'action' => 'to', 'submodule' => $subModule, 'module' => $module,'newticket' => "$newticket");

echo json_encode($ticketData);
?>

