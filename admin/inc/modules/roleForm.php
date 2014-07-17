<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$role = new DB("role", "id");
$role->exceptions(array("id"));
$role->changeFormObject('role.description', 'basicEditor');

if (isset($_GET['update']))
{
	$role->updateData($_GET['update'], FALSE);
}
else
{
	$role->checkItemIfExist("role_name", "role", "name");
	echo '<div algin="center" id="showTitle">INSERTAR ROL</div>';
	if($role->insertData(FALSE))
	{
		echo '<br><div id="back">';
		echo '<a href="../contentMain.php?module=role"><img src="../../images/back.png"/>Volver al menu de Roles</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar Nuevo ROL</a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="back"><a href="../contentMain.php?module=role"><img src="../../images/back.png"/>Volver al menu de Roles</a></div><br>';
}
?>
