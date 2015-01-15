<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }

include 'libs/bootstrapStyle.php';

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>

 	<link rel="stylesheet" href="../inc/js/datapickerB/css/datepicker3.css">
 	<script src="../inc/js/datapickerB/js/bootstrap-datepicker.js"></script>
 	<script src="../inc/js/datapickerB/js/locales/bootstrap-datepicker.es.js"></script>
 	



</head>
<body>



<div class="container">
	<div class="row">
		<div class="row text-center well well-sm ">
			<div class="col-md-1">
				<label style="margin-top: 8px;"><span class="glyphicon glyphicon-th-list"></span> Estadistica</label>
			</div>


			<div class="col-md-2">
				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span> Zona</div>
					<h4 id="loading"><i class="fa fa-spinner fa-spin"></i></h4>
					<select class="form-control" id="selectorZone" style="display:none">
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-align-justify"></span> Sub-módulos</div>
					<h4 id="loading1"><i class="fa fa-spinner fa-spin"></i></h4>
					<select class="form-control" id="selectorSubmodule" style="display:none">
					</select>
				</div>
			</div>

			<div class="col-md-2">
				<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-filter"></span> Rango de tiempo</div>
				<select class="form-control" id="filter" >
					<option value="day">Dia</option>
					<option value="week">Semana</option>
					<option value="month">Mes</option>
					<option value="year">Año</option>
					<option value="range">Rango fecha</option>
				</select>
				</div>
			</div>

			<div class="col-md-3 text-center">
				<!--<div class="input-daterange input-group" id="datepicker" data-provide="datepicker">
				   	<div class="input-group-addon">Desde</div>
				    <input type="text" class="form-control" name="start" />
				    <div class="input-group-addon">Hasta</div>
				    <input type="text" class="form-control" name="end" />

				    -->
				<div class="input-group">

					<div class="input-group-addon" id="field1">Día </div>
					<input type="text" type="text" class="form-control" id="date1" readonly>


					<div class="input-group-addon" id="field2">Hasta </div>
					<input type="text" type="text" class="form-control" id="date2" readonly>

				</div>


				</div>
			</div>


		</div>



</div>



</body>

<script>



//

$("#filter").change(function(event) {
	var type = this.value;
	$('#date2').hide();
	$('#field2').hide();
	$("#date1").val("");
	$("#date2").val("");
	if(type == 'day'){
		$("#field1").text("Día");
		$("#field2").hide();
		$("#date2").hide();
	}
	if(type == 'week'){
		$("#field1").text("Semana");
		$("#date1").datepicker("remove");
		$("#date1").datepicker( {
		    format: "yyyy-mm-dd",
		    startView: "months", 
		    minViewMode: "months"
		});
	}
	if(type == 'month'){
		$("#field1").text("Mes");
		$("#date1").datepicker("remove");
		$("#date1").datepicker( {
		    format: "yyyy-mm-dd",
		    startView: "months", 
		    minViewMode: "months"
		});
	}
	if(type == 'year'){
		$("#field1").text("Año");
		$("#date1").datepicker("remove");
		$("#date1").datepicker( {
		    format: "yyyy-mm-dd",
		    startView: "years", 
		    minViewMode: "years"
		});
	}
	if(type == 'range'){
		$('#date2').show();
		$('#field2').show();

		$("#field1").text("Desde");
		$("#field2").text("Hasta");

		$("#date1").datepicker("remove");
		$('#date1').datepicker({
			language: "es",
			format: "yyyy-mm-dd",
			calendarWeeks: true,
			autoclose: true,
			todayHighlight: true,
			todayBtn: "linked",
		});
	}

});



//




var currentZone = 1;
var currentFilter = 'day';
$(document).ready(function() {
	$('#date2').hide();
	$('#field2').hide();
	$('#date1').datepicker({
		language: "es",
		format: "yyyy-mm-dd",
		calendarWeeks: true,
		autoclose: true,
		todayHighlight: true,
		todayBtn: "linked",
	});

	$('#date2').datepicker({
		language: "es",
		format: "yyyy-mm-dd",
		autoclose: true,
		calendarWeeks: true,
		todayHighlight: true,
		todayBtn: "linked",
	});

	$.ajax({
		url: 'modules/statistical/getZoneInfo.php',
		type: 'GET',
	})
	.done(function(e) {
		
		var data = JSON.parse(e);
	    var sel = $("#selectorZone");
	    sel.empty();
	    for (var i=0; i<data.length; i++) {
	      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
	    }
	    $("#loading").fadeOut('slow', function() {
	    	$("#selectorZone").fadeIn('slow', function() {
	    	});
	    });
	})
	.fail(function() {
		window.setTimeout('location.reload()', 100);

	});

	$.ajax({
		url: 'modules/statistical/getModuleInfo.php',
		type: 'GET',
		data: {zone: currentZone},
	})
	.done(function(e) {
		console.log(e);
		var data = JSON.parse(e);
	    var sel = $("#selectorSubmodule");
	    sel.empty();
	    for (var i=0; i<data.length; i++) {
	      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
	    }
	    $("#loading1").fadeOut('slow', function() {
	    	$("#selectorSubmodule").fadeIn('slow', function() {
	    	});
	    });
	})
	.fail(function() {
		window.setTimeout('location.reload()', 100);

	});


});

$('#date1').change(function(event) {
	setRange();
});
$('#date2').change(function(event) {
	setRange();
});


var currentInitialDate =  new Date();
var currentFinalDate = new Date();
currentFinalDate.setDate(currentFinalDate.getDate()+1);
currentInitialDate =  currentInitialDate.toISOString().slice(0,10).replace(/-/g,"-");
currentFinalDate =  currentFinalDate.toISOString().slice(0,10).replace(/-/g,"-");


function setRange() {
	currentInitialDate = $('#date1').val();
	currentFinalDate = $('#date2').val();
	if(currentInitialDate != ''){
		if(currentFinalDate != ''){
			
		}
	}
}

</script>
</html>


