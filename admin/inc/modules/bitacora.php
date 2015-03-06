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
<script src="js/validaRut.js" type="text/javascript"></script>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="js/datatables2/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="js/datatables2/css/dataTables.tableTools.css">
<link rel="stylesheet" type="text/css" href="js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="js/datapickerB/css/daterangepicker-bs3.css" />
<body>
	<div class="container">
		<div class="row">
			<form class="form-horizontal" id="toSearch" role="form" name="between" method="POST">
					<div class="row well well-sm ">
						<div class="col-md-2 col-xs-2 col-sm-2 col-lg-2">
							<label style="margin-top: 8px;"><span class="glyphicon glyphicon-th-list"></span> Bitacora</label>
						</div>
						<div class="col-md-1 col-xs-1 col-sm-1 col-lg-1">
							<label class="radio-inline"><input id='radioRut' type="radio" name="optradio" onchange="changeID('rut');">RUT</label>
							<label class="radio-inline"><input type="radio" name="optradio" onchange="changeID('dni');">DNI</label>
						</div>
						<div class="col-md-3 col-xs-3 col-sm-3 col-lg-3">
							<div id="idView" class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-bookmark"></span> RUT</div>
									<input id="idViewText" type="text" class="form-control" id="rutSearch" name="rutSearch" onblur="Valida_Rut(this)"  onKeydown="Javascript: if (event.keyCode==13) Valida_Rut(this);" placeholder="Ingrese RUT"/>
							</div>
						</div>
						<div class="col-md-3 col-xs-3 col-sm-3 col-lg-3">
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span> Nombres</div>
								<input type="text" class="form-control" id="nameSearch" name="nameSearch" placeholder="Ingrese nombre y/o apellido">
							</div>
						</div>
						<div class="col-md-1 col-xs-1 col-sm-1 col-lg-1">
							<button id="button" name="beetween"  class="btn btn-primary" type="submit" onclick="submit();"><span class="glyphicon glyphicon-search"></span> Buscar</button>
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
			echo '<div class="bs-example">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#sectionA">Sec 1</a></li>
						<li><a data-toggle="tab" href="#sectionB">Sec 2</a></li>
					</ul>
					<div class="tab-content">
						<br>
						<div id="sectionA" class="tab-pane fade in active">
							<div class="well well-sm text-center" id="info">
							</div>
							<div class="well well-sm text-center" id="options">
								<div class="row">
									<div class="col-md-3 col-sm-3 col-xs-3">
										<div class="btn-group" role="group" aria-label="..." id = "buttonsGroups">
									</div>
								</div>
								<div class="col-md-offset-5 col-md-2 col-xs-offset-5 col-xs-2 col-sm-offset-5 col-sm-2">
									<div id="reportrange" class="form-control" style="cursor: pointer;">
										<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
										<span></span> <b class="caret"></b>
									</div>
								</div> 
							</div>

						</div>
						<div class="well well-sm text-center" id="data">
						</div><br>
					</div>
			<div id="sectionB" class="tab-pane fade">
			</div>
			</div>
			</div>
				';


			}
			
		?>
	</div>
</body>
</html>
<script src="js/datatables2/js/jquery.js"></script>
<script src="js/datatables2/js/jquery.dataTables.min.js"></script>
<script src="js/datatables2/js/dataTables.tableTools.js"></script>
<script src="js/datatables2/js/dataTables.bootstrap.js"></script>
<script src="js/datapickerB/js/moment.js"></script>
<script src="js/datapickerB/js/daterangepicker.js"></script>
<script src="js/bootstrap/js/bootstrap.min.js"></script>
<script>

var rut='',state=0;
$(document).ready(function() {
	rut = "<?php echo $rut; ?>";
	var name = '<?php echo $name; ?>';
	$("#radioRut").attr('checked', true);
	//show only log of patient
	if(rut != '' || rut.length){
		getPatientInfo(rut);
		createRangePicker();
	}else{
		//show list of patients
		if(name != ''){
			getPatinetsList(name);
		}
	}
});



