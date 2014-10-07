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
		<br>
		<div class="row">
			<div class="well well-sm text-center">
				Bitacora
			</div>
			<form class="form-horizontal" id="toSearch" role="form" name="between" method="POST">

					<div class="row">
				
						<div class="col-md-2">
							<label for="rutSearch" class="col-sm-2 control-label pull-right">Rut: </label>
						</div>
						<div class="col-md-3">
							<input type="text" class="form-control" id="rutSearch" name="rutSearch" placeholder="Ingrese rut">
						</div>
						<div class="col-md-1">
							<label for="nameSearch" class="col-sm-2 control-label ">Nombre: </label>

						</div>
						<div class="col-md-3">
							<input type="text" class="form-control" id="nameSearch" name="nameSearch" placeholder="Ingrese nombre">
						</div>
						<div class="col-md-2">
							<input id="button" name="beetween"  class="btn btn-default" type="submit" value="Buscar" onclick="submit();">
						</div>
					
					</div>
			</form>
		</div>

		<div class="row col-lg-9">	
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
				//$where=array(""=>" module.id NOT IN (SELECT module FROM users_roles WHERE users=$rol)");
				$rows = $bitacora->select($where);
				if($rows){
					echo "<div class='well well-sm text-center' id='data'></div>";
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
		<div id="rowDetail" class="col-lg-3">HOLA</div>
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
		if(rut != ''){
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
						$("#data").html("");
						$("#data").text(data1);
						
					}else{
						$("#data").text('Sin registros');
					}
			    },
			    error: function(xhr) { // if error occured

			    },
			    complete: function() {

			    	$('#showData').addClass('table table-striped table-bordered');
				    $('#showData').dataTable( {
				        "dom": 'T<"clear">lfrtip',
				        "tableTools": {
				            "sSwfPath": "js/datatables2/swf/copy_csv_xls_pdf.swf"
				        },
				        "language": {
				            "url": "js/datatables2/languaje/languaje.lang"
				        }
				    } );

				    var table = $('#example').DataTable();
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
		console.log(rut);
		$("#rutSearch").val(rut);
		$("#toSearch").submit();
	}

	function getRowDetail(idLog){
		if(idLog!=0){
			$.post('services/getLogData.php', {idLog: idLog}, function(data, textStatus, xhr) {
				var jsonData = JSON.parse(data);
				console.log(jsonData);
				var htmlDetail = '<table>';
				htmlDetail += '<tr><td>ID: </td><td>  </td><td>'+idLog+'</td></tr>';
				htmlDetail += '<tr><td>RUT/DNI: </td>  <td></td><td>'+jsonData.rut+'</td></tr>';
				htmlDetail += '<tr><td>Descripción: </td>  <td></td><td>'+jsonData.description+'</td></tr>';
				htmlDetail += '<tr><td>Zona: </td><td></td>  <td>'+jsonData.zone+'</td></tr>';
				htmlDetail += '<tr><td>Módulo: </td><td>  </td><td>'+jsonData.module+'</td></tr>';
				htmlDetail += '<tr><td>Sub-Módulo: </td><td>  </td><td>'+jsonData.submodule+'</td></tr>';
				htmlDetail += '<tr><td>Usuario: </td><td>  </td><td>'+jsonData.username+'</td></tr>';
				htmlDetail += '<tr><td>---------</td><td>  </td><td>----------</td></tr>';
				
				htmlDetail += '<tr><td>Fecha: </td><td>  </td><td>'+jsonData.datetime.split(' ')[0]+'</td></tr>';
				
				if(jsonData.description!='Ingreso de RUT Totem' && jsonData.description!='No seleccionó atención'){
					htmlDetail += '<tr><td>Inicio de espera: </td><td></td><td>'+jsonData.waitingStart.split(' ')[1]+'</td></tr>';
					if(jsonData.secondDescription==null){
						htmlDetail += '<tr><td>Inicio de atención: </td><td></td><td>'+jsonData.attentionStart.split(' ')[1]+'</td></tr>';
						htmlDetail += '<tr><td>Fin de atención: </td><td></td><td>'+jsonData.attentionFinish.split(' ')[1]+'</td></tr>';
						htmlDetail += '<tr><td>Tiempo de espera: </td><td></td><td>'+jsonData.waitingTime+'</td></tr>';
						htmlDetail += '<tr><td>Tiempo de atención: </td><td></td><td>'+jsonData.attentionTime+'</td></tr>';
					}else{
						htmlDetail += '<tr><td>Fin de espera: </td><td></td><td>'+jsonData.attentionStart.split(' ')[1]+'</td></tr>';
					}
				}else{
					htmlDetail += '<tr><td>Hora ingreso: </td><td></td><td>'+jsonData.datetime.split(' ')[1]+'</td></tr>';
				}

				htmlDetail += '</table>';
				$("#rowDetail").html(htmlDetail);
			});


			
		}else{
			$("#rowDetail").html('');
		}
	}

</script>
