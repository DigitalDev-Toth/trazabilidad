<?php

session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf8');  }

include("libs/db.class.php");
include("controls.php");

$module_special = new DB("module_special", "id");
//$module_special->additions("module", array("name"=>"modulename"));
$module_special->relation("module", "module", "id", "name");
$module_special->additions("module", array("name"=>"module"));

/*
$module_special->insertExternalInShow('Usuario actual', 'http://localhost/traza_enzo/admin/inc/modules/subModuleTime.php?module_special=%id%&type=user');
$module_special->insertExternalInShow('Hora inicio', 'http://localhost/traza_enzo/admin/inc/modules/subModuleTime.php?module_special=%id%&type=ini');
$module_special->insertExternalInShow('Tiempo de Actividad', 'http://localhost/traza_enzo/admin/inc/modules/subModuleTime.php?module_special=%id%&type=total');
*/
//$module_special->changeItemInShow("state", '<a href="?modulo=module_special&id=%id%&state=%value%"><img src="../images/%value%.png"></a>');

makeControls($module_special, "modules/module_specialForm.php", "modules/module_specialDelete.php", "modules/module_specialUpdate.php", $_SERVER['HTTP_REFERER']);

$module_special->showControls();
echo '<div algin="center" id="showTitle">Modulos Especiales</div>';

//$where = array();
$rows = $module_special->select();
echo $module_special->showData($rows, TRUE);
?>