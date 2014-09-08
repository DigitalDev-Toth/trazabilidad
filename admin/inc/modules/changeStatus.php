<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
include("../libs/db.class.php");
$comet ='[';
if(isset($_GET['id'])){
	$state=$_GET['state'];
	$data = explode(",", $_GET['id']);
	if($state == 'activo') $msg = "activar";
	else $msg = "desactivar";
	if(count($data) > 1){
		$db = NEW DB();
		for ($i = 0; $i < count($data) - 1 ; $i++) { 		
			$sql = "UPDATE submodule SET state='$state' WHERE id=$data[$i]";
			$db -> doSql($sql);
			$comet .= '{"comet":"submodule","state":"'.$state.'","id":'.$data[$i].'},';
		}
		$comet = substr_replace($comet ,"",-1);
		$comet .=']'; 
		echo "<h3> Exito al ".$msg." </h3>";
		echo '<a href="#" onclick="window.close();return false;" ><img src="../../images/back.png"/>Volver al menu de Submodulos</a>';
		echo '<script language="Javascript">opener.window.location.reload(false);</script>';		
	}else{
		echo('<script language="Javascript">  window.parent.focus();window.parent.alert("Seleccione al menos un submodulo a '.$msg.'"); window.close();</script>');		
		exit();
	}
}

?>

<script src="../js/jquery-1.8.1.min.js"></script>
<script>
	$(document).ready(function() {
	 	var comet='<?php echo $comet;?>';
	 	$.ajax({
	 		url: '../../../visor/comet/backend.php',
	 		type: 'POST',
	 		dataType: 'json',
	 		data: {msg: comet},
	 	})
	 	.done(function() {
	 		console.log("success");
	 	})
	});
</script>