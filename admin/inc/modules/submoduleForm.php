<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$submodule = new DB("submodule", "id");
$submodule->exceptions(array("id"));

$submodule->relation("module", "module", "id", "name");
$submodule->relation("users", "users", "id", "name", array("null"=>"no aplica"));
$submodule->changeFormObject('submodule.state', 'menu', null, array("activo"=>"activo", "inactivo"=>"inactivo"));
/*$submodule->toolTipInFormObject('submodule.max_wait', ' - tiempo maximo en minutos que un paciente puede esperar');
$submodule->toolTipInFormObject('submodule.position', ' - Posicion en la pantalla de visualizacion de las zonas');*/

if (isset($_GET['update']))
{
	$submodule->updateData($_GET['update'], FALSE);
}
else
{
	//$submodule->checkItemIfExist("submodule_name", "submodule", "name");
	echo '<div algin="center" id="showTitle">INSERTAR SUB-MODULO</div>';
	if($submodule->insertData(FALSE))
	{
		echo '<br><div id="back">';
		echo '<a href="../contentMain.php?module=submodule"><img src="../../images/back.png"/>Volver al menu de Sub-Modulos</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar Nuevo Sub-Modulo</a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="back"><a href="../contentMain.php?module=submodule"><img src="../../images/back.png"/>Volver al menu de Sub-Modulos</a></div><br>';
}
?>
