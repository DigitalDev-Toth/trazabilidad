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

					<div class="row text-center well well-sm text-primary">
						<div class="col-md-2">
							<label style="margin-top: 8px;"><span class="glyphicon glyphicon-th-list"></span> Bitacora</label>
						</div>
						<div class="col-md-1 ">
							<label for="rutSearch" class="col-sm-2 control-label">RUT/DNI:</label>
						</div>
						<div class="col-md-2">
							<input type="text" class="form-control" id="rutSearch" name="rutSearch" placeholder="Ingrese RUT/DNI">
						</div>
						<div class="col-md-1 ">
							<label for="nameSearch" class="col-sm-2 control-label ">Nombres: </label>
						</div>
						<div class="col-md-3">
							<input type="text" class="form-control" id="nameSearch" name="nameSearch" placeholder="Ingrese nombre y/o apellido">
						</div>
						<div class="col-md-1">
							<button id="button" name="beetween"  class="btn btn-primary" type="submit" onclick="submit();"><span class="glyphicon glyphicon-search"></span> Buscar</button>
						</div>
					
					</div>
			</form>
		</div>

		<!--<div class="row col-lg-9">	-->
		<?php


		if (isset($_REQUEST['external'])) {
			$external = TRUE;
		}


		$where='';
		if(isset($_REQUEST['nameSearch']) || isset($_REQUEST['rutSearch']) ){
			
			$rut = $_REQUEST['rutSearch'];
			$name = $_REQUEST['nameSearch'];
			if($name != ''){
				echo "<div class='well well-sm text-center' id='data'></div>";
			}else{
				$where = array(""=>"rut='$rut'");
				$bitacora = new DB("logs", "id");
				makeControls($bitacora, NULL , NULL , NULL , $_SERVER['HTTP_REFERER']);
				$bitacora->exceptions(array("action","users","module","sub_module","zone"));

				$bitacora->relation("zone", "zone", "id", "name");
				$bitacora->additions("zone",array("name"=>"Zona"));
				$bitacora->relation("module", "module", "id", "name");
				$bitacora->additions("module", array("name"=>"Modulo"));
				$bitacora->relation("submodule", "sub_module", "id", "name");
				$bitacora->additions("submodule",array("name"=>"Submodulo"));
				



				//$where=array(""=>" module.id NOT IN (SELECT module FROM users_roles WHERE users=$rol)");
				$rows = $bitacora->select($where);
				if($rows){
					echo "<div class=' text-center' id='data'></div>";
					echo "<div class='row col-md-9'>";
					echo "<div class='well well-sm text-center'>";
					
					echo $bitacora->showData($rows, TRUE);
					echo "</div>";
				}else{
					echo "<div class='well well-sm text-center' id='data'> Sin Resultados...</div>";
				}
			}
		}
		?>

		</div>
		<div id="rowDetail" class="col-md-3"></div><!--Descripción de Log-->
	</div>
</body>
</html>


<script src="js/datatables2/js/jquery.js"></script>
<script src="js/datatables2/js/jquery.dataTables.min.js"></script>
<script src="js/datatables2/js/dataTables.tableTools.js"></script>
<script src="js/datatables2/js/dataTables.bootstrap.js"></script>


