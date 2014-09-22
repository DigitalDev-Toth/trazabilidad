<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<?
include("libs/db.class.php");
include("controls.php");

$module = $_GET['module'];
$toForm = "?users=$module";

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
			$sql="INSERT INTO users_modules(users, module) values(".$module.", ".$item.")";
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
	echo '<script>window.location.href="main.php?modulo=users_modules&module='.$module.'&msg='.$msg.'";</script>';
}

$users_modules = new DB("users_modules", "id");
$users_modules->relation("users", "users", "id");
$users_modules->additions("users", array("realname"=>"usersrealname"));
$users_modules->relation("module", "module", "id","name");
$users_modules->additions("module", array("name"=>"module") );
//$users_modules->additions("module", array("obs"=>"Descripcion"));
$users_modules->exceptions(array("users"));

/*$module->relation("zone", "zone", "id", "name");
$module->additions("zone", array("name"=>"zonename"));
*/


makeControls($users_modules, NULL, "modules/users_modulesDelete.php", NULL, $_SERVER['HTTP_REFERER']);
$db = new DB ();
$sql=("SELECT id from users  where id=$module");
$nameUsers = $db->doSql($sql);
$users_modules->showControls();

echo '<div algin="center" id="showTitle">Modulos del Usuario: '.$nameUsers['name'].' '.$nameUsers['lastname'].'</div>';

//echo '<td align="right"><div align="center"><p><img src="../images/control/excel.png" id="excel" class="botonExcel" onclick="envia();" /></p>';
//echo '<form action="/newbioris/inc/modules/payment/archivoExcel.php" method="post" target="_blank" id="FormularioExportacion">';
//echo '<a href=”/modules/payment/archivoExcel.php”>Exportar</a>';
$rows = $users_modules->select(array("users"=>$module));
echo $users_modules->showData($rows, TRUE);
?>
<script>
function envia(){
	FormularioExportacion.tabla.value=document.getElementById('showData').innerHTML;
	FormularioExportacion.submit();
}
</script>
