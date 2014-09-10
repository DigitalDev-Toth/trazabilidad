<?php
function setState($id, $state){
	$comet = '{"comet":"submodule", "id":"'.$id.'", "state":"'.$state.'"}';
	echo '<script src="js/jquery-1.8.1.min.js"></script>';
	echo '<script>
		    $.post("../../visor/comet/backend.php",{msg: JSON.stringify('.$comet.')},function(data, textStatus, xhr){
		    });
		</script>';
}

session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf8');  }

include("libs/db.class.php");
include("controls.php");

if(isset($_GET['state'])) {
	$db = new DB();
	$id = $_GET['id'];
	$state = $_GET['state'];
	if($state=='activo') {
		$state = 'inactivo';
	} else {
		$state = 'activo';
	}
	$sql = "UPDATE submodule SET state='$state' WHERE id=$id";
	$db->doSql($sql);
	setState($id, $state);
}

$submodule = new DB("submodule", "id");
$submodule->exceptions(array("module", "users"));
$submodule->relation("module", "module", "id", "name");
$submodule->additions("module", array("name"=>"modulename"));

$submodule->relation("users", "users", "id", "name");
//$submodule->additions("users", array("name"=>"usersname"));
$submodule->insertExternalInShow('Usuario actual', 'http://localhost/new_traza/admin/inc/modules/subModuleTime.php?submodule=%id%&type=user');
$submodule->insertExternalInShow('Hora inicio', 'http://localhost/new_traza/admin/inc/modules/subModuleTime.php?submodule=%id%&type=ini');
$submodule->insertExternalInShow('Tiempo de Actividad', 'http://localhost/new_traza/admin/inc/modules/subModuleTime.php?submodule=%id%&type=total');
//$submodule->control("activateSubModule", "modules/changeStatus.php?state=activo");
//$submodule->control("desactivateSubModule", "modules/changeStatus.php?state=inactivo");

$submodule->changeItemInShow("state", '<a href="?modulo=submodule&id=%id%&state=%value%"><img src="../images/%value%.png"></a>');

makeControls($submodule, "modules/submoduleForm.php", "modules/submoduleDelete.php", "modules/submoduleUpdate.php", $_SERVER['HTTP_REFERER']);

$submodule->showControls();
echo '<div algin="center" id="showTitle">Sub-Modulos</div>';

//$where = array();
$rows = $submodule->select();
echo $submodule->showData($rows, TRUE);
?>