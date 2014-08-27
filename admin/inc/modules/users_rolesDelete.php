<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<link href="../../style/styleAll.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$users_roles = new DB("users_roles", "id");
$data = $_REQUEST;

if($users_roles->deleteData($data)) 
{ 
	echo '<p id="d_true"><img src="../../images/delete.png"/>Items borrados con exito!</p>'; 
}
else 
{ 
	echo 'error al borrar algunos de los items'; 
}
echo '<br><div id="back"><a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/back.png"/>Volver al menu de roles del usuario</a></div><br>';
?>
