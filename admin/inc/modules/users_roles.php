<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<?
include("libs/db.class.php");
include("controls.php");

$roles = $_GET['roles'];
$toForm = "?users=$roles";

if(isset($_GET['data']))
{
	$data = $_GET['data'];
	if($data!="")
	{
		$db=new DB();
		$num=0;
		$dataExp=explode(",", $data, -1);
		foreach($dataExp as $item)
		{
			$sql="INSERT into users_roles(users, roles) values(".$roles.", ".$item.")";
			$db->doSql($sql);
			$num++;
		}
		$msg="Se insertaron ".$num." Registros";
	}
	else
	{
		$msg="No se ha insertado nada aun!...";
		//header("location: main.php?module=exam_mod&users_exam=".$id_user."&msg=".$msg);
	}
	echo '<script>window.location.href="main.php?modulo=users_roles&roles='.$roles.'&msg='.$msg.'";</script>';
}

$users_roles = new DB("users_roles", "id");
$users_roles->relation("users", "users", "id");
$users_roles->additions("users", array("realname"=>"usersrealname"));
$users_roles->relation("roles", "roles", "id");
$users_roles->additions("roles", array("obs"=>"Descripcion"));
$users_roles->exceptions(array("users"));

makeControls($users_roles, NULL, "modules/users_rolesDelete.php", NULL, $_SERVER['HTTP_REFERER']);
$db = new DB ();
$sql=("SELECT id from users  where id=$roles");
$nameUsers = $db->doSql($sql);
$users_roles->showControls();

echo '<div algin="center" id="showTitle">Roles del Usuario: '.$nameUsers['name'].' '.$nameUsers['lastname'].'</div>';

//echo '<td align="right"><div align="center"><p><img src="../images/control/excel.png" id="excel" class="botonExcel" onclick="envia();" /></p>';
//echo '<form action="/newbioris/inc/modules/payment/archivoExcel.php" method="post" target="_blank" id="FormularioExportacion">';
//echo '<a href=”/modules/payment/archivoExcel.php”>Exportar</a>';
$rows = $users_roles->select(array("users"=>$roles));
echo $users_roles->showData($rows, TRUE);
?>
<script>
function envia(){
	FormularioExportacion.tabla.value=document.getElementById('showData').innerHTML;
	FormularioExportacion.submit();
}
</script>
