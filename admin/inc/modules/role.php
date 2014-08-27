<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }

include("libs/db.class.php");
include("controls.php");

$role = new DB("role", "id");
//$role->exceptions(array("role", "password"));

makeControls($role, "modules/roleForm.php", "modules/roleDelete.php", "modules/roleUpdate.php", $_SERVER['HTTP_REFERER']);

$role->showControls();
echo '<div algin="center" id="showTitle">Roles</div>';

//$where = array();
$rows = $role->select();
echo $role->showData($rows, TRUE);
?>
