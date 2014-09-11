<?php

session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }

include("libs/db.class.php");
include("controls.php");

$users = new DB("users", "id");
$users->exceptions(array("role", "password"));
$users->relation("role", "role", "id");
$users->additions("role", array("name"=>"rol"));
$users->changeItemInShow("color", '<table align="center" border="0" width="40"><tr><td bgcolor="%value%">&nbsp;</td></tr></table>');

//if(findRole($module, "show_roles")) {
	$users->insertLinkInShow("roles", "#", "../images/role.png", 'onclick="PopupCenter('."'main.php?modulo=users_roles&###'".', '."'roles'".', 800, 600)"', "Permisos", NULL, array("table"=>"users_roles", "field"=>"users", "image"=>"../images/roleBack.png"));
//}
makeControls($users, "modules/usersForm.php", "modules/usersDelete.php", "modules/usersUpdate.php", $_SERVER['HTTP_REFERER']);

$users->showControls();
echo '<div algin="center" id="showTitle">USUARIO</div>';

//$where = array();
$rows = $users->select();
echo $users->showData($rows, TRUE);
?>
