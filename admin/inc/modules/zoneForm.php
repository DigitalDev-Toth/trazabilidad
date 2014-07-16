<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$zone = new DB("zone", "id");
$zone->exceptions(array("id"));
$zone->changeFormObject('zone.description', 'basicEditor');
$zone->toolTipInFormObject('zone.seats', ' - Numero de asientos maximo de la sala de espera.');

if (isset($_GET['update']))
{
	$zone->updateData($_GET['update'], FALSE);
}
else
{
	$zone->checkItemIfExist("zone_name", "zone", "name");
	echo '<div algin="center" id="showTitle">INSERTAR ZONA</div>';
	if($zone->insertData(FALSE))
	{
		echo '<br><div id="back">';
		echo '<a href="../contentMain.php?module=zone"><img src="../../images/back.png"/>Volver al menu de Zonas</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar Nuevo Zona</a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="back"><a href="../contentMain.php?module=zone"><img src="../../images/back.png"/>Volver al menu de Zonas</a></div><br>';
}
?>
