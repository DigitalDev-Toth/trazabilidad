<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }

include 'libs/bootstrapStyle.php';

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>

	<!-- La del flojo... cuando este listo bajo las librerias xD-->
	<link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.5.1.css">
	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
 	<script src="http://cdn.oesmith.co.uk/morris-0.5.1.min.js"></script>
 	<!-- -->

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
			<div class="col-md-2">
				<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-align-justify"></span> Tipo</div>
				<select class="form-control" id="selectorFilter" >
					<option value="0">Zona</option>
					<option value="1">Modulos</option>
					<option value="2">SubModulo</option>
					<option value="3">Totem</option>
				</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-filter"></span> Filtro</div>
				<select class="form-control" id="intervale" >
					<option value="hour">Hora</option>
					<option value="minute">Minutos</option>
					<option value="second">Segundos</option>
				</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="input-group">
				<div class="input-group-addon"><span class="glyphicon glyphicon-stats"></span> Grafico</div>
				<select class="form-control" id="grType" >
					<option value="bar">Barras</option>
					<option value="area">Lineas</option>
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
					
					<div class="input-group-addon">Desde </div>
					<input type="text" type="text" class="form-control" id="date1" >
					<div class="input-group-addon">Hasta </div>
					<input type="text" type="text" class="form-control" id="date2">
				</div>


				</div>
			</div>


		</div>


	<div class="row" id='principalRow' >

		<div class="col-md-6">
			<div id="supervision"></div>	
		</div>


		<div class="col-md-6" id ='grapZone'>
		</div>
		
	</div>


</div>



</body>

<script>


