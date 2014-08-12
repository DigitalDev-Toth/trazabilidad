<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>


<link href="../../style/styleAll.css" rel="stylesheet" type="text/css" />
<?

$roles = $_GET['users'];

include("../libs/db.class.php");
$users_roles = new DB("users_roles", "id");
$users_roles->exceptions(array("id"));
$users_roles->relation("users", "users", "id", "realname");
$users_roles->relation("roles", "roles", "id", "label");

if (isset($_GET['update']))
{
	$users_roles->updateData($_GET['update'], FALSE);
}
else
{
	echo '<div algin="center" id="showTitle">INSERTAR ROLES</div>';
	if($users_roles->insertData(FALSE))
	{
		echo '<br><div id="back">';
		echo '<a href="../main.php?modulo=users_roles&roles=$roles"><img src="../../images/back.png"/>Volver al menu de Roles del Usuario</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar </a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="back"><a href="../main.php?modulo=users_roles&roles=$roles"><img src="../../images/back.png"/>Volver al menu de Roles del Usuario</a></div><br>';
}

?>
