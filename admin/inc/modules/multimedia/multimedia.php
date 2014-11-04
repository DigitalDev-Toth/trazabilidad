<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }

include 'libs/bootstrapStyle.php';

if($_SESSION['zone'] == NULL){
	$_SESSION['zone']="1";
}

if (isset($_REQUEST['refreshZone'])) {
	$_SESSION['zone']=$_REQUEST['refreshZone'];
}

$dir = 'modules/multimedia/uploads/'.$_SESSION["zone"].'/';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}
$max_size = 1024*200;
$extensions = array('jpeg', 'jpg', 'png');
$count = 0;

if (isset($_FILES['files']))
{
	foreach ( $_FILES['files']['name'] as $i => $name )
	{
		if ( !is_uploaded_file($_FILES['files']['tmp_name'][$i]) )
			continue;
		if ( $_FILES['files']['size'][$i] >= $max_size )
			continue;
		if( !in_array(pathinfo($name, PATHINFO_EXTENSION), $extensions) )
			continue;
	    if( move_uploaded_file($_FILES["files"]["tmp_name"][$i], $dir . $name) )
	    	$count++;
	}
}
if (isset($_REQUEST['toDelete'])) {
	$toDel = $_REQUEST['toDelete'];
	unlink($dir.$toDel);
}
$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {
	if($filename != '.' && $filename != '..' && strpos($filename,'conf') === false){
		$files[] = $filename;
	}
}

if(isset($_REQUEST['patientView'])){
	$op = $_REQUEST['patientView'];
	$fh = fopen($dir.$_SESSION["zone"].".conf", (file_exists($dir.$_SESSION["zone"].".conf")) ? 'w' : 'a');
	fwrite($fh, $op);
	fclose($fh);
}else{
	
	if(!file_exists($dir.$_SESSION["zone"].".conf")){
		$fh = fopen($dir.$_SESSION["zone"].".conf", (file_exists($dir.$_SESSION["zone"].".conf")) ? 'w' : 'a');
		fwrite($fh, '1');
		fclose($fh);
	}else{
		$option = file($dir.$_SESSION["zone"].".conf");
		$op = $option[0];
	}
	
}




?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
 	<style type="text/css">
 	img:hover {
	    -webkit-transform: scale(7); 
	    -moz-transform: scale(7);
	    -o-transform: scale(7);
	    transform: scale(7);
	}
	img {
	    width:200px;
		-webkit-transition: all .4s ease-in-out;
		-moz-transition: all .4s ease-in-out;
		-o-transition: all .4s ease-in-out;
		-ms-transition: all .4s ease-in-out;
	}
 	</style>
</head>
<body>
	<div class="container">
	<div class="row">
		<div class="row text-center well well-sm ">
			<div class="col-md-2">
				<label style="margin-top: 8px;"><span class="glyphicon glyphicon-th-list"></span> Pantalla Pacientes</label>
			</div>


			<div class="col-md-2">
				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span> Zona</div>
					<h4 id="loading"><i class="fa fa-spinner fa-spin"></i></h4>



						 <form method='post' id="zoneForm"> 
						 		<select class="form-control" id="selectorZone" name='refreshZone' style="display:none">
								</select>
						 </form>
					
				</div>
			</div>
	
	
			</div>


		</div>



		<div class="row">
			<div class="col-md-6">

				<div class="panel panel-default">
					<div class="panel-heading">Carga de imagenes</div>	
					<div class="container">
						<form action="" method="post" enctype="multipart/form-data" class="pure-form">
					    	<br>
							<div style="position:relative;">
					        <a class='btn btn-primary' href='javascript:;'>
					            <span class="glyphicon glyphicon-search"></span> Seleccionar Archivos
					            <input type="file" accept="image/x-png, image/gif, image/jpeg image/jpg" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;'  size="40"  name="files[]" multiple="multiple" id="files">
					        </a>
					        <br>
					        <div id="file-info"></div>
							</div>
							<hr>
							
					    
					    	<button type='submit' class='btn btn-default'> <span class="glyphicon glyphicon-floppy-open"></span> Subir Imagenes</button>
					    	<br>
					    	<div id="file-up"></div>
				  		</form>
					</div>
					

				</div>
				<div class="panel panel-default">
					<div class="panel-heading">Imagenes</div>	
					
							<div id="showImages"></div>

				
				</div>
				<br><br>

			</div>
			<div class="col-md-6">

				<div class="panel panel-default">
					<div class="panel-heading">Tipo de visualizacion</div>	
					<div class="container">		<br>
						<!--<iframe src="modules/multimedia/envivo.13.cl/index.html" style="width:600;height:500"></iframe>-->
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span> Zona</div>
		
								<form method='post' id="list" > 
									<select class="form-control" id="view" name="patientView">
										<option value="1" name="selector" >Canal 13 HD</option>
										<option value="2" name="selector" >TVN</option>
										<option value="3" name="selector" >Imagenes</option>
										<option value="4" name="selector" >Sin contenido</option>
									</select>
								</form>
					
							</div>
							<br>
					</div>
					

				</div>
		

			</div>

			<div class="col-md-6">

				<div class="panel panel-default">
					<div class="panel-heading">Vista Previa</div>	
					<div class="container">		<br>
						<!--<iframe src="modules/multimedia/envivo.13.cl/index.html" style="width:600;height:500"></iframe>-->
					</div>
					

				</div>
		

			</div>

		
		</div>
	</div>



