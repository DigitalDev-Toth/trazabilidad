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
	<script type="text/javascript" src="../inc/modules/statistical/moment.js"></script>
	<script type="text/javascript" src="../inc/modules/statistical/daterangepicker.js"></script>
	


	<script src="http://code.highcharts.com/highcharts.js"></script>
	<script src="http://code.highcharts.com/modules/exporting.js"></script>


	<link rel="stylesheet" type="text/css" href="../inc/modules/statistical/daterangepicker-bs3.css" />

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
					<select class="form-control" id="selectorSubmodule" style="display:none">+
							<option value="x">Todos</option>	
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
			   <div id="reportrange" class="form-control" style="cursor: pointer;">
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                  <span></span> <b class="caret"></b>
               </div>
				</div>
			</div>


		</div>

		<div class="row">
			<div class="col-md-6" id="generalData">
			</div>
			<div class="col-md-6 id='waitG' ">
				<div id="waitingG"  style="height: 280px;"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" id="graphic1">
				
			</div>
		</div>

		<div class="row">
			<div class="col-md-12" id="moduleData">
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" id="sbData">
			</div>
		</div>		



</div>
<br><br><br>



</body>

<script>






var currentZone = 1;
var currenType = 1;
var initialDate = moment().format('YYYY-MM-DD');
var finishDate = moment().add(1,'days').format('YYYY-MM-DD');
var currentModule = 0;
var modulesInfo='';

$("#selectorZone").change(function(event) {
	currentZone = this.value;
	showReport();
});


$("#selectorSubmodule").change(function(event) {
	currentModule = this.value;

	showReport();
});




