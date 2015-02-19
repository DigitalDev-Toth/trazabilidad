

<!DOCTYPE html>
<html>
<head>
	<title></title>

<link rel="stylesheet" type="text/css" href="../../js/bootstrap/css/bootstrap.css">

<script src="../../js/datatables2/js/jquery.js"></script>
<script src="../../js/bootstrap/js/bootstrap.min.js"></script>

<?php


$idZone = $_REQUEST['idZone'];

//echo '<p>Vista Tablas, Zona:'.$idZone.'</p>';
	
?>


</head>
<body>

	<div class="container" style="width:90%">
		<div class="row">
			<div class="col-md-3 col-sm-3">
				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span> Modulo</div>
					<select class="form-control" id="selectorModule">
					</select>
				</div>
			</div>
			
		</div>
		<br>
		<div class="row">
			<div class="col-md-6">

				<div id="tableModule"></div>
				<div id="totemTable"></div>
				<div id="waitingTable"></div>

			</div>
			<div class="col-md-6">
				<div id="patientList"></div>
				<div id="patientOnServer"></div>
				
			</div>
		</div>


			
	

	</div>

</body>
<script src="http://falp.biopacs.com:8000/socket.io/socket.io.js"></script>
<script src="../../js/bitacora.js"></script>
<script type="text/javascript">
var socket = io.connect('http://falp.biopacs.com:8000');  
 

socket.on('connect', function() {
        socket.on('message', function(message) {
       	var json = JSON.parse(message);
       	Start();
    });
});


	
var currentModule='';	
var zoneId="<?php echo $idZone; ?>"
$(document).ready(function() {
	
	
	//modulos y submodulos
	//while(ajax1(zoneId,"mSm",1));
	//totem
	//while(ajax1(zoneId,"tt",2));
	//waiting
	//while(ajax1(zoneId,"wtg",3));
		fillSelector(fill);
	Start();

	
});

function Start(){

	if(currentModule != undefined){
		ajax1(zoneId,"mSm",1,currentModule);
		ajax1(zoneId,"tt",2,currentModule);
		ajax1(zoneId,"wtg",3,currentModule);
		ajax1(zoneId,"patient",4,currentModule);
		ajax1(zoneId,"os",5,currentModule);
	}
}

function fill(e){
	currentModule=e;
}

function fillSelector(callback){
	$.ajax({
		async:false, 
		url: 'infoModules.php',
		type: 'GET',
		data: {data: zoneId,type:'modules'},
	})
	.done(function(e) {
		if(e != 0){
			var data = JSON.parse(e);
			var sel = $("#selectorModule");
			currentModule = data[0].id;
		    sel.empty();
		    for (var i=0; i<data.length; i++) {
		      sel.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
		    }
		    
		}
 		callback(data[0].id);
	});

}

//Ajax list
function ajax1(zoneId,type,order,module){
	$.ajax({
		async:false, 
		url: 'infoModules.php',
		type: 'GET',
		data: {data: zoneId,type:type,module:module},
	})
	.done(function(e) {
		switch(order) {
	    	case 1:
	        fillTable(e);
	        break;
	    	case 2:
	        totemTable(e);
	        break;
	        case 3:
	        fillWaiting(e);
	        break;
	        case 4:
	        fillPatienW(e);
	        break;
	       	case 5:
	        fillPatienOS(e);
	        break;
		}
		return false;	
	})
	.fail(function() {
		return true;
	})
	
}

//fill waiting table (right div under tothem table)

function fillWaiting(data){
	var json = JSON.parse(data);	
	var cont = '';
	cont  = '<table class="table table-bordered table-striped table-condensed"><tr><th colspan="2" class="text-center">Tiempos y pacientes en espera</th></tr>';
	if(data != 0){
		cont += '<tr><td>Cantidad de pacientes en espera: </td><td>'+ json.length +'</td></tr>';

		var date2 = new Date();
		var x=0,y=0,z=0;
		for (var i = 0; i < json.length; i++) {
			var date1 = new Date(json[i].datetime);
			
			var minutes = Math.round(Math.abs(date1.getTime() - date2.getTime()) / 60000);
			if(minutes>=0 && minutes<=10){
				x++;
			}
			if(minutes>10 && minutes<=20){
				y++
			}
			if(minutes>20){
				z++
			}
		};
		cont += '<tr><td>Pacientes con espera menor a 10 </td><td>'+x+'</td></tr>';
		cont += '<tr><td>Pacientes con espera entre 10 y 20 minutos </td><td>'+y+'</td></tr>';
		cont += '<tr><td>Pacientes con espera mayor a 20 minutos </td><td>'+z+'</td></tr>';
	}else{
		cont += '<tr><td>Total de pacientes en espera: </td><td>0</td></tr>';
	}
	cont +="</table>";
	$("#waitingTable").html(cont);
	//console.log(json);
}


function fillPatienOS(data){
	var json  = JSON.parse(data);
	var cont  ='<table class="table table-bordered table-striped table-condensed">';
		cont +='<tr><th colspan="6" class="text-center">Pacientes en Atencion</th></tr>';
		cont +='<tr><th>N°</th><th>RUT</th><th>Hora llegada</th><th>Ticket</th><th>Bitacora</th></tr>';

		if(json.length != undefined){
			for (var i = 0; i < json.length; i++) {
				cont += '<tr><td>'+ (i+1) +'</td><td>'+ json[i].rut +'</td><td>'+ json[i].datetime.split(" ")[1] +'</td> <td>'+ json[i].ticket +'</td><td> <button type="button" class="btn" onclick="parent.parent.parent.showBitacora(\''  +json[i].rut+'\')"><span class="glyphicon glyphicon-list-alt"></span></button>  </td></tr>'
			};
		}else{
			cont +='<tr><th colspan="6" class="text-center">Sin pacientes en espera</th></tr>';
		}
		

	cont +='</table>';
	$("#patientOnServer").html(cont);
}