function getPatientInfo(rut,dateI,dateF,hourI,hourF) {
	$.ajax({
	    type: 'POST',
	    url: "services/getBitacora.php",
	    data: { data:rut, type:1 } ,
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
	    data: { rut:rut , dateI:dateI, dateF:dateF,hourI:hourI,hourF:hourF } ,
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



function getPatinetsList (name) {
	$.ajax({
	    type: 'POST',
	    url: "services/getBitacora.php",
	    data: { data:name, type:2 } ,
	    beforeSend: function() {
	    	$("#data").html('<h1><i class="fa fa-spinner fa-spin"></i></h1>');
	    },
	    success: function(data) {
	    	var json = JSON.parse(data);
			$("#data").text('');
			var content = '';
			content = "<table id='patientResults' class='table table-striped table-bordered'> <thead> <tr><th>Nombre</th> <th>Rut</th><th>Ver Bitacora</th></tr>  </thead><tfoot> <tr><th>Nombre</th> <th>Rut</th></tr>  </tfoot><tbody>";
			for (var i = 0; i < json.length; i++) {
				content+="<tr><td>"+json[i].name+" "+json[i].lastname+"</td> <td>"+json[i].rut+"</td><td><button  class='btn btn-default' onclick=search('"+json[i].rut +"')><span class='glyphicon glyphicon-search' aria-hidden='true'></span></button> </td></tr>";
			};
			content+=" </tbody></table>";
			$("#data").hide();
			$("#data").append(content);
			$("#data").fadeIn('slow');
		  	tableConfig('patientResults');
	    },
	    error: function(xhr) { 
	    	
	    },
	    complete: function() {
	    }
	});
}

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
		$.post('services/getBitacora_plan.php', {rut: json[0]["rut"]}, function(data, textStatus, xhr) {
			var infoPlans = JSON.parse(data);
			var patientHtml = '<table class="table table-bordered table-condensed">';
			//patientHtml += '<tr><th>RUT/DNI: </th><td>'+json[0]["rut"]+'</td>  <th>N° Ficha:</td><td>1</td>    <th>Estado Actual</td><td>1</td>  </tr>';
			//patientHtml += '<tr><th>Nombre: </th><td>'+ifNull(json[0]["name"])+' '+ifNull(json[0]["lastname"])+' <th>N° Plan de Tratamiento:</td><td>'+infoPlans["planes"]+'</td>    <th>Maximo T. de espera:</td><td>1</td></tr>';
			if(json[0]["gender"]=="M" && json[0]["gender"]!=null){
				json[0]["gender"]="Masculino";
			}else{
				json[0]["gender"]="Femenino";
			}
			patientHtml += '<tr><th>RUT/DNI: </th><td>'+json[0]["rut"]+'</td><th>Nombre: </th><td>'+ifNull(json[0]["name"])+' '+ifNull(json[0]["lastname"])+'</td><th>Género: </th><td>'+ifNull(json[0]["gender"])+'</td></tr>';
			patientHtml += '<tr><th>Dirección: </th><td>'+ifNull(json[0]["address"])+'</td><th>Fecha de Nacimiento:</th><td>'+ifNull(json[0]["birthdate"])+'</td><th></th><td></td></tr>';

			patientHtml += '</table>';
			$("#info").html("");
			$("#info").html(patientHtml);

			patientHtml = '<button type="button" class="btn btn-default" id="groupa" onclick="getPatientInfo(\''+json[0]["rut"]+'\');">Bitacora</button>';
			patientHtml += '<button type="button" class="btn btn-default" id="groupb" onclick="filterPlanPres(\''+json[0]["rut"]+'\');">Planes de tratamiento</button>';
			patientHtml += '<button type="button" class="btn btn-default" id="groupc" onclick="filterPlanPres(\''+json[0]["rut"]+'\');">Presupuestos</button>';

			//patientHtml += '<button class="btn btn-primary" onclick="filterPlanPres(\''+json[0]["rut"]+'\');"><span class="glyphicon glyphicon-search"></span> Ver Planes de Tratamiento y Presupuesto</button>';
			$("#buttonsGroups").html("");
			$("#buttonsGroups").html(patientHtml);
			
		});	
	}
}
function createRangePicker() {
	var cb = function(start, end, label) {
			$('#reportrange span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
	}

	var optionSet1 = {
		startDate: moment(),
		endDate: moment().add(1,'days'),
		minDate: '01/01/2014',
		maxDate: moment().add(1,'days').format('DD/MM/YYYY'),
		timePicker: true,
		timePickerIncrement: 5,
		timePicker12Hour:false,
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
		format: 'DD-MM-YYYY HH:mm',
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
	$('#reportrange span').html(moment().format('DD-MM-YYYY') + ' - ' + moment().add(1,'days').format('DD-MM-YYYY'));
	$('#reportrange').daterangepicker(optionSet1, cb);
	//$('.calendar-time').hide();

	$('#reportrange').on('apply.daterangepicker', function(ev, picker) { 


		var start = picker.startDate.format('YYYY-MM-DD HH:mm').split(" ");
		var end = picker.endDate.format('YYYY-MM-DD HH:mm').split(" ");

		if($('#checkHour').is(':checked')){
			getPatientInfo(rut,start[0],end[0],start[1],end[1]);
		}else{
			getPatientInfo(rut,start[0],end[0],null,null);
		}
		state=0;

		
	});



}

function fillTable (data) {
	if(data != 0){
		var json = JSON.parse(data);
		var T =  '<table class="table table-striped table-bordered" id="bitacora">';
			T += "<thead><tr>";
			T += "<th>Fecha</th>";
			T += "<th>Hora</th>";
			T += "<th>Descripcion</th>";
			T += "<th>Zona</th>";
			T += "<th>Modulo</th>";
			T += "<th>Submodulo</th>";
			T += "<th>Usuario</th>";
			T += "<th>Hora inicio de espera</th>";
			T += "<th>Hora inicio de atencion</th>";
			T += "<th>Hora fin</th>";
			T += "<th>Total espera</th>";
			T += "<th>Total atencion</th>";
			T += "</tr></thead>";

			T +=  "<tfoot><tr>";
			T += "<th>Fecha</th>";
			T += "<th>Hora</th>";
			T += "<th>Descripcion</th>";
			T += "<th>Zona</th>";
			T += "<th>Modulo</th>";
			T += "<th>Submodulo</th>";
			T += "<th>Usuario</th>";
			T += "<th>Hora inicio de espera</th>";
			T += "<th>Hora inicio de atencion</th>";
			T += "<th>Hora fin</th>";
			T += "<th>Total espera</th>";
			T += "<th>Total atencion</th>";
			T += "</tr></tfoot>";

			T += "<tbody>";

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
		T += '</tbody></table>';
		$("#data").html(T);
		tableConfig('bitacora');
		$("#groupa").removeClass().addClass('btn btn-primary');
		$("#groupb").removeClass().addClass('btn btn-default');
		$("#groupc").removeClass().addClass('btn btn-default');
	}else{
		$("#data").html('<h2>Sin resultados</h2>');
	}
	

}

function tableConfig(table) {
	
		$('#'+table+' tfoot th').each( function () {
			var title = $('#'+table+' thead th').eq( $(this).index() ).text();
			$(this).html( '<input type="text" style="width:100%"  placeholder="'+title+'" />' );
		});



  	var table =  $('#'+table).DataTable( {
    	"sDom": 'TR<"clear">lfrtip',
    	//"sDom": 'R<"clear">lfrtip',
    	"tableTools": {
			"sSwfPath": "js/datatables2/swf/copy_csv_xls_pdf.swf"
		}
		/*"language": {
			"url": "js/datatables2/languaje/languaje.lang"
	  	}*/
    });
	table.columns().eq( 0 ).each( function ( colIdx ) {
		$( 'input', table.column( colIdx ).footer() ).on( 'keyup change', function () {
			table.column( colIdx ).search( this.value ).draw();
	    });
	});
}


function search(rut){
	$("#rutSearch").val(rut);
	$("#toSearch").submit();
}



function filterPlanPres(rut){
	$.ajax({
	    type: 'POST',
	    url: "services/getBitacora_PlanBudgets.php",
	    data: { rut:rut} ,
	    beforeSend: function() {
	    	$("#data").html('<h1><i class="fa fa-spinner fa-spin"></i></h1>');
	    },
	    success: function(data) {
	    	//fillTable(data);
			if(data != 0){
				var json = JSON.parse(data);
				var T = '<table class="table table-striped table-bordered" id="bitacora"><thead><tr> ';
					T +="<th>Origen</th>";
					T +="<th>Plan de Tratamiento Nº</th>";
					T +="<th>Médico</th>";
					T +="<th>Fecha y Hora Plan</th>";
					T +="<th>Fecha y Hora Presupuesto</th>";
					T +="</tr></thead><tbody>";

				for (var i = 0; i < json.length; i++) {
					T += '<tr><td>'+ ifNull(json[i].origin) +'</td>';
					T += '<td>'+ ifNull(json[i].plan_number) +'</td>';
					T += '<td>'+ ifNull(json[i].medical) +'</td>';
					T += '<td>'+ ifNull(json[i].plan_time) +'</td>';
					T += '<td>'+ ifNull(json[i].budget_time) +'</td></tr>';
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


		$("#groupa").removeClass().addClass('btn btn-default');
		$("#groupb").removeClass().addClass('btn btn-primary');
		$("#groupc").removeClass().addClass('btn btn-default');


	    },
	    error: function(xhr) {
	    	//do a barrel roll
	    }
	});
}

function changeID(type){//Búsqueda por RUT o DNI
	if(type=='rut'){
		$("#idView").children().html('<span class="glyphicon glyphicon-bookmark"></span> RUT');
		$("#idViewText").attr({
			onblur: 'Valida_Rut(this)',
			onkeydown: 'Javascript: if (event.keyCode==13) Valida_Rut(this)',
			placeholder: 'Ingrese RUT'
		});
	}else{
		$("#idView").children().html('<span class="glyphicon glyphicon-bookmark"></span> DNI');
		$("#idViewText").removeAttr('onblur');
		$("#idViewText").attr({
			onkeydown: 'Javascript: if (event.keyCode==13) submit()',
			placeholder: 'Ingrese DNI'
		});
	}
}

</script>
