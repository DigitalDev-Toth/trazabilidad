<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }
include("libs/db.class.php");
include("controls.php");



?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="js/datatables2/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="js/datatables2/css/dataTables.tableTools.css">
<link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap.css">
<!--<link rel="stylesheet" type="text/css" href="js/bootstrap/css/dataTables.bootstrap.css">-->

<body>
	<div class="container">
		
		<div class="row">

			<form class="form-horizontal" id="toSearch" role="form" name="between" method="POST">

					<div class="row text-center well well-sm ">
						<div class="col-md-2">
							<label style="margin-top: 8px;"><span class="glyphicon glyphicon-th-list"></span> Bitacora</label>
						</div>

						<div class="col-md-3 ">
								<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-bookmark"></span> RUT/DNI</div>
										<input type="text" class="form-control" id="rutSearch" name="rutSearch" placeholder="Ingrese RUT/DNI">
								</div>
						</div>
				
						<div class="col-md-3">
								<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span> Nombres</div>
									<input type="text" class="form-control" id="nameSearch" name="nameSearch" placeholder="Ingrese nombre y/o apellido">
								</div>
						</div>
						<div class="col-md-1">
							<button id="button" name="beetween"  class="btn btn-default" type="submit" onclick="submit();"><span class="glyphicon glyphicon-search"></span> Buscar</button>
						</div>
					
					</div>
			</form>
		</div>

		<!--<div class="row col-lg-9">	-->
		<?php


	
			$name = $_REQUEST['nameSearch'];
			if($name != ''){
				
				echo "<div class='well well-sm text-center' id='data'></div>";
			}


			$rut = $_REQUEST['rutSearch'];
			if($rut != ''){
				
				echo "<div class='well well-sm text-center' id='info'></div>";
				echo "<div class='well well-sm text-center' id='data'></div><br><br><br>";
			}
			
		?>



	</div>
</body>
</html>


<script src="js/datatables2/js/jquery.js"></script>
<script src="js/datatables2/js/jquery.dataTables.min.js"></script>
<script src="js/datatables2/js/dataTables.tableTools.js"></script>
<script src="js/datatables2/js/dataTables.bootstrap.js"></script>


