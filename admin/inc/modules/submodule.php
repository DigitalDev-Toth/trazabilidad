<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf8');  }

include("libs/db.class.php");
include("controls.php");

?>
<script src="js/jquery-1.8.1.min.js"></script>
<script>
var setState = function(id, state) {
	if(state!='blink') {
		$.get("modules/updateSubmoduleState.php",{"id": id, "state": state},function(data, textStatus, xhr){
			state=data;
			$('#state_'+id).html('<img src="../images/'+state+'.png" onClick="setState('+id+', '+"'"+state+"'"+');">');
			$.post("../../visor/comet/backend.php",{msg: JSON.stringify({"comet":"submodule", "id":id, "state":state})},function(data, textStatus, xhr){});
		});
	} else {
		$.post("../../visor/comet/backend.php",{msg: JSON.stringify({"comet":"submodule", "id":id, "state":state})},function(data, textStatus, xhr){});
	}
	
};
</script>
<?php

$submodule = new DB("submodule", "id");
$submodule->exceptions(array("module", "users"));
$submodule->relation("module", "module", "id", "name");
$submodule->additions("module", array("name"=>"modulename"));

$submodule->relation("users", "users", "id", "name");
//$submodule->additions("users", array("name"=>"usersname"));
$submodule->insertExternalInShow('Zona', 'http://localhost/traza_enzo/admin/inc/services/showZones.php?module=%id%');
/*$submodule->insertExternalInShow('Usuario actual', 'http://localhost/new_traza/admin/inc/modules/subModuleTime.php?submodule=%id%&type=user');
$submodule->insertExternalInShow('Hora inicio', 'http://localhost/new_traza/admin/inc/modules/subModuleTime.php?submodule=%id%&type=ini');
$submodule->insertExternalInShow('Tiempo de Actividad', 'http://localhost/new_traza/admin/inc/modules/subModuleTime.php?submodule=%id%&type=total');*/
$submodule->control("activateSubModule", "modules/changeStatus.php?state=activo");
$submodule->control("desactivateSubModule", "modules/changeStatus.php?state=inactivo");

$submodule->changeItemInShow("state", '<a id="state_%id%" class="state"><img id="image_%id%" src="../images/%value%.png" onClick="setState(%id%, '."'%value%'".');"></a>
							<a class="state" onClick="setState(%id%, '."'blink'".');"><img src="../images/info.png"></a>
							');

makeControls($submodule, "modules/submoduleForm.php", "modules/submoduleDelete.php", "modules/submoduleUpdate.php", $_SERVER['HTTP_REFERER']);

$submodule->showControls();
echo '<div algin="center" id="showTitle">Sub-Modulos</div>';

//$where = array();
$rows = $submodule->select();
echo $submodule->showData($rows, TRUE);
?>