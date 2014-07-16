<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }

include("libs/db.class.php");
include("controls.php");

$module = new DB("module", "id");
//$module->exceptions(array("module", "password"));

makeControls($module, "modules/moduleForm.php", "modules/moduleDelete.php", "modules/moduleUpdate.php", $_SERVER['HTTP_REFERER']);

$module->showControls();
echo '<div algin="center" id="showTitle">Modulos</div>';

//$where = array();
$rows = $module->select();
echo $module->showData($rows, TRUE);
?>
