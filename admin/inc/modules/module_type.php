<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }

include("libs/db.class.php");
include("controls.php");

$module_type = new DB("module_type", "id");
$module_type->changeItemInShow("color", '<table align="center" border="0" width="40"><tr><td bgcolor="%value%">&nbsp;</td></tr></table>');

makeControls($module_type, "modules/module_typeForm.php", "modules/module_typeDelete.php", "modules/module_typeUpdate.php", $_SERVER['HTTP_REFERER']);

$module_type->showControls();
echo '<div algin="center" id="showTitle">Tipos de modulo</div>';

$module_type->changeItemInShowIf("shape", "==", "circulo", "replaceWithImage", "../images/geometrics/circulo.png");
$module_type->changeItemInShowIf("shape", "==", "cuadrado", "replaceWithImage", "../images/geometrics/cuadrado.png");
$module_type->changeItemInShowIf("shape", "==", "triangulo", "replaceWithImage", "../images/geometrics/triangulo.png");
$module_type->changeItemInShowIf("shape", "==", "rombo", "replaceWithImage", "../images/geometrics/rombo.png");
$module_type->changeItemInShowIf("shape", "==", "pentagono", "replaceWithImage", "../images/geometrics/pentagono.png");
$module_type->changeItemInShowIf("shape", "==", "hexagono", "replaceWithImage", "../images/geometrics/hexagono.png");
$module_type->changeItemInShowIf("shape", "==", "octogono", "replaceWithImage", "../images/geometrics/octogono.png");
$module_type->changeItemInShowIf("shape", "==", "estrella", "replaceWithImage", "../images/geometrics/estrella.png");

//$where = array();
$rows = $module_type->select();
echo $module_type->showData($rows, TRUE);
?>
