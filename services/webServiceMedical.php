<?php
include ('../admin/inc/libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);


/*$rut = $_REQUEST['rut'];
$description =$_REQUEST['description'];
$sbIp=$_REQUEST['ip'];
$action=$_REQUEST['action'];
$cometType=$_REQUEST['cometType'];
$datetime = date("Y-m-d H:i:s");*/

//Hora de atención
/*$description = 'Hora de atención';
$action = 'to';
$datetime = '2014-10-29 15:30:00';*/
//Inicio de atención
/*$description = 'Inicio de atención';
$action = 'in';
$datetime = '2014-10-29 15:35:00';*/
//Fin de atención
$description = 'Fin de atención, plan de tratamientos: 0';
$action = 'lb';
$datetime = '2014-10-29 15:56:00';


$rut = '17.172.852-5';
$zone = 1;
$module = 34;
$submodule = 47;
$users = 0;

$db = NEW DB();
$sql = "INSERT INTO logs(rut,datetime,description,action,zone,module,sub_module,users) VALUES('$rut','$datetime','$description','$action',$zone,$module,$submodule,$users)";

$db->doSql($sql);

$returnComet = array('rut' => $rut, 'datetime' => $datetime, 'description' => $description, 'zone' => $zone, 'action' => $action, 'submodule' => $submodule, 'module' => $module, 'users' => $users);
echo json_encode($returnComet);
?>