</body>


<script type="text/javascript">

fillZone();
$("#files").change(function(event) {
	var inp = document.getElementById('files');
	var name='',cant=0;
	for (var i = 0; i < inp.files.length; ++i) {
		name += inp.files.item(i).name + " <br> "; 
		cant++;
	}
	$("#file-info").html(name);
	$("#file-info").append("Total de imagenes a subir: "+cant);
});

$(document).ready(function() {
	var uploaded = "<?php echo $count; ?>";
	var dir = "<?php echo $dir; ?>";
	
	if(uploaded != 0){
		$("#file-up").html("<br>Se almacenaron "+ uploaded + " imagenes correctamente.");
	}
	var files = <?php echo json_encode($files ); ?>;
	if(files){
		var htmlC ='<table class="table table-hover table-striped"> <tr> <th>N</th><th>Nombre</th><th>Imagen</th> <th>Eliminar</th> <tr>';
		for (var i = 0; i < files.length; i++) {
		htmlC += "<tr> <th>"+(i+1)+"</th><th>"+files[i]+"</th><th><img src='"+dir+"/"+files[i]+"' style='width:100' ></th> <th>	  <form method='post'> <input type='hidden' value='"+files[i]+"' name='toDelete' />  <button type='submit' class='btn btn-default'><span class='glyphicon glyphicon-remove'></span> </button> </form>     </th><tr>";
	};
	htmlC+="</table>";
	$("#showImages").html(htmlC);
	}
	

	$("#selectorZone").change(function(event) {
		//$("#selectedZone").text(this.value);
		$("#zoneForm").submit();
	});

	$("#view").change(function(event) {
		$("#list").submit();
	});

	var multimediaOp = "<?php echo $op;?>";
	if(multimediaOp != ''){
		$("#view option[value='" + multimediaOp + "']").attr("selected","selected");
	}


});



function fillZone() {
	$.ajax({
		url: 'services/getZoneInfo.php',
		type: 'GET',
	})
	.done(function(e) {
		var data = JSON.parse(e);
	    var sel = $("#selectorZone");
	    sel.empty();
	    for (var i=0; i<data.length; i++) {
	      sel.append('<option value="' + data[i].id + '" name="selector" >' + data[i].name + '</option>');
	    }
	   	var zone = "<?php echo $_SESSION['zone']; ?>";
		if(zone != ''){
			$("#selectorZone option[value='" + zone + "']").attr("selected","selected");
		}
	    $("#loading").fadeOut('fast', function() {
	    	$("#selectorZone").fadeIn('fast', function() {
	    	});
	    });
	})
	.fail(function() {
		window.setTimeout('location.reload()', 100);
	});
}


</script>

</html>


