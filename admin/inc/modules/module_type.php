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

//$where = array();
$rows = $module_type->select();
echo $module_type->showData($rows, TRUE);
?>
