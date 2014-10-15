<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }

include 'libs/bootstrapStyle.php';

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>



<div class="container">
	<div class="row">
		<div class="row text-center well well-sm ">
			<div class="col-md-2">
				<label style="margin-top: 8px;"><span class="glyphicon glyphicon-th-list"></span> Supervision</label>
			</div>

			<div class="col-md-1 ">
					<label style="margin-top: 8px;">Zona: </label>	
			</div>

			<div class="col-md-2">
				<h4 id="loading"><i class="fa fa-spinner fa-spin"></i></h4>
				<select class="form-control" id="selectorZone" style="display:none">
				</select>
			</div>

			<div class="col-md-1 ">
					<label style="margin-top: 8px;">Filtro: </label>	
			</div>

			<div class="col-md-2">
				<select class="form-control" id="selectorZone" >
					<option value="0">Zona</option>
					<option value="1">Modulos</option>
					<option value="2">SubModulo</option>
				</select>
			</div>

		</div>
	
	</div>

	<div class="row">

	<div id="waitingDiv">
		
	</div>

		
	</div>


</div>



</body>

<script>


var currentZone = 1;
var currentOptiion=0;



$(document).ready(function() {
	
	$.ajax({
		url: 'services/getZoneInfo.php',
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
	    //changeZone(currentZone,switchZone);
	    while(ajax1(currentZone,"wtg",currentOptiion));
	})
	.fail(function() {
		window.setTimeout('location.reload()', 100);

	});


});




//change current zone
$("#selectorZone").change(function() {
	currentZone=this.value;
	//changeZone(this.value,switchZone);
	ajax1(currentZone,"wtg",currentOptiion);
});


function fillWaiting(data){
	var json = JSON.parse(data);	
	var cont = '';
	console.log(data);
	cont  = '<table class="table table-bordered table-striped "><tr><th></th><th>Espera</th><tr>';
	if(data != 0){
		cont += '<tr><td>Total de pacientes en espera: </td><td>'+ json.length +'</td></tr>';
		var date2 = new Date();
		var x=0,maxTime=-1,minTime=1000;
		for (var i = 0; i < json.length; i++) {
			var date1 = new Date(json[0].datetime);
			var minutes = Math.round(Math.abs(date1.getTime() - date2.getTime()) / 60000);
			console.log(minutes);
			if(maxTime<minutes){
				maxTime=minutes;
			}
			if(minTime>minutes){
				minTime=minutes;
			}
			x += minutes;
			

		};
		cont += '<tr><td>Pacientes con espera menor a 10 </td><td>'+x+'</td></tr>';
		cont += '<tr><td>Pacientes con espera entre 10 y 20 minutos </td><td>'+minTime+'</td></tr>';
		cont += '<tr><td>Pacientes con espera mayor a 20 minutos </td><td>'+maxTime+'</td></tr>';
	}else{
		cont += '<tr><td>Total de pacientes en espera: </td><td>0</td></tr>';
	}
	cont +="</table>";



	$("#waitingDiv").html(cont);
	//console.log(json);
}
function ajax1(zoneId,type,order){
	$.ajax({
		async:false, 
		url: 'services/getInfoTables.php',
		type: 'GET',
		data: {data: zoneId,type:type},
	})
	.done(function(e) {
		switch(order) {
	        case 0:
	        fillWaiting(e);
	        break;
		}
		return false;	
	})
	.fail(function() {
		return true;
	})
	
}

</script>
</html>


