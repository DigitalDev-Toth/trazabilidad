<?phpsession_start();if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }?><link href="../../style/style.css" rel="stylesheet" type="text/css" /><?include("../libs/db.class.php");$module = new DB("module", "id");$data = $_REQUEST;function sendComet(){	$comet = '{"comet":"f5"}';	echo '<script src="../js/jquery-1.8.1.min.js"></script>';	echo '<script>		    $.post("../../../visor/comet/backend.php",{msg: JSON.stringify('.$comet.')},function(data, textStatus, xhr){		    });		</script>';}if($module->deleteData($data)) { 	echo '<p id="d_true"><img src="../../images/delete.png"/>Items borrados con exito!</p>'; 	sendComet();}else { 	echo 'error al borrar algunos de los items'; }echo '<br><div id="back"><a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/back.png"/>Volver al menu de Modulos</a></div><br>';?>