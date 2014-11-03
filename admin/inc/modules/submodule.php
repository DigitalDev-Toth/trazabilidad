<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf8');  }

include("libs/db.class.php");
include("controls.php");

?>
<script src="js/jquery-1.8.1.min.js"></script>
<script src="http://192.168.0.104:8000/socket.io/socket.io.js"></script>
<script>
console.log('asdad');
    var socket = io.connect('http://192.168.0.104:8000');        
	var setState = function(id, state) {
		if(state!='blink') {
			$.get("modules/updateSubmoduleState.php",{"id": id, "state": state},function(data, textStatus, xhr){
				state=data;
				$('#state_'+id).html('<img src="../images/'+state+'.png" onClick="setState('+id+', '+"'"+state+"'"+');">');
				           console.log(JSON.stringify({"comet":"submodule", "id":id, "state":state}));

                socket.send(JSON.stringify({"comet":"submodule", "id":''+id+'', "state":state}));

				//$.post("../../visor/comet/backend.php",{msg: JSON.stringify({"comet":"submodule", "id":id, "state":state})},function(data, textStatus, xhr){});
			});
		} else {

           console.log(JSON.stringify({"comet":"submodule", "id":id, "state":state}));
            socket.send(JSON.stringify({"comet":"submodule", "id":''+id+'', "state":state}));

			//$.post("../../visor/comet/backend.php",{msg: JSON.stringify({"comet":"submodule", "id":id, "state":state})},function(data, textStatus, xhr){});
		}
		
	};
</script>
<?php

$submodule = new DB("submodule", "id");
$submodule->exceptions(array("module", "users"));
$submodule->relation("module", "module", "id", "name");
$submodule->additions("module", array("name"=>"modulename"));

$submodule->relation("users", "users", "id", "name");
$submodule->additions("users", array("name"=>"usersname"));
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





$ZoneCombo['Todos'] = "";
$db = new DB("zone","id");
$sql = $db->doSql("SELECT id,name from zone order by id");
do{
	$nameZ = $sql['name'];
	$idZ = $sql['id'];
	if($idZ!='')
	{
		$ZoneCombo[$nameZ] = $idZ;
	}
}while($sql = pg_fetch_assoc($db->actualResults));




echo '<div algin="center" id="showTitle">Sub-Modulos</div>';
echo '<link href="../style/styleAll.css" rel="stylesheet" type="text/css" title="default" media="screen" />';
$submodule->showControls();
echo '<table  id="tableForm" align="center"><tr><td>';
	echo '<div>';
		echo '<form name="between" method="POST">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Zona</td><td>'.$submodule->fillCombo($ZoneCombo,"zone","zone",'id="zone" onclick="submit();"').'</td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
	echo '</div>';
echo '</td></tr></table>';
//$where = array();


$search = '';
if(isset($_REQUEST['zone'])){
	$idZone = $_REQUEST['zone'];
	if($idZone != ''){
		$search ="zone=$idZone";
		$where = array(''=>$search);
	}else{
		$where='';
	}
	
}

$rows = $submodule->select($where);
echo $submodule->showData($rows, TRUE);
?>
<script>
$(document).ready(function() {
	var zone = "<?php echo $_REQUEST['zone'] ?>";
	if(zone != ''){
		$("#zone").val(zone);

	}else{
		$("#zone").val(0);
	}

});

</script>