function remainTime(time){
	var T = new Date(time);
	var Ihour = T.getHours();
	var Iminutes = T.getMinutes();
	var remainminutes; 
	$.ajax({
		url: '../../../../services/getServerTime.php',
		type: 'POST',
		async:false
	})
	.done(function(e) {
		var today = new Date(e);
		var Fhour = today.getHours();
		var Fminutes = today.getMinutes();
		var remainHours = (parseInt(Fhour)-parseInt(Ihour))*60;
		remainminutes = (parseInt(Fminutes)-parseInt(Iminutes))+remainHours;
		if(remainminutes<0){
			remainminutes=0;
		}
	});
	return remainminutes+' Minutos';
}



function fillPatienW(data){
	var json  = JSON.parse(data);
	var cont  ='<table class="table table-bordered table-striped table-condensed">';
		cont +='<tr><th colspan="6" class="text-center">Pacientes en espera</th></tr>';
		cont +='<tr><th>N°</th><th>RUT</th><th>Hora llegada</th><th>Tiempo espera</th><th>Ticket</th><th>Bitacora</th></tr>';
	
		if(json.length != undefined){
			for (var i = 0; i < json.length; i++) {
				cont += '<tr><td>'+ (i+1) +'</td><td>'+ json[i].rut +'</td><td>'+ json[i].datetime.split(" ")[1] +'</td> <td>'+ remainTime(json[i].datetime) +'</td> <td>'+ json[i].ticket +'</td><td> <button type="button" class="btn" onclick="parent.parent.parent.showBitacora(\''  +json[i].rut+'\')"><span class="glyphicon glyphicon-list-alt"></span></button>  </td></tr>'
			};
		}else{
			cont +='<tr><th colspan="6" class="text-center">Sin pacientes en espera</th></tr>';
		}
		

	cont +='</table>';
	$("#patientList").html(cont);
}


function totemTable (data) {
	var json = JSON.parse(data);
	var cont = '';
	var modules = [];
	for (var i = 0; i < json.length; i++) {
		modules.push(json[i].name);	
	};
	var onlyModules = [];
	$.each(modules, function(i, elements){
	    if($.inArray(elements, onlyModules) === -1) onlyModules.push(elements);
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
    onlyModules.sort();

    cont  = '<table class="table table-bordered table-striped table-condensed"><tr><th colspan="2" class="text-center">Tothtem</th><tr>';
    if(data != 0){
    	cont += '<tr><th align="right">Total tickets retirados:</th><th>'+json.length+'</th> </tr>';
    	cont += '<tr><td>Hora del primer ticket retirado:</td><td>'+json[0].datetime+'</td><tr><td>Hora del ultimo ticket retirado</td><td>'+json[json.length-1].datetime+'</td> </tr></table>';
    }else{
    	cont += '<tr><td>Total Tickets:</td><td>0</td> </tr>';
    }
    $("#totemTable").html(cont);
}


//fill left table (modules)
function fillTable (data) {
	var json = JSON.parse(data);
	var totalPatient = 0;
	var tableText="",cols = 0,datas = ["Nombre ejecutiva:","Cantidad de Pacientes atendidos","Promedio de atencion","Minimo","Maximo"];
	cols=json.length;
	tableText += "<table class='table table-bordered table-striped table-condensed'>";
	tableText += "<tr><td></td>"; 
	tableText += "<td colspan='"+(cols)+"' class='text-center'><b>"+ json[0].modulename +"</b></td></tr>";
	tableText += "<tr><td></td>";
	for (var j = 0; j < cols; j++) {
		if(json[j].submodulestate == "activo"){
			tableText += "<th class='text-center' bgcolor='green' style='color:white'>"+json[j].submodulename+"</th>";	
		}else{
			tableText += "<th class='text-center' bgcolor='red' style='color:white'>"+json[j].submodulename+"</th>";	
		}
	};
	
	for (var k = 0; k < datas.length ; k++) {
		tableText +="<tr><td align='right'>"+datas[k]+"</td>";
		for (var j = 0; j < cols; j++) {
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

		        break
			}

			//tableText += "<td class='text-center'>"+json[j].user+"</td>";
		};
		tableText +="</tr>";	
	};

	tableText += "</tr>";

		//var totalHours = getPd(onlyModulesID[i],"pd");

		//productividad
		
		$.post('infoModules.php',  {data: currentModule,type:"pd"}, function(data, textStatus, xhr) {
			var totalHours = JSON.parse(data);
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



		});

	


		
		//tableText += "<tr><td align='right'>Productividad del modulo: </td><td colspan='"+cols+"'> </td></tr>"
		tableText += "<tr><td align='right'>Atendiendo a: </td>";
		for (var i = 0; i < cols; i++) {
			tableText += '<td class="text-center">-</td>';
		};
		tableText+='</tr>';



		tableText+="</table><br>";
	
	//console.log(json[1].others.maxtime);
	$("#tableModule").html(tableText);

	


}


function getPd (module,type) {
	$.ajax({
		sync:true, 
		url: '../services/getInfoTables.php',
		type: 'GET',
		data: {data: module,type:type},
	})
	.done(function(e) {
		return JSON.parse(e);
	})
	.fail(function() {
		return false;
	})
	
	
}


$("#selectorModule").change(function(event) {
	currentModule=this.value;
	Start();
});


</script>


</html>