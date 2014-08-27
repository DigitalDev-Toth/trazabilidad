<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>

<link href="../../style/styleAll.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$roles = new DB("roles", "id");
$roles->exceptions(array("id"));

if (isset($_GET['update']))
{
	$roles->updateData($_GET['update'], FALSE);
}
else
{
	echo '<div algin="center" id="showTitle">INSERTAR ROLES</div>';
	if($roles->insertData(FALSE))
	{
		echo '<br><div id="back">';
			echo '<a href="../main.php?modulo=roles"><img src="../../images/back.png"/>Volver al men&uacute; de roles</a>';
			echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar roles</a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="back"><a href="../main.php?modulo=roles"><img src="../../images/back.png"/>Volver al men&uacute de roles</a></div><br>';
}

?>
