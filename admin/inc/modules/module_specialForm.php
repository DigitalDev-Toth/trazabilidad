<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");

$module_special = new DB("module_special", "id");
$module_special->exceptions(array("id"));

$module_special->relation("module", "module", "id", "name");
//$module_special->relation("users", "users", "id", "name", array("null"=>"no aplica"));
//$module_special->changeFormObject('module_special.state', 'menu', null, array("activo"=>"activo", "inactivo"=>"inactivo"));
/*$module_special->toolTipInFormObject('module_special.max_wait', ' - tiempo maximo en minutos que un paciente puede esperar');
$module_special->toolTipInFormObject('module_special.position', ' - Posicion en la pantalla de visualizacion de las zonas');*/

if (isset($_GET['update']))
{
	$module_special->updateData($_GET['update'], FALSE);
}
else
{
	//$module_special->checkItemIfExist("module_special_name", "module_special", "name");
	echo '<div algin="center" id="showTitle">INSERTAR MODULO ESPECIAL</div>';
	if($module_special->insertData(FALSE))
	{
		echo '<br><div id="back">';
		echo '<a href="../contentMain.php?module=module_special"><img src="../../images/back.png"/>Volver al menu de Modulo Especial</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar Nuevo Modulo Especial</a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="back"><a href="../contentMain.php?module=module_special"><img src="../../images/back.png"/>Volver al menu de Modulo Especial</a></div><br>';
}
?>
