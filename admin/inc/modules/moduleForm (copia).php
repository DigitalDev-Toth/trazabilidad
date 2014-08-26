<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$module = new DB("module", "id");
$module->exceptions(array("id"));
$module->relation("zone", "zone", "id", "name");
$module->relation("module_type", "type", "id", "name");
$module->changeFormObject('module.position', 'menu', null, array("superior-izquierda"=>"superior-izquierda", "superior"=>"superior", "superior-derecha"=>"superior-derecha",
																"derecha"=>"derecha", "inferior-derecha"=>"inferior-derecha",
																"inferior"=>"inferior", "inferior-izquierda"=>"inferior-izquierda",
																"izquierda"=>"izquierda"));
$module->toolTipInFormObject('module.max_wait', ' - tiempo maximo en minutos que un paciente puede esperar');
$module->toolTipInFormObject('module.position', ' - Posicion en la pantalla de visualizacion de las zonas');

if (isset($_GET['update']))
{
	$module->updateData($_GET['update'], FALSE);
}
else
{
	//$module->checkItemIfExist("module_name", "module", "name");
	echo '<div algin="center" id="showTitle">INSERTAR MODULO</div>';
	if($module->insertData(FALSE))
	{
		//{"comet":"tothtem","rut":"17.443.625-8","datetime":"2014-08-25 09:59:53","description":"Ingreso de RUT Totem","zone":"1","action":"in","submodule":"1","module":"1"}
		//$comet = '{"comet":"f5"}';
		$comet['comet']="f5";
		echo '<br><div id="back">';
		echo '<a href="../contentMain.php?module=module"><img src="../../images/back.png"/>Volver al menu de Modulos</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar Nuevo Modulo</a><br>';
		echo '</div>';
	    echo '<script src="../js/jquery-1.8.1.min.js"></script>';
		echo '<script>
			$.ajax({
	        url: "../../../visor/comet/backend.php",
	        type: "POST",
	        contentType: "application/json; charset=utf-8",
	        //dataType: "default: Intelligent Guess (Other values: xml, json, script, or html)",
	        data: {msg: JSON.stringify('.json_encode($comet).')},
	    });
				
			</script>';
		exit();
	}
	echo '<br><div id="back"><a href="../contentMain.php?module=module"><img src="../../images/back.png"/>Volver al menu de Modulos</a></div><br>';
}
?>
