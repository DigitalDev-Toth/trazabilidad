<?php
include ('libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);
//get data

$module = $_REQUEST['module'];
$moduleSpecial = $_REQUEST['special'];

if($moduleSpecial!='0'){
	//get last ticket
	$db = NEW DB();
	$sql = "SELECT zone.name AS z ,module_special.name AS m
			FROM zone 
			left join module on module.zone=zone.id
			left join module_special on module_special.module = module.id
			where module_special.id = $moduleSpecial ";
	$names = $db->doSql($sql);

	

}else{
	$db = NEW DB();
	$sql = "SELECT zone.name AS z ,module.name AS m
			FROM zone 
			left join module on module.zone = zone.id 
			where module.id = $module";

	$names = $db->doSql($sql);


}

$results = array('zone' => $names['z'] ,'module' => $names['m']);
echo json_encode($results);
?>

