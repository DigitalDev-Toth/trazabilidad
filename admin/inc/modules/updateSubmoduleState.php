<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);
include("../libs/db.class.php");
if(isset($_GET['state'])) {
	$db = new DB();
	$id = $_GET['id'];
	$state = $_GET['state'];
	if($state=='activo' || $state=='pausado') {
		$state = 'inactivo';
	} else {
		$state = 'activo';
	}
	$sql = "UPDATE submodule SET state='$state' WHERE id=$id";
	$db->doSql($sql);
	echo $state;
} else {
	echo "missing data!";
}
?>
