<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }
?>

<?
include("libs/db.class.php");
include("controls.php");

$modules = new DB("module", "id");
$modules->exceptions(array("zone", "type","position","max_wait","alias"));
makeControls($modules, NULL , NULL , NULL , $_SERVER['HTTP_REFERER']);


$modules->showControls();
echo '<div algin="center" id="showTitle">MODULOS</div>';




if(isset($_GET['module']))
{
	$module=$_GET['module'];
	$where=array(""=>" module.id NOT IN (SELECT module_derivation FROM module_derivation WHERE module=$module) AND module.zone = (SELECT zone FROM module WHERE id=$module) AND NOT module.type=1");
	//$where=array(""=>" module_derivation.id NOT IN (SELECT module_derivation FROM module_derivation WHERE module=$module) AND module.zone=(SELECT zone FROM module WHERE id=$module)");
	//$where=array(""=>" module.id NOT IN (SELECT module FROM users_roles WHERE users=$rol)");
}
/*
if(isset($_GET['role']))
{
	$rol=$_GET['role'];
	$where=array(""=>" roles.id NOT IN (SELECT roles FROM role_roles WHERE role=$rol)");
}
*/



$rows = $modules->select($where);

echo $modules->showData($rows, TRUE);
?>
