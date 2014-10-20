<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }
?>
<?
include("libs/db.class.php");
include("controls.php");

$module = $_GET['module'];
$toForm = "?module=$module";

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
			$sql="INSERT INTO module_derivation(module, module_derivation) values(".$module.", ".$item.")";
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
	echo '<script>window.location.href="main.php?modulo=module_derivation&module='.$module.'&msg='.$msg.'";</script>';
}

$module_derivation = new DB("module_derivation", "id");
$module_derivation->relation("module", "module_derivation", "id");
$module_derivation->additions("module", array("name"=>"module_derivation"));
//$module_derivation->relation("module", "module_derivation", "id","name");
//$module_derivation->additions("module", array("name"=>"module_derivation") );
//$module_derivation->additions("module", array("obs"=>"Descripcion"));
//$module_derivation->exceptions(array("users"));

/*$module->relation("zone", "zone", "id", "name");
$module->additions("zone", array("name"=>"zonename"));
*/
$module_derivation->exceptions(array("module"));

makeControls($module_derivation, NULL, "modules/module_derivationDelete.php", NULL, $_SERVER['HTTP_REFERER']);
$db = new DB ();
$sql=("SELECT name FROM module WHERE id=$module");
$nameModule = $db->doSql($sql);
$module_derivation->showControls();

echo '<div algin="center" id="showTitle">Modulos a derivar: '.$nameModule['name'].'</div>';

//echo '<td align="right"><div align="center"><p><img src="../images/control/excel.png" id="excel" class="botonExcel" onclick="envia();" /></p>';
//echo '<form action="/newbioris/inc/modules/payment/archivoExcel.php" method="post" target="_blank" id="FormularioExportacion">';
//echo '<a href=”/modules/payment/archivoExcel.php”>Exportar</a>';
$rows = $module_derivation->select(array("module"=>$module));
echo $module_derivation->showData($rows, TRUE);
?>
<script>
function envia(){
	FormularioExportacion.tabla.value=document.getElementById('showData').innerHTML;
	FormularioExportacion.submit();
}
</script>
