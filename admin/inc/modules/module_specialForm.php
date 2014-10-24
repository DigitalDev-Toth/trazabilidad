<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }
?>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");

$module_special = new DB("module_special", "id");
$module_special->exceptions(array("id"));

$module_special->relation("module", "module", "id", "name");
$module_special->exceptions(array("last_ticket"));

$alias = array("A"=>"A","B"=>"B","C"=>"C","D"=>"D","E"=>"E","F"=>"F","G"=>"G","H"=>"H","I"=>"I",
				"J"=>"J","K"=>"K","L"=>"L","M"=>"M","N"=>"N","O"=>"O","P"=>"P","Q"=>"Q","R"=>"R",
				"S"=>"S","T"=>"T","U"=>"U","V"=>"V","W"=>"W","X"=>"X","Y"=>"Y","Z"=>"Z");

$module_special->changeFormObject('module_special.alias', 'menu', null, $alias);
if (isset($_GET['update']))
{
	$module_special->updateData($_GET['update'], FALSE);
}
else
{
	//$module_special->checkItemIfExist("module_special_name", "module_special", "name");
	echo '<div algin="center" id="showTitle">INSERTAR MODULO ESPECIAL</div>';
	if($module_special->insertData(FALSE))
	{
		echo '<br><div id="back">';
		echo '<a href="../contentMain.php?module=module_special"><img src="../../images/back.png"/>Volver al menu de Modulo Especial</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar Nuevo Modulo Especial</a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="back"><a href="../contentMain.php?module=module_special"><img src="../../images/back.png"/>Volver al menu de Modulo Especial</a></div><br>';
}
?>

<script language="javascript">

function reloadList(module){
	selectAlias.find('option').each(function(){
		$(this).removeAttr('disabled');
	});

	$.post('../services/getModuleSpecialOptions.php', {module: module}, function(data, textStatus, xhr) {
		var jsonData = JSON.parse(data);
		for(i=0;i<jsonData.length;i++){
			selectAlias.find('option').each(function(){
				if($(this).val()==jsonData[i]['value']){
					$(this).attr('disabled','disabled');
				}
			});
		}
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
	        if(input.attr('name')=="module_special_module") selectModule = $(this);
	        if(input.attr('name')=="module_special_alias") selectAlias = $(this);
	        if(input.attr('name')=="saveButton") {
	        	button = $(this);
	        	button.attr('onclick','').unbind('click');
	        }
	    }
	);

	selectModule.find('option').each(function(){
		reloadList($(this).val());
		return false;
	});

	selectModule.change(function(event) {
		reloadList($($(this)).val());
	});

	button.click(function(event) {
		var index = 0;
		selectAlias.find('option').each(function(){
			if($(this).attr('disabled')==true){
				index++;
			}
		});
		if(index!=0){
			var form = $('#form_main');
			verify(this.form);
		}else{
			alert("No puede ingresar más módulos especiales en el módulo seleccionado");
		}
	});



});
</script>
