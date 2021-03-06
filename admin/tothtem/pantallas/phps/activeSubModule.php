<?php
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');

$userId = $_REQUEST['user'];
$submoduleId = $_REQUEST['submodule'];
$type = $_REQUEST['type'];

$sqlZone="SELECT zone.id AS zone , submodule.id AS submodule, submodule.module AS module
		  FROM zone 
		  LEFT JOIN module ON module.zone = zone.id 
		  LEFT JOIN submodule ON submodule.module=module.id 
		  WHERE submodule.id='$submoduleId'";

$db2=NEW DB();
$results=$db2->doSql($sqlZone);
$zone=$results['zone'];
$subModule=$results['submodule'];
$module=$results['module'];

$state = $type;
if($type == 're-activo') $state='activo';

$dbSubModule=NEW DB();
$dbSubModule->doSql("UPDATE submodule SET state='$state' WHERE id=$subModule");

$rut = $userId;
if($type == 'activo'){
    $description ='Inicio de Sesión Usuario: '.$rut;
    $action = 'in';
}else if($type == 'inactivo'){
    $description ='Cierre de Sesión Usuario: '.$rut;
    $action = 'to';
}else if($type == 'pausado'){
    $motive = $_REQUEST['motive'];
    $description ='Pausa de Sesión Usuario: '.$rut.' Motivo: '.$motive;
    $action = 'to';
}else if($type == 're-activo'){
    $description ='Re-inicio de Sesión Usuario: '.$rut;
    $action = 'in';
}

$datetime = date("Y-m-d H:i:s");

$db = NEW DB();
$sql = "INSERT INTO logs(rut,datetime,description,zone,action,sub_module,module,users) VALUES('$rut','$datetime','$description',$zone,'$action',$subModule,$module,$userId)";
$db->doSql($sql);

$submoduleData = array('comet' => 'submodule', 'state' => $state, 'id' => $subModule);

echo json_encode($submoduleData);

?>