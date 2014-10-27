<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>

<?
include("libs/db.class.php");
include("controls.php");

$roles = new DB("roles", "id");

$pwd = $_GET['pwd'];
if($pwd=='role_roles')  { makeControls($roles, NULL , NULL , NULL , $_SERVER['HTTP_REFERER']); }
elseif($pwd=='users_roles') { makeControls($roles, NULL , NULL , NULL , $_SERVER['HTTP_REFERER']); }
else makeControls($roles, "modules/rolesForm.php", "modules/rolesDelete.php", "modules/rolesUpdate.php", $_SERVER['HTTP_REFERER']);


$roles->showControls();
echo '<div algin="center" id="showTitle">PERMISOS DE USUARIO</div>';

if(isset($_GET['roles']))
{
	$rol=$_GET['roles'];
	$where=array(""=>" roles.id NOT IN (SELECT roles FROM users_roles WHERE users=$rol)");
}
if(isset($_GET['role']))
{
	$rol=$_GET['role'];
	$where=array(""=>" roles.id NOT IN (SELECT roles FROM role_roles WHERE role=$rol)");
}

$rows = $roles->select($where);
echo $roles->showData($rows, TRUE);
?>
