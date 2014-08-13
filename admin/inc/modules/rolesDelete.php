<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>

<link href="../../style/styleAll.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$roles = new DB("roles", "id");
$data = $_REQUEST;

if($roles->deleteData($data)) 
{ 
	echo '<br><br><p id="d_true"><img src="../../images/delete.png"/>Items borrados con exito!</p>'; 
}
else 
{ 
	echo 'error al borrar algunos de los items'; 
}
echo '<br><div id="back"><a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/back.png"/>Volver al men&uacute; de roles</a></div><br>';
?>
