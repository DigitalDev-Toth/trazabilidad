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
<script src="js/datatables2/js/jquery.js"></script>
<script src="js/datatables2/js/jquery.dataTables.min.js"></script>
<script src="js/datatables2/js/dataTables.tableTools.js"></script>
<script src="js/datatables2/js/dataTables.bootstrap.js"></script>

<link rel="stylesheet" href="../inc/js/datapickerB/css/datepicker3.css">
<script src="../inc/js/datapickerB/js/bootstrap-datepicker.js"></script>
<script src="../inc/js/datapickerB/js/locales/bootstrap-datepicker.es.js"></script>
<script type="text/javascript" src="../inc/modules/statistical/moment.js"></script>
<script type="text/javascript" src="../inc/modules/statistical/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="../inc/modules/statistical/daterangepicker-bs3.css" />

<body>
	<div class="container">
		
		<div class="row">

			<form class="form-horizontal" id="toSearch" role="form" name="between" method="POST">

					<div class="row well well-sm ">
						<div class="col-md-1 text-center">
							<button id="btnBack" type="button" class="btn btn-primary" title="Volver" style="cursor: pointer; visibility:hidden;" onclick="search()"><i class="glyphicon glyphicon-arrow-left"></i></button>
						</div>
						<div class="col-md-2">
							<label style="margin-top: 8px;"><span class="glyphicon glyphicon-th-list"></span> Bit&aacute;cora Usuarios</label>
							<input type="text" class="form-control" id="userSearch" name="userSearch" style="visibility: hidden;">
						</div>

						<!--<div class="col-md-3 ">
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
						</div>-->
						<div class="col-md-3 text-center">
					   		<div id="reportrange" class="form-control" style="cursor: pointer; visibility:hidden;">
		                  		<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
		                  		<span></span> <b class="caret"></b>
               				</div>
						</div>
					</div>
					<div class="row well well-sm " id='data'>
						
					</div>
			</form>
		</div>

		<!--<div class="row col-lg-9">	-->
		<?php


	
			/*$name = $_REQUEST['nameSearch'];
			if($name != ''){
				
				echo "<div class='well well-sm text-center' id='data'></div>";
			}*/


			$userId = $_REQUEST['userSearch'];
			if($userId != ''){
				
				echo "<div class='well well-sm text-center' id='info'></div>";
				echo "<div class='well well-sm text-center' id='data'></div><br><br><br>";
			}
			
		?>



	</div>
</body>
</html>