<script>

	$(document).ready(function() {
		
		var rut = "<?php echo $rut; ?>";
		var name = '<?php echo $name; ?>';
		//show only log of patient
		if(rut != '' || rut.length){
			$.ajax({
			    type: 'POST',
			    url: "services/getBitacora.php",
			    data: { data:rut } ,
			    beforeSend: function() {
			    	$("#data").html('<h1><i class="fa fa-spinner fa-spin"></i></h1>');
			    },
			    success: function(data) {
			    	fillInfo(data);
			    },
			    error: function(xhr) {
			    	//do a barrel roll
			    }
			});
			$.ajax({
			    type: 'POST',
			    url: "services/getLogData.php",
			    data: { rut:rut } ,
			    beforeSend: function() {
			    	$("#data").html('<h1><i class="fa fa-spinner fa-spin"></i></h1>');
			    },
			    success: function(data) {
			    	fillTable(data);
			    },
			    error: function(xhr) {
			    	//do a barrel roll
			    }
			});
		}else{
			//show list of patients
			if(name != ''){
				$.ajax({
				    type: 'POST',
				    url: "services/getBitacora.php",
				    data: { data:name } ,
				    beforeSend: function() {
				    	$("#data").html('<h1><i class="fa fa-spinner fa-spin"></i></h1>');
				    },
				    success: function(data) {
				    	var json = JSON.parse(data);
						$("#data").text('');
						var content = '';
						content = "<table id='patientResults' class='table table-striped table-bordered'> <thead> <tr><th>Nombre</th> <th>Rut</th><th>Usar</th></tr>  </thead><tbody>";
						for (var i = 0; i < json.length; i++) {
							content+="<tr><td>"+json[i].name+" "+json[i].lastname+"</td> <td>"+json[i].rut+"</td><td><button onclick=search('"+json[i].rut +"')>x</button> </td></tr>";
						};
						content+=" </tbody></table>";
						$("#data").hide();
						$("#data").append(content);
						$("#data").fadeIn('slow');
					  	$('#patientResults').dataTable( {
				       		"dom": 'T<"clear">lfrtip',
				        	"tableTools": {
				            	"sSwfPath": "js/datatables2/swf/copy_csv_xls_pdf.swf"
				        	},
				        	"language": {
				            	"url": "js/datatables2/languaje/languaje.lang"
				        	}
				    	} );

						
				    },
				    error: function(xhr) { // if error occured
				    	//do a barrel roll
				    },
				    complete: function() {

				    }
				});
			}
		}

	

	});

	function ifNull (data) {
		return (data == null ? "-" : data);
	}
	function fixTime (data) {
		if(data != null){
			return data.split(' ')[1];
		}
		return '-';
	}
	function fillInfo(data) {
	  	var json = JSON.parse(data);
		if(json.length == 1){
			console.log(json);
			var patientHtml = '<table class="table table-bordered">';
				patientHtml += '<tr> <th colspan="6" class="text-center">Resultados </th></tr>';
				patientHtml += '<tr><th>RUT/DNI: </th><td>'+json[0]["rut"]+'</td>  <th>N° Ficha:</td><td>1</td>    <th>Estado Actual</td><td>1</td>  </tr>';
				patientHtml += '<tr><th>Nombre: </th><td>'+json[0]["name"]+' '+json[0]["lastname"]+' <th>N° P. Tratamiento:</td><td>1</td>    <th>Maximo T. de espera:</td><td>1</td></tr>';
				patientHtml += '<tr><th>Fecha de Nacimiento: </th><td>'+json[0]["birthdate"]+'</td> <th>N° Presupuesto:</td><td>1</td>  <th>T. espera cumulado</td><td>1</td> </tr>';
				patientHtml += '</table>';
			$("#info").html("");
			$("#info").html(patientHtml);
		}
	}
	function fillTable (data) {
		if(data != 0){
			var json = JSON.parse(data);
			console.log(data);
			var T = '<table class="table table-striped table-bordered" id="bitacora"><thead><tr> ';
				T +="<th>Fecha</th>";
				T +="<th>Hora</th>";
				T +="<th>Descripcion</th>";
				T +="<th>Zona</th>";
				T +="<th>Modulo</th>";
				T +="<th>Submodulo</th>";
				T +="<th>Usuario</th>";
				T +="<th>Hora inicio de espera</th>";
				T +="<th>Hora inicio de atencion</th>";
				T +="<th>Hora fin</th>";
				T +="<th>Total espera</th>";
				T +="<th>Total atencion</th>";
				//T +="<th>Otros</th>";
				T +="</tr></thead><tbody>";

			for (var i = 0; i < json.length; i++) {
				T += '<tr><td>'+ ifNull(json[i].datetime.split(' ')[0]) +'</td>';
				T += '<td>'+ ifNull(json[i].datetime.split(' ')[1]) +'</td>';
				T += '<td>'+ ifNull(json[i].description) +'</td>';
				T += '<td>'+ ifNull(json[i].zone) +'</td>';
				T += '<td>'+ ifNull(json[i].module) +'</td>';
				T += '<td>'+ ifNull(json[i].submodule) +'</td>';
				T += '<td>'+ ifNull(json[i].username) +'</td>';
				T += '<td>'+ fixTime(json[i].waitingStart) +'</td>';
				T += '<td>'+ fixTime(json[i].attentionStart) +'</td>';
				T += '<td>'+ fixTime(json[i].attentionFinish) +'</td>';
				T += '<td>'+ ifNull(json[i].waitingTime) +'</td>';
				T += '<td>'+ ifNull(json[i].attentionTime) +'</td>';
				//T += '<td>'+ ifNull(json[i].secondDescription) +'</td></tr>';
			};

			T +='</tbody></table>';
			$("#data").html(T);
		  	$("#bitacora").dataTable( {
		       		"dom": 'T<"clear">lfrtip',
		        	"tableTools": {
		            	"sSwfPath": "js/datatables2/swf/copy_csv_xls_pdf.swf"
		        	},
		        	"language": {
		            	"url": "js/datatables2/languaje/languaje.lang"
		        	}
		    	} );

		}else{
			$("#data").html('<h2>Sin resultados</h2>');
		}
		

	}
	function search(rut){
		$("#rutSearch").val(rut);
		$("#toSearch").submit();
	}



</script>
