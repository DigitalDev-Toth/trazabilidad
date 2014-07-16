<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$module = new DB("module", "id");
$module->exceptions(array("id"));
//$module->changeFormObject('module.description', 'basicEditor');
//$module->toolTipInFormObject('module.seats', ' - Numero de asientos maximo de la sala de espera.');

if (isset($_GET['update']))
{
	$module->updateData($_GET['update'], FALSE);
}
else
{
	$module->checkItemIfExist("module_name", "module", "name");
	echo '<div algin="center" id="showTitle">INSERTAR MODULO</div>';
	if($module->insertData(FALSE))
	{
		echo '<br><div id="back">';
		echo '<a href="../contentMain.php?module=module"><img src="../../images/back.png"/>Volver al menu de Modulos</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar Nuevo Modulo</a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="back"><a href="../contentMain.php?module=module"><img src="../../images/back.png"/>Volver al menu de Modulos</a></div><br>';
}
?>