<script>
	var user = "<?php echo $userId; ?>";

	$(document).ready(function() {
		
		//show only log of patient
		if(user != '' || user.length){
			$("#reportrange").css({'visibility': 'visible'})
			$("#btnBack").css({'visibility': 'visible'})
			datepickerConf();
			getLogs(moment().format('DD-MM-YYYY'),moment().add(1,'days').format('DD-MM-YYYY'));
		}else{
			loadUsers();
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
			var T = '<table class="table table-striped table-bordered" id="bitacora"><thead><tr> ';
				T +="<th>Fecha</th>";
				T +="<th>Hora</th>";
				T +="<th>Descripcion</th>";
				T +="<th>Zona</th>";
				T +="<th>Modulo</th>";
				T +="<th>Submodulo</th>";
				T +="<th>Hora inicio de atenci&oacute;n</th>";
				T +="<th>Hora fin de atenci&oacute;n</th>";
				T +="<th>Pausas</th>";
				T +="<th>Tiempo en pausa</th>";
				T +="<th>Tiempo de atenci&oacute;n</th>";
				//T +="<th>Otros</th>";
				T +="</tr></thead><tbody>";

			for (var i = 0; i < json.length; i++) {
				T += '<tr><td>'+ ifNull(json[i].datetime.split(' ')[0]) +'</td>';
				T += '<td>'+ ifNull(json[i].datetime.split(' ')[1]) +'</td>';
				T += '<td>'+ ifNull(json[i].description) +'</td>';
				T += '<td>'+ ifNull(json[i].zone) +'</td>';
				T += '<td>'+ ifNull(json[i].module) +'</td>';
				T += '<td>'+ ifNull(json[i].submodule) +'</td>';
				T += '<td>'+ fixTime(json[i].attentionStart) +'</td>';
				T += '<td>'+ fixTime(json[i].attentionEnd) +'</td>';
				T += '<td>'+ ifNull(json[i].attentionPauses) +'</td>';
				T += '<td>'+ ifNull(json[i].timePauses) +'</td>';
				T += '<td>'+ ifNull(json[i].timeAttention) +'</td>';
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

	function search(user){
		$("#userSearch").val(user);
		$("#toSearch").submit();
	}

	function loadUsers(){
		$.ajax({
		    type: 'POST',
		    url: "modules/bitacora_user/getUsers.php",
		    data: { data:name } ,
		    beforeSend: function() {
		    	$("#data").html('<h1><i class="fa fa-spinner fa-spin"></i></h1>');
		    },
		    success: function(data) {
		    	var json = JSON.parse(data);
				$("#data").text('');
				var content = '';
				content = "<table id='userList' class='table table-striped table-bordered'> <thead> <tr><th>ID<th>Nombre</th><th>Usuario</th><th>Estado</th><th>Ver Historial</th></tr>  </thead><tbody>";
				for (var i = 0; i < json.length; i++) {
					content+="<tr><td>"+json[i].id+"</td><td>"+json[i].name+"</td><td>"+json[i].username+"</td><td>"+json[i].state+"</td><td><img onclick=search('"+json[i].id +"') src='../images/informe.png' style='cursor: pointer;' title='Ver lista'/></td></tr>";
				};
				content+=" </tbody></table>";
				$("#data").hide();
				$("#data").append(content);
				$("#data").fadeIn('slow');
			  	$('#userList').dataTable( {
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


	function datepickerConf () {
		var cb = function(start, end, label) {
	        $('#reportrange span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
	    }

		var optionSet1 = {
			startDate: moment(),
			endDate: moment().add(1,'days'),
			minDate: '01/01/2014',
			maxDate: moment().add(1,'days').format('DD/MM/YYYY'),
			
			showDropdowns: true,
			showWeekNumbers: true,
			ranges: {
			   'Hoy': [moment(), moment()],
			   'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			   'Ultimos 7 días': [moment().subtract(6, 'days'), moment()],
			   'Ultimos 30 días': [moment().subtract(29, 'days'), moment()],
			   'Este mes': [moment().startOf('month'), moment().endOf('month')],
			   'Ultimo Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
			   'Este año': [moment().startOf('year'), moment().endOf('year')],
			},
			opens: 'center',
			buttonClasses: ['btn btn-default'],
			applyClass: 'btn-small btn-primary',
			cancelClass: 'btn-small',
			format: 'DD-MM-YYYY',
			separator: ' Hasta ',
			locale: {
			    applyLabel: 'Aceptar',
			    cancelLabel: 'Limpiar',
			    fromLabel: 'Desde',
			    toLabel: 'Hasta',
			    customRangeLabel: 'Rango',
			    daysOfWeek: ['D', 'L', 'M', 'M', 'J', 'V','S'],
			    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			    firstDay: 1
			}
		};
		$('#reportrange span').html(moment().format('DD-MM-YYYY') + ' - ' + moment().format('DD-MM-YYYY'));
		$('#reportrange').daterangepicker(optionSet1, cb);
		$('#reportrange').on('apply.daterangepicker', function(ev, picker) { 
			
			initialDate = picker.startDate.format('YYYY-MM-DD');         
			finishDate = picker.endDate.add(1,'days').format('YYYY-MM-DD');
			getLogs(initialDate,finishDate);
		});
	}

	function getLogs(date1,date2){
		$.ajax({
		    type: 'POST',
		    url: "modules/bitacora_user/getLogData.php",
		    data: { user:user, date1:date1, date2:date2 } ,
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
	}

</script>
