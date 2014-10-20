<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }
?>
<link href="../../style/styleAll.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$module_derivation = new DB("module_derivation", "id");
$data = $_REQUEST;

if($module_derivation->deleteData($data)) 
{ 
	echo '<p id="d_true"><img src="../../images/delete.png"/>Items borrados con exito!</p>'; 
}
else 
{
	echo 'error al borrar algunos de los items'; 
}
echo '<br><div id="back"><a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/back.png"/>Volver al menu de modulos a derivar</a></div><br>';
?>
