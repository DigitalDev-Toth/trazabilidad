<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$users = new DB("users", "id");
$users->exceptions(array("id"));

//$users->relation("role", "role", "id", "name");

$users->relation("role", "role", "id", "name");
$users->changeFormObject('users.color', 'color');
$users->changeFormObject('users.state', 'menu', NULL, array('Activo'=>'activo','Inactivo'=>'inactivo'));

$users->checkItemIfExist("users_username", "users", "username");


if (isset($_GET['update']))
{
	$users->exceptions(array("password"));
	$users->changeFormObject('users.password', 'password', 'md5');
	//$users->fooAdditions("users.verifyPass");
	//$users->changeFormObject('users.verifyPass', 'password');
	//$users->reOrder('users.verifyPass','2');
	//$users->changeFormObject("saveButton", "submit", NULL, NULL, "verifyPass(users.users_password,users.users_verifyPass);");
	$users->updateData($_GET['update'], FALSE);
}
else
{
	$users->changeFormObject('users.password', 'password', 'md5');

	$users->fooAdditions("users.verifyPass");
	$users->changeFormObject('users.verifyPass', 'password');
	$users->reOrder('users.verifyPass','3');

	$users->changeFormObject("saveButton", "submit", NULL, NULL, "verifyPass(users.users_password,users.users_verifyPass);");

	echo '<div algin="center" id="showTitle">INSERTAR USUARIO</div>';
	if($users->insertData(FALSE))
	{
		echo '<br><div id="back">';
		echo '<a href="../contentMain.php?module=users"><img src="../../images/back.png"/>Volver al menu de USUARIO</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar USUARIO</a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="back"><a href="../contentMain.php?module=users"><img src="../../images/back.png"/>Volver al menu de USUARIO</a></div><br>';
}
?>
<script language="javascript">
	function verifyPass(pass, verify){
		if(pass.value!=''){
			if(pass.value!=verify.value)
			{
				alert('Contraseñas no Coinciden')					
				return false;
			} else {
				this.verify(document.users);
				return true;
			}
		} else {
			alert("no puede quedar password en blanco");
			return false;
		}
		//return true;		
	}
</script>
