<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }
?>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");

function sendComet(){
	$comet = '{"comet":"f5"}';
	echo '<script src="../js/jquery-1.8.1.min.js"></script>';
	echo '<script>
		    $.post("../../../visor/comet/backend.php",{msg: JSON.stringify('.$comet.')},function(data, textStatus, xhr){
		    });
		</script>';
}

$module = new DB("module", "id");
$module->exceptions(array("id"));
$module->relation("zone", "zone", "id", "name");
$module->relation("module_type", "type", "id", "name");
$position = array("superior-izquierda"=>"superior-izquierda", "superior"=>"superior", "superior-derecha"=>"superior-derecha",
				"derecha"=>"derecha", "inferior-derecha"=>"inferior-derecha","inferior"=>"inferior", 
				"inferior-izquierda"=>"inferior-izquierda","izquierda"=>"izquierda");
$alias = array("A"=>"A","B"=>"B","C"=>"C","D"=>"D","E"=>"E","F"=>"F","G"=>"G","H"=>"H","I"=>"I","J"=>"J","K"=>"K","L"=>"L","M"=>"M","N"=>"N","O"=>"O","P"=>"P","Q"=>"Q");
$module->changeFormObject('module.module_zone', 'hidden');
/*
$dbPosition = new DB();
$sqlPosition = "SELECT position FROM module WHERE zone=1";
$resultPosition = $dbPosition->doSql($sqlPosition);
do{//Quita los registros ya existentes de posición
	unset($position[$resultPosition['position']]);
}while($resultPosition=pg_fetch_assoc($dbPosition->actualResults));


$dbAlias = new DB();
$sqlAlias = "SELECT alias FROM module WHERE zone=1 AND NOT alias=''";
$resultAlias = $dbAlias->doSql($sqlAlias);
do{//Quita los registros ya existentes de alias/prefijo
	unset($alias[$resultAlias['alias']]);
}while($resultAlias=pg_fetch_assoc($dbAlias->actualResults));


/*
$module->changeFormObject('module.position', 'menu', null, array("superior-izquierda"=>"superior-izquierda", "superior"=>"superior", "superior-derecha"=>"superior-derecha",
																"derecha"=>"derecha", "inferior-derecha"=>"inferior-derecha",
																"inferior"=>"inferior", "inferior-izquierda"=>"inferior-izquierda",
																"izquierda"=>"izquierda"));
*/

$module->changeFormObject('module.position', 'menu', null, $position);
$module->changeFormObject('module.alias', 'menu', null, $alias);
//$module->changeFormObject('module.alias', 'menu', null, array("A"=>"A","B"=>"B","C"=>"C","D"=>"D","E"=>"E","F"=>"F"));
$module->toolTipInFormObject('module.max_wait', ' - tiempo maximo en minutos que un paciente puede esperar');
$module->toolTipInFormObject('module.position', ' - Posicion en la pantalla de visualizacion de las zonas');


if (isset($_GET['update']))
{
	$module->updateData($_GET['update'], FALSE);
	sendComet();
}
else
{
	//$module->checkItemIfExist("module_name", "module", "name");
	echo '<div algin="center" id="showTitle">INSERTAR MODULO</div>';
	if($module->insertData(FALSE))
	{
		//{"comet":"tothtem","rut":"17.443.625-8","datetime":"2014-08-25 09:59:53","description":"Ingreso de RUT Totem","zone":"1","action":"in","submodule":"1","module":"1"}
		
		//$comet['comet']="f5";
		echo '<br><div id="back">';
		echo '<a href="../contentMain.php?module=module"><img src="../../images/back.png"/>Volver al menu de Modulos</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar Nuevo Modulo</a><br>';
		echo '</div>';

		$db = new DB();
		$sql = "SELECT id from module ORDER BY id DESC  limit 1 ";
		$result=$db->doSql($sql);
		$sql ="INSERT INTO last_tickets(ticket,module) VALUES('1',".$result['id'].") ";
		$db->doSql($sql);

		sendComet();
		exit();
	}
	echo '<br><div id="back"><a href="../contentMain.php?module=module"><img src="../../images/back.png"/>Volver al menu de Modulos</a></div><br>';
}
?>
<script language="javascript">

function reloadList(zone){
	selectPosition.find('option').each(function(){
		$(this).removeAttr('disabled');
	});
	selectAlias.find('option').each(function(){
		$(this).removeAttr('disabled');
	});

	$.post('../services/getModuleOptions.php', {zone: zone}, function(data, textStatus, xhr) {
		var jsonData = JSON.parse(data);
		for(i=0;i<jsonData.length;i++){
			if(jsonData[i]['type']=='module_position'){
				selectPosition.find('option').each(function(){
					if($(this).val()==jsonData[i]['value']){
						$(this).attr('disabled','disabled');
					}
				});
			}
			if(jsonData[i]['type']=='module_alias'){
				selectAlias.find('option').each(function(){
					if($(this).val()==jsonData[i]['value']){
						$(this).attr('disabled','disabled');
					}
				});
			}
		}
		selectPosition.find('option').each(function(){
			if($(this).attr('disabled')==false){
				$(this).attr('selected','selected');
				return false;
			}
		});
		selectAlias.find('option').each(function(){
			if($(this).attr('disabled')==false){
				$(this).attr('selected','selected');
				return false;
			}
		});

	});

}


$(document).ready(function() {
	var select;


	$('select, input').each(
	    function(index){  
	        var input = $(this);
	        if(input.attr('name')=="module_zone") selectZone = $(this);
	        if(input.attr('name')=="module_position") selectPosition = $(this);
	        if(input.attr('name')=="module_alias") selectAlias = $(this);
	        if(input.attr('name')=="saveButton") {
	        	button = $(this);
	        	button.attr('onclick','').unbind('click');
	        }
	    }
	);

	selectZone.change(function(event) {
		reloadList($($(this)).val());
	});

	selectZone.change(function(event) {
		reloadList($($(this)).val());
	});

	button.click(function(event) {
		var index = 0;
		selectPosition.find('option').each(function(){
			if($(this).attr('disabled')==true){
				index++;
			}
		});
		if(index!=0){
			var form = $('#form_main');
			verify(this.form); //ERA LA MISMA WEA!!! xD La weá forme
		}else{
			alert("No puede ingresar más módulos en la zona seleccionada");
		}
	});



});
</script>