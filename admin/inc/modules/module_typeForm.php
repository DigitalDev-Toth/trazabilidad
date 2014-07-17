<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$module_type = new DB("module_type", "id");
$module_type->exceptions(array("id"));
$module_type->changeFormObject('module_type.description', 'basicEditor');
$module_type->changeFormObject('module_type.color', 'color');
$module_type->toolTipInFormObject('module_type.shape', ' - Forma que toma el paciente cuando esta a la espera de un modulo');
$module_type->toolTipInFormObject('module_type.color', ' - Color que toma el modulo en la visualizacion de las zonas');
$module_type->changeFormObject('module_type.shape', 'menu', null, array("circulo"=>"circulo", "cuadrado"=>"cuadrado", "triangulo"=>"triangulo",
																"rombo"=>"rombo", "pentagono"=>"pentagono",
																"hexagono"=>"hexagono", "octogono"=>"octogono", "estrella"=>"estrella"));

if (isset($_GET['update']))
{
	$module_type->updateData($_GET['update'], FALSE);
}
else
{
	$module_type->checkItemIfExist("module_type_name", "module_type", "name");
	echo '<div algin="center" id="showTitle">INSERTAR TIPO DE MODULO</div>';
	if($module_type->insertData(FALSE))
	{
		echo '<br><div id="back">';
		echo '<a href="../contentMain.php?module=module_type"><img src="../../images/back.png"/>Volver al menu de Tipos de Modulo</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar Nuevo Tipo de Modulo</a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="back"><a href="../contentMain.php?module=module_type"><img src="../../images/back.png"/>Volver al menu de Tipos de Modulo</a></div><br>';
}
?>