var currentZone = 1;
var currentOptiion = 0;
var currenInterval ='hour';
var currentType = 'bar';
$(document).ready(function() {
	
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

	    while(ajax1(currentZone,"wtg",0));
	    //while(ajax1(currentZone,"tt",1));

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

$("#selectorZone").change(function(event) {
	currentZone=this.value;
	if(currentOptiion == 0){
		ajax1(currentZone,"wtg",parseInt(currentOptiion));
	}
	if(currentOptiion == 1){
		ajax1(currentZone,"mSm",parseInt(currentOptiion));
	}
});

$("#selectorFilter").change(function(event) {
	currentOptiion=this.value;
	if(currentOptiion == 0){
		ajax1(currentZone,"wtg",parseInt(currentOptiion));
	}
	if(currentOptiion == 1){
		ajax1(currentZone,"mSm",parseInt(currentOptiion));
	}
	if(currentOptiion == 2){
		ajax1(currentZone,"mSm",parseInt(currentOptiion));
	}
	if(currentOptiion == 3){
		ajax1(currentZone,"tt",parseInt(currentOptiion));
	}
	
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
			while(ajax1(currentZone,"wtg",0));
		}
	}
}

function zoneData(data){
	var json = JSON.parse(data);	
	var cont = '';

	cont  = '<table class="table table-bordered table-striped table-condensed"><tr><th></th><th>Espera</th><tr>';
	if(data != 0){
		cont += '<tr><td>Total de pacientes en espera: </td><td>'+ json.length +'</td></tr>';
		var date2 = new Date();
		var x=0,maxTime=-1,minTime=1000;
		for (var i = 0; i < json.length; i++) {

			var date1 = new Date(json[i].datetime);
			var minutes = Math.round(Math.abs(date1.getTime() - date2.getTime()) / 60000);

			if(maxTime<minutes){
				maxTime=minutes;
			}
			if(minTime>minutes){
				minTime=minutes;
			}
			x += minutes;
		};
		x=x/json.length;
		cont += '<tr><td>Tiempo maximo de espera </td><td>'+maxTime+' minutos</td></tr>';
		cont += '<tr><td>Tiempo minimo de espera </td><td>'+minTime+' minutos</td></tr>';
		cont += '<tr><td>Tiempo promedio de espera</td><td>'+parseInt(x)+' minutos</td></tr>';
	}else{
		cont += '<tr><td>Total de pacientes en espera: </td><td>0</td></tr>';
		cont += '<tr><td>Tiempo maximo de espera </td><td>0 minutos</td></tr>';
		cont += '<tr><td>Tiempo minimo de espera </td><td>0 minutos</td></tr>';
		cont += '<tr><td>Tiempo promedio de espera</td><td>0 minutos</td></tr>';
	}

	$.post('services/getStatiscal.php', {data: currentZone,type:"att", date1:currentInitialDate , date2:currentFinalDate}, function(data, textStatus, xhr) {
		var json = JSON.parse(data);
		var serv=0,notS=0;
		if(json != 0 ){
			for (var i = 0; i < json.length; i++) {
				if(json[i].attention == 'served'){
					serv++;
				}
				if(json[i].attention == 'no_serve'){
					notS++;
				}	
			};
			cont += '<tr><td>Total de tickets emitidos</td><td>'+json.length+'</td></tr>';
			cont += '<tr><td>Pacientes atendidos</td><td>'+serv+'</td></tr>';
			cont += '<tr><td>Pacientes no atendidos</td><td>'+notS+'</td></tr>';
		}else{
			cont += '<tr><td>Total de tickets emitidos</td><td>0</td></tr>';
		}
	
		cont += '<tr><td>Productividad</td><td>---</td></tr>';
		cont +="</table>";

		/*
		
		<div class="col-md-6">
			<div id="supervision"></div>	
		</div>


		<div id ='grapZone'>
		</div>
	
		*/
		$("#principalRow").html('<div class="row">'+cont+'</div><div class="row" id="createGraphic"><div id ="grapZone"></div></div>');

		//$("#supervision").html(cont);
		createGraphic('hour');

	});
}

$("#intervale").change(function(event) {
		currenInterval = this.value;
		if(currentOptiion == 0){
			createGraphic(currenInterval);	
		}
		if(currentOptiion == 3){
			ajax1(currentZone,"tt",parseInt(currentOptiion));
		}
		

	});

$("#grType").change(function(event) {
		currentType = this.value;
		if(currentOptiion == 0){
			createGraphic(currenInterval);	
		}
		if(currentOptiion == 3){
			ajax1(currentZone,"tt",parseInt(currentOptiion));
		}
		

	});


function createGraphic(interval){
	$('#grapZone').html('');
	
	var data = '';
	var cont = '<h4 class="text-center">Cantidad de tickets del dia</h4><div id="graphic" style="height: 200px;" class="well"></div>'
	$.ajax({
		url: 'services/grapStatiscal.php',
		type: 'GET',
		async: false,
		data: {data: currentZone, type:'zn', interval:interval,date1:currentInitialDate , date2:currentFinalDate},
	})
	.done(function(e) {
		data = e; 
	})
	.fail(function() {
		console.log("error");
	});
	
	var json = JSON.parse(data);
	$("#grapZone").html(cont);
	showGraph('graphic',currentType,json);


	
}

function moduleData (data) {
	var cont=''
	console.log(data);
	var modules = [];
	var json = JSON.parse(data);
	for (var i = 0; i < json.length; i++) {
		modules.push(json[i].module);	
	};
	var idModules = [];
	for (var i = 0; i < json.length; i++) {
		idModules.push(json[i].modulename);	
	};
	var onlyModules = [];
	$.each(idModules, function(i, el){
	    if($.inArray(el, onlyModules) === -1) onlyModules.push(el);
	});
	var onlyModulesID = [];
	$.each(modules, function(i, el){
	    if($.inArray(el, onlyModulesID) === -1) onlyModulesID.push(el);
	});

	for (var i = 0; i < onlyModulesID.length; i++) {
		var served = 0, q=0;
		var minT=0,maxT=0,aver=0;
		for (var j = 0; j < json.length; j++) {
			
			if(onlyModulesID[i] == json[j].module){
				served += json[j].others.served_tickets;
    			var MX = new Date(json[j].others.maxtime);
    			var MN = new Date(json[j].others.mintime);
    			var AV = new Date(json[j].others.average);
				minT += MN.getSeconds();
				maxT += MX.getSeconds();
				aver += AV.getSeconds(); 
				q++;

			}	

		};
		if(minT != 0){
			minT = minT/q;
		}
		if(maxT != 0){
			maxT = maxT/q;	
		}
		if(aver != 0){
			aver = aver/q;
		}
		cont += '<table class="table table-bordered table-striped table-condensed" id="T'+onlyModulesID[i]+'" ><tr><th></th><th>'+onlyModules[i]+'</th><tr>';
		cont += '<tr> <th>Numero de pacientes atendidos:</th> <td> '+served+' </td> </tr>';
		cont += '<tr> <th>Promedio maximo de atencion </th> <td> '+parseInt(maxT)+' </td> </tr>';
		cont += '<tr> <th>Promedio minimo de atencion </th> <td> '+parseInt(minT)+' </td> </tr>';
		cont += '<tr> <th>Promedio de atencion </th> <td> '+parseInt(aver)+' </td> </tr>';
		served = 0; maxT = 0; minT = 0; aver = 0;
		$.ajax({
			url: 'services/getStatiscal.php',
			type: 'GET',
			async: false,
			data: {data: onlyModulesID[i],type:"os",date1:currentInitialDate , date2:currentFinalDate},
		})
		.done(function(dataA) {
			if(dataA!=0){
				var jsonAttention = JSON.parse(dataA);
				cont += '<tr> <th>Numero de pacientes en atencion: </th> <td> '+jsonAttention.length+' </td> </tr>';
				cont += '<tr><td>Productividad</td><td>---</td></tr>';
				
			}else{
				cont += '<tr> <th>Numero de pacientes en atencion: </th> <td> 0 </td> </tr>';
				cont += '<tr><td>Productividad</td><td>---</td></tr>';
			}
		})
		.fail(function() {
			console.log("error");
		});
		cont +="</table>";
	};

		//$("#supervision").html(cont);
		$("#principalRow").html('<div class="col-md-6">'+cont+'</div><div class="col-md-6" id="graphic"></div>');

	
		//createGraphic('hour');
}

function totemData (data) {
	var json = JSON.parse(data);
	var cont = '';
	
	var modules = [];
	var json = JSON.parse(data);
	for (var i = 0; i < json.length; i++) {
		modules.push(json[i].id);	
	};
	var idModules = [];
	for (var i = 0; i < json.length; i++) {
		idModules.push(json[i].name);	
	};
	var onlyModules = [];
	$.each(idModules, function(i, el){
	    if($.inArray(el, onlyModules) === -1) onlyModules.push(el);
	});
	var onlyModulesID = [];
	$.each(modules, function(i, el){
	    if($.inArray(el, onlyModulesID) === -1) onlyModulesID.push(el);
	});





	var b = [], prev;
    modules.sort();
    for ( var i = 0; i < modules.length; i++ ) {
        if ( modules[i] !== prev ) {
            b.push(1);
        } else {
            b[b.length-1]++;
        }
        prev = modules[i];
    }
    //onlyModules.sort();

    cont  = '<table class="table table-bordered table-striped table-condensed"><tr><th colspan="2" class="text-center">Tothtem</th><tr>';
    if(data != 0){
    	cont += '<tr><th align="right">Total tickets retirados:</th><th>'+json.length+'</th> </tr>';
	    for (var i = 0; i < onlyModules.length; i++) {
	    	cont += '<tr><td>Tickets retirados de '+onlyModules[i]+':</td><td>'+b[i]+'</td> </tr>';
	    };
    	cont += '<tr><td>Hora del primer ticket retirado:</td><td>'+json[0].datetime+'</td><tr><td>Hora del ultimo ticket retirado</td><td>'+json[json.length-1].datetime+'</td> </tr></table>';	

    }else{
    	cont += '<tr><td>No se han solicitado tickets para ningun modulo...</td></tr>';
    }
  
//		$("#supervision").html(cont);
	
		$("#principalRow").html('<div class="row">'+cont+'</div> <div class="row" id="grapZone">  </div>');

    totemGraph(currenInterval,onlyModulesID,onlyModules);

}

function totemGraph (interval,ids,names) {
	var data = '';
	$("#grapZone").html('');

	var contents = '';
	var colmd=12;
	if(ids.length > 0 ){
		colmd = parseInt(colmd / ids.length) ;
	}
	for (var i = 0; i < ids.length; i++) {
		$.ajax({
			url: 'services/grapStatiscal.php',
			type: 'GET',
			async: false,
			data: {data: currentZone, type:'tt', interval:interval, module:ids[i] ,date1:currentInitialDate , date2:currentFinalDate },
		})
		.done(function(e) {
			var json = JSON.parse(e);
			console.log(json);
			contents = '<div class="col-md-'+colmd+'"><h4 class="text-center">'+names[i] +'</h4><div id=G'+i+' style="height: 200px;" class="well"></div></div>';
			$('#grapZone').append(contents);
			var idDiv = 'G'+i;
			showGraph(idDiv,currentType,json);
		
		})
		.fail(function() {
			console.log("error");
		});
	
	};

	
}


function showGraph(idDiv,type,json){
	if(type == 'bar'){
		Morris.Bar({
		  element: idDiv,
		  data: json,
		  hideHover: 'auto',
		  xkey: 'hora',
		  ykeys: ['cantidad'],
		  labels: ['cantidad'],
		  xLabels:'hour'
		});
	}else{
		Morris.Area({
		  element: idDiv,
		  data: json,
		  hideHover: 'auto',
		  xkey: 'hora',
		  ykeys: ['cantidad'],
		  labels: ['cantidad'],
		  
		});
	}
}

function ajax1(zoneId,type,order){
	console.log(currentInitialDate,currentFinalDate);
	$.ajax({
		async:false, 
		url: 'services/getStatiscal.php',
		type: 'GET',
		data: {data: zoneId,type:type , date1:currentInitialDate , date2:currentFinalDate},
	})
	.done(function(e) {
		switch(order) {
	        case 0:
	        zoneData(e);
	        break;

	        case 1:
	        moduleData(e);
	        break;

	        case 2:
	        subModuleData(e);
	        break;

	        case 3:
	        totemData(e);
	        break;

		}
		return false;	
	})
	.fail(function() {
		return true;
	})
	
}


function subModuleData(data) {
	var modules = [];
	var json = JSON.parse(data);
	console.log(data);
	for (var i = 0; i < json.length; i++) {
		modules.push(json[i].module);	
	};
	var idModules = [];
	for (var i = 0; i < json.length; i++) {
		idModules.push(json[i].modulename);	
	};
	var onlyModules = [];
	$.each(idModules, function(i, el){
	    if($.inArray(el, onlyModules) === -1) onlyModules.push(el);
	});
	var onlyModulesID = [];
	$.each(modules, function(i, el){
	    if($.inArray(el, onlyModulesID) === -1) onlyModulesID.push(el);
	});
	var totalPatient = 0;

	var tableText="",cols = 0,X=0,datas = ["Nombre ejecutiva:","Cantidad de Pacientes atendidos","Promedio de atencion","Promedio Minimo de atencion","Promedio maximo de atencion","Tiempo de actividad"];

	for (var i = 0; i < onlyModules.length; i++) {
		for (var j = 0; j < json.length; j++) {
			if(onlyModules[i] == json[j].modulename){
				cols++;
			}
		};
		//console.log(cols);
		tableText += "<table class='table table-bordered table-striped table-condensed'>";
		tableText += "<tr><td></td>"; 
		tableText += "<td colspan='"+(cols)+"' class='text-center'><b>"+ onlyModules[i] +"</b></td></tr>";
		tableText += "<tr><td></td>";
		for (var j = X; j < cols; j++) {
			
				tableText += "<th class='text-center'>"+json[j].submodulename+"</th>";	
			
			
			
		};
		
		var totalHours = '';
		
		$.ajax({
			url: 'services/getStatiscal.php',
			type: 'GET',
			async : false,
			data: {data: onlyModulesID[i],type:"pd", date1:currentInitialDate , date2:currentFinalDate},
		})
		.done(function(data) {
			totalHours = JSON.parse(data);
		})
		.fail(function() {
			console.log("error");
		});
		




		for (var k = 0; k < datas.length ; k++) {
			tableText +="<tr><td align='right'>"+datas[k]+"</td>";
			for (var j = X; j < cols; j++) {

				switch(k) {
			    	case 0:
			        tableText += "<td class='text-center'>"+json[j].user+"</td>";
			        break;
			    	case 1:
			    	tableText += "<td class='text-center'>"+json[j].others.served_tickets+"</td>";
			    	totalPatient += json[j].others.served_tickets;
			        break;
			        case 2:
			        var d = new Date(json[j].others.average);

			        tableText += "<td class='text-center'>"+d.getSeconds()+" Segundos</td>";
			        break;
			        case 3:
			        var d = new Date(json[j].others.mintime);
			        tableText += "<td class='text-center'>"+d.getSeconds()+" Segundos</td>";
			        break;
			        case 4:
			        var d = new Date(json[j].others.maxtime);
					tableText += "<td class='text-center'>"+d.getSeconds()+" Segundos</td>";			       
			        break;

			        case 5:
			        var lastLogin = '',lastClose = '';
			        for (var l = 0; l < totalHours.length; l++) {
			        	if(totalHours[l].users == json[j].username ){
			        		if(totalHours[l].description.search("Inicio") != -1 || totalHours[l].description.search("inicio") != -1) {
   								lastLogin = totalHours[l].datetime;
							}
							if(totalHours[l].description.search("Cierre") != -1) {
   								lastClose = totalHours[l].datetime;
							} 
			        	}
			        };

			        if(lastLogin != ''){

			        	var date1 = new Date(lastLogin);
			        	var date2 = new Date();
			        	var diff = date2.getTime() - date1.getTime();
			        	var diffHrs = Math.round((diff % 86400000) / 3600000); // hours
						var diffMins = Math.round(((diff % 86400000) % 3600000) / 60000); // minutes

			        	tableText += "<td class='text-center'>"+diffHrs+" Horas con "+diffMins+" minutos</td>";
			        }else{
			        	tableText += "<td class='text-center'>Sin hora de sesion</td>";
			        }

			

			        break;


				}

				//tableText += "<td class='text-center'>"+json[j].user+"</td>";
			};
			tableText +="</tr>";	
		};
		X=cols;
		tableText += "</tr>";

		//var totalHours = getPd(onlyModulesID[i],"pd");

		//productividad
	/*	$.post('services/getStatiscal.php',  {data: onlyModulesID[i],type:"pd"}, function(data, textStatus, xhr) {
			var totalHours = JSON.parse(data);
			console.log(totalHours);
			if(totalHours != 0){
							var initialHour = totalHours[0].datetime;
			var finalHour = totalHours[totalHours.length-1].description;
			if(finalHour.indexOf("Cierre") !=-1){
				finalHour = new Date();
			}else{
				finalHour = totalHours[totalHours.length-1].datetime;
			}
			
			finalHour = new Date(finalHour);
			initialHour = new Date(initialHour);
			
			}



		});*/
		tableText+="</table><br>";
	};
	//console.log(json[1].others.maxtime);
	$("#principalRow").html('<div class="row">'+tableText+'</div> ');

	
}

</script>
</html>