$(document).ready(function() {
	datepickerConf();
	$.ajax({
		async:false, 
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
		async:false, 
		url: 'modules/statistical/getModuleInfo.php',
		type: 'GET',
		data: {zone: currentZone},
	})
	.done(function(e) {
		var data = JSON.parse(e);
		modulesInfo=data;
	    var sel = $("#selectorSubmodule");
	    sel.empty();
	    sel.append('<option value="0">Todos</option>');
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
	showReport();

});

function showReport() {
	initialDate = moment(initialDate).format('YYYY-MM-DD');
	finishDate = moment(finishDate).format('YYYY-MM-DD');
	if(finishDate == initialDate){
		finishDate = moment(finishDate).add(1,'days').format('YYYY-MM-DD');
	}

	while(ajax1(currentZone,"wtg",0));
	while(ajax1(currentZone,"mSm",1));
	subModuleDataX();
	subModuleData();	
}

function ajax1(zoneId,type,order){
	
	$.ajax({
		async:false, 
		url: 'modules/statistical/getStatiscal.php',
		type: 'GET',
		data: {data: zoneId,type:type , date1:initialDate , date2:finishDate , data2:currentModule},
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

var minWT =0,normalWT=0,maxWT=0;
function zoneData(data){
	var json = JSON.parse(data);	
	var cont = '';

	cont  = '<table class="table table-bordered table-striped table-condensed"><tr><th></th><th>Espera</th><tr>';
	if(data != 0){
		//cont += '<tr><td>Total de pacientes en espera: </td><td>'+ json.length +'</td></tr>';
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

			if(minutes<10){
				minWT++;
			}
			if(minutes>=10 && minutes<30 ){
				normalWT++;
			}
			if(minutes>=30){
				maxWT++;
			}


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

	$.post('modules/statistical/getStatiscal.php', {data: currentZone,type:"att", date1:initialDate , date2:finishDate ,data2:currentModule}, function(data, textStatus, xhr) {
		var json = JSON.parse(data);
		var serv=0,notS=0;
		if(json != 0 ){

			for (var i = 0; i < json.length; i++) {
				if(json[i].attention == 'served' || json[i].attention == 'derived'){
					serv++;

				}
				if(json[i].attention == 'no_serve'){
					notS++;
				}	
				console.log(json[i].attention);
			};
			cont += '<tr><td>Total de tickets emitidos</td><td>'+json.length+'</td></tr>';
			cont += '<tr><td>N° total de pacientes atendidos</td><td>'+serv+'</td></tr>';
			cont += '<tr><td>N° total de pacientes no atendidos</td><td>'+notS+'</td></tr>';
		}else{
			cont += '<tr><td>Total de tickets emitidos</td><td>0</td></tr>';
			cont += '<tr><td>N° total de pacientes atendidos</td><td>0</td></tr>';
			cont += '<tr><td>N° total de pacientes no atendidos</td><td>0</td></tr>';
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
		$("#generalData").html(cont);

		//$("#supervision").html(cont);
		//createGraphic('hour');

	});
	waiting();
}


function waiting(){
	var total = maxWT+normalWT+minWT;
	//tiempo*100/max
	var max = maxWT*100/total;
	var min = minWT*100/total;
	var nor = normalWT*100/total;
	$('#waitingG').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Tiempos de espera'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    ['Menor a 10 minutos',   min],
                    ['Entre 10 y 29 minutos',       nor],
                    ['mayor a 30 minutos',    max],
                ]
            }]
        });
    
}

function moduleData (data) {

	var cont=''
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
	

	cont += '<table class="table table-bordered table-striped table-condensed">';
	cont += '<tr> <th>Modulo</th><th>Pacientes atendidos</th><th>Promedio maximo de atencion </th><th>Promedio minimo de atencion </th><th>Promedio de atencion </th>';
	var chain = [];
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
		chain[i] = [];
		chain[i][0]=onlyModules[i];
		chain[i][1]=served;
		chain[i][2]=parseInt(maxT);
		chain[i][3]=parseInt(minT);
		chain[i][4]=parseInt(aver);
		served = 0; maxT = 0; minT = 0; aver = 0;
		
	};
	
	for (var i = 0; i < chain.length; i++) {
		cont +='<tr>';
		for (var ii = 0; ii < 5; ii++) {
			if(ii<2){
				cont +="<td>"+chain[i][ii] +"</td>";	
			}else{
				cont +="<td>"+chain[i][ii] +" Segundos</td>";	
			}
		};
		cont +='</tr>';
	};
	cont +="</table>";
	$("#moduleData").html('<div class="row">'+cont+'</div>');

	
		//createGraphic('hour');
}




function subModuleData() {
	var data='';
	$.ajax({
		async:false, 
		url: 'modules/statistical/usersInfo.php',
		type: 'GET',
		data: {data: currentZone , date1:initialDate , date2:finishDate , data2:currentModule},
	})
	.done(function(e) {
		data = e;
	});
	var json = JSON.parse(data);
	var totalHours = '';
	$.ajax({
		url: 'modules/statistical/getStatiscal.php',
		type: 'GET',
		async : false,
		data: {data: currentZone,type:"pd", date1:initialDate , date2:finishDate , data2:currentModule},
	})
	.done(function(data) {
		totalHours = JSON.parse(data);
	})
	.fail(function() {
		console.log("error");
	});


	var TXT="",cols = 0,X=0,datas = ["Ejecutiva:","Pacientes atendidos","Promedio de atencion","Promedio Minimo de atencion","Promedio maximo de atencion","Tiempo de actividad","Productividad"];
	TXT += "<table class='table table-bordered table-striped table-condensed'>";
	TXT +="<tr>";
	for (var i = 0; i < datas.length; i++) {
		TXT +="<th>"+ datas[i] +"</th>";
	};
	TXT +="</tr>";


	for (var i = 0; i < json.length; i++) {
		TXT +="<tr><td>"+json[i].name +"</td><td>"+json[i].others.served_tickets +"</td><td>"+json[i].others.average +"</td><td>"+json[i].others.mintime +"</td><td>"+json[i].others.maxtime +"</td><td>"+ getPro(json[i].id,totalHours,false,1) +"</td><td>"+ getPro(json[i].id,totalHours,true,json[i].others.served_tickets)  +"</td></tr>"; 
	};
	
	
	TXT += "</table>";

	$("#sbData").html('<div class="row">'+TXT+'</div> ');
}
function getPro(user,totalHours,total,patients) {

	var lastLogin = '',lastClose = '';
    for (var l = 0; l < totalHours.length; l++) {
    	if(totalHours[l].users == user ){
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
		if(total){
			return  (patients/((diff % 86400000) / 3600000)).toFixed(2);;
		}
    	return  diffHrs+" Horas con "+diffMins+" minutos";
    }else{
    	if(total){
			return  0;
		}
    	return  "Sin actividad";
    }
}


function subModuleDataX(){
	$('#graphic1').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Total de pacientes segun horario'
        },
        xAxis: {
            categories: ['08:00 - 08:59','09:00 - 09:59','10:00 - 10:59','11:00 - 11:59','12:00 - 12:59','13:00 - 13:59','14:00 - 14:59','15:00 - 15:59','16:00 - 16:59','17:00 - 17:59','18:00 - 18:59','19:00+']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Numero de pacientes'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -70,
            verticalAlign: 'top',
            y: 20,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black, 0 0 3px black'
                    }
                }
            }
        }, 
    });


	var colors = ['#428BCA','#FAB700','#C94B42','#C9B742','#8C942'];
	var chart1 = $('#graphic1').highcharts();
	while(chart1.series.length > 0)
    chart1.series[0].remove(true);
	console.log(modulesInfo);
	for (var i = 0; i < modulesInfo.length; i++) {
		if(currentModule==0){
			$.ajax({
				url: 'modules/statistical/graphData.php',
				type: 'GET',
				async : false,
				data: {zone: currentZone, date1:initialDate , date2:finishDate , module:modulesInfo[i].id},
			})
			.done(function(e) {
				chart1.addSeries({name: modulesInfo[i].name ,data: JSON.parse(e),color: colors[i]});
			});
		}else{
			if(currentModule==modulesInfo[i].id ){
				$.ajax({
					url: 'modules/statistical/graphData.php',
					type: 'GET',
					async : false,
					data: {zone: currentZone, date1:initialDate , date2:finishDate , module:modulesInfo[i].id},
				})
				.done(function(e) {
					chart1.addSeries({name: modulesInfo[i].name ,data: JSON.parse(e),color: colors[0]});
				});
			}
		}

	};

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
	$('#reportrange span').html(moment().format('DD-MM-YYYY') + ' - ' + moment().add(1,'days').format('DD-MM-YYYY'));
	$('#reportrange').daterangepicker(optionSet1, cb);
	$('#reportrange').on('apply.daterangepicker', function(ev, picker) { 
		
		initialDate = picker.startDate.format('YYYY-MM-DD');         
		finishDate = picker.endDate.format('YYYY-MM-DD');
		showReport();
		 
	});

}

</script>
</html>


