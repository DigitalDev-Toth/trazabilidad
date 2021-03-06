<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }

include("libs/db.class.php");
include("controls.php");

$module = new DB("module", "id");
$module->exceptions(array("zone", "type"));
$module->relation("zone", "zone", "id", "name");
$module->additions("zone", array("name"=>"zonename"));
$module->relation("module_type", "type", "id", "name");
$module->additions("module_type", array("name"=>"modulename"));
$module->insertLinkInShow("module", "#", "../images/derivation.png", 'onclick="PopupCenter('."'main.php?modulo=module_derivation&###'".', '."'module'".', 800, 600)"', "Derivar", NULL, array("table"=>"module_derivation", "field"=>"module", "image"=>"../images/derivationBack.png"));

makeControls($module, "modules/moduleForm.php", "modules/moduleDelete.php", "modules/moduleUpdate.php", $_SERVER['HTTP_REFERER']);

$module->showControls();
echo '<div algin="center" id="showTitle">Modulos</div>';

//$where = array();
$rows = $module->select();
echo $module->showData($rows, TRUE);
?>
