<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>

<?
include("libs/db.class.php");
include("controls.php");

$modules = new DB("module", "id");
$modules->relation("zone", "zone", "id", "name");
$modules->additions("zone", array("name"=>"zone"));
$modules->relation("module_type", "type", "id", "name");
$modules->additions("module_type", array("name"=>"type"));
makeControls($modules, NULL , NULL , NULL , $_SERVER['HTTP_REFERER']);


$modules->showControls();
echo '<div algin="center" id="showTitle">MODULOS</div>';




if(isset($_GET['modules']))
{
	$module=$_GET['modules'];
	$where=array(""=>" module.id NOT IN (SELECT module FROM users_modules WHERE users=$module)");
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
