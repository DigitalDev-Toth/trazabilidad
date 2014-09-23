<?php
include ('../../../inc/libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);


$subModule=$_REQUEST['subModule'];

if(!isset($_REQUEST['module'])){
	$sqlZone="SELECT zone.id AS zone, submodule.module AS module
			  FROM zone 
			  LEFT JOIN module ON module.zone = zone.id 
			  LEFT JOIN submodule ON submodule.module=module.id 
			  WHERE submodule.id='$subModule'";
	$db2=NEW DB();
	$results=$db2->doSql($sqlZone);
	$zone=$results['zone'];
	$module=$results['module'];

}else{
	$module=$_REQUEST['module'];
	$sqlZone="SELECT zone
			  FROM module 
			  WHERE id=$module";
	$db2=NEW DB();
	$results=$db2->doSql($sqlZone);
	$zone=$results['zone'];
}

//Obtener LOG
$dbLogNew = NEW DB();
$sqlLogNew = "SELECT id FROM logs ORDER BY id DESC LIMIT 1";
$resultLogNew = $dbLogNew->doSql($sqlLogNew);
$log=$resultLogNew['id'];


//Obtener NUMERO TICKET
$dbTicket = NEW DB();
$sqlTicket2 = "SELECT ticket FROM tickets WHERE id=$idTicket";
$resultTicket = $dbTicket->doSql($sqlTicket2);
$newticket=$resultTicket['ticket'];

//Comet es el encabezado que corresponde al tipo de comet a entregar: ejemplo : comet tipo tothtem , comet tipo gestion

$returnComet = array('zone' => $zone, 'submodule' => $subModule, 'module' => $module, 'newticket' => $newticket);
echo json_encode($returnComet);
?>