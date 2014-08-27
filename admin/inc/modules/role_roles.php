<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>

<?
include("libs/db.class.php");
include("controls.php");

$roles = $_GET['role'];
$toForm = "?role=$roles";

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
			$sql="INSERT into role_roles(role, roles) values(".$roles.", ".$item.")";
			$db->doSql($sql);
			$num++;
		}
		$msg="Se insertaron ".$num." Registros";
	}
	else
	{
		$msg="No se ha insertado nada aun!...";
		//header("location: main.php?module=exam_mod&role_exam=".$id_user."&msg=".$msg);
	}
	echo '<script>window.location.href="main.php?module=role_roles&role='.$roles.'&msg='.$msg.'";</script>';
}

$role_roles = new DB("role_roles", "id");
$role_roles->relation("role", "role", "id");
$role_roles->additions("role", array("realname"=>"rolerealname"));
$role_roles->relation("roles", "roles", "id");
$role_roles->additions("roles", array("obs"=>"roleslabel"));
$role_roles->exceptions(array("role","roles"));

makeControls($role_roles, NULL, "modules/administration/role_rolesDelete.php", NULL, $_SERVER['HTTP_REFERER']);
$role_roles->showControls();
echo '<div algin="center" id="showTitle">Roles del Tipo de Usuario</div>';

$rows = $role_roles->select(array("role"=>$roles));

echo $role_roles->showData($rows, TRUE);

?>