<script>
$("#showData").hide();
	$(document).ready(function() {
		
		var external = "<?php echo $external; ?>";		
		if(external){
			$("toSearch").hide("");
		}
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
			    success: function(data1) {
			    	var json = JSON.parse(data1);
					if(json.length == 1){
						var patientHtml = '<table class="table table-bordered">';
							patientHtml += '<tr> <th colspan="6" class="text-center bg-primary">Resultados </th></tr>';
							patientHtml += '<tr><th>RUT/DNI: </th><td>'+json[0]["rut"]+'</td>  <th>N° Ficha:</td><td>1</td>    <th>Estado Actual</td><td>1</td>  </tr>';
							patientHtml += '<tr><th>Nombre: </th><td>'+json[0]["name"]+' '+json[0]["lastname"]+' <th>N° P. Tratamiento:</td><td>1</td>    <th>Maximo T. de espera:</td><td>1</td></tr>';
							patientHtml += '<tr><th>Fecha de Nacimiento: </th><td>'+json[0]["birthdate"]+'</td> <th>N° Presupuesto:</td><td>1</td>  <th>T. espera cumulado</td><td>1</td> </tr>';
							patientHtml += '</table>';

							/*

			
												
	




- N° Ficha:
- N° P. Tratamiento
- N° Presupuesto:

- T° espera actual:
- Máx. T° espera: 
- T° espera acumulado: 
*/



						$("#data").html("");
						$("#data").html(patientHtml);
					}else{
						$("#data").text('Sin registros');
					}
			    },
			    error: function(xhr) { // if error occured

			    },
			    complete: function() {

			    	//$('#showData').addClass('table table-striped table-bordered');
			    	$('#showData').addClass('table table-bordered table-hover');
				    $('#showData').dataTable( {
				        "dom": 'T<"clear">lfrtip',
				        "tableTools": {
				            "sSwfPath": "js/datatables2/swf/copy_csv_xls_pdf.swf"
				        },
				        "language": {
				            "url": "js/datatables2/languaje/languaje.lang"
				        }
				    } );

				    var table = $('#showData').DataTable();

				 	$('#showData tr').hover(function() {
					   getRowDetail(this.childNodes[1].innerHTML);
					}, function() {
					    if ($(this).hasClass('selected')==false){
				            getRowDetail(0);
				        }
					});


				    $('#showData tbody').on( 'click', 'tr', function () {
				        if ($(this).hasClass('selected')){
				            $(this).removeClass('selected');
				            getRowDetail(0);
				        }else{
				            table.$('tr.selected').removeClass('selected');
				            $(this).addClass('selected');
				            getRowDetail(this.childNodes[1].innerHTML);
				        }
				    });

			    	$("#showData").fadeIn('slow');
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
				    success: function(data1) {
				    	var json = JSON.parse(data1);
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



	function search(rut){
		$("#rutSearch").val(rut);
		$("#toSearch").submit();
	}

	function getRowDetail(idLog){
		if(idLog!=0){

			$.ajax({
				url: 'services/getLogData.php',
				type: 'GET',
				data: {idLog: idLog}  ,
				beforeSend: function() {
					var loading ='<div style="position: fixed;" ><br><h1 ><i class="fa fa-spinner fa-spin" style="margin-left: 150px;margin-top: 150px;"></i></h1></div>';
				   	$("#rowDetail").html(loading);
				},
			})
			.done(function(data) {
				var jsonData = JSON.parse(data);
				var htmlDetail = '<div style="position: fixed;" class="panel panel-primary">';
				htmlDetail += '<div class="panel-heading text-center">Resumen</div>  <div class="panel-body"><table class="table table-bordered">';
				htmlDetail += '<tr><th>ID: </th><td>'+idLog+'</td></tr>';
				htmlDetail += '<tr><th>RUT/DNI: </th> <td>'+jsonData.rut+'</td></tr>';
				htmlDetail += '<tr><th>Descripción: </th> <td>'+jsonData.description+'</td></tr>';
				htmlDetail += '<tr><th>Zona: </th><td>'+jsonData.zone+'</td></tr>';
				htmlDetail += '<tr><th>Módulo: </th><td>'+jsonData.module+'</td></tr>';
				htmlDetail += '<tr><th>Sub-Módulo: </th><td>'+jsonData.submodule+'</td></tr>';
				if(jsonData.username!=null)	htmlDetail += '<tr><th>Usuario: </th><td>'+jsonData.username+'</td></tr>';
				htmlDetail += '<tr><th colspan="3"> </th></tr>';
				
				htmlDetail += '<tr><th>Fecha: </th><td>'+jsonData.datetime.split(' ')[0]+'</td></tr>';
				
				if(jsonData.description!='Ingreso de RUT Totem' && jsonData.description!='No seleccionó atención'){
					htmlDetail += '<tr><th>Inicio de espera: </th><td>'+jsonData.waitingStart.split(' ')[1]+'</td></tr>';
					if(jsonData.secondDescription==null){
						if(jsonData.attentionStart!=null){
							htmlDetail += '<tr><th>Inicio de atención: </th><td>'+jsonData.attentionStart.split(' ')[1]+'</td></tr>';
						}
						if(jsonData.attentionFinish!=null){
							htmlDetail += '<tr><th>Fin de atención: </th><td>'+jsonData.attentionFinish.split(' ')[1]+'</td></tr>';
						}
						htmlDetail += '<tr><th>Tiempo de espera: </th><td>'+jsonData.waitingTime+'</td></tr>';
						htmlDetail += '<tr><th>Tiempo de atención: </th><td>'+jsonData.attentionTime+'</td></tr>';
					}else{
						htmlDetail += '<tr><th>Fin de espera: </th><td>'+jsonData.attentionStart.split(' ')[1]+'</td></tr>';
					}
				}else{
					htmlDetail += '<tr><th>Hora ingreso: </th><td>'+jsonData.datetime.split(' ')[1]+'</td></tr>';
				}

				htmlDetail += '</table></div></div></div>';
			
					$("#rowDetail").html(htmlDetail);
				
			});
			
		}else{
			$("#rowDetail").html('');
		}
	}

</script>
