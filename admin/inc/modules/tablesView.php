

<!DOCTYPE html>
<html>
<head>
	<title></title>

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../js/datatables2/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="../js/datatables2/css/dataTables.tableTools.css">
<link rel="stylesheet" type="text/css" href="../js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../js/bootstrap/css/dataTables.bootstrap.css">

<script src="../js/datatables2/js/jquery.js"></script>
<script src="../js/datatables2/js/jquery.dataTables.min.js"></script>
<script src="../js/datatables2/js/dataTables.tableTools.js"></script>
<script src="../js/datatables2/js/dataTables.bootstrap.js"></script>
<script src="../js/bootstrap/js/bootstrap.min.js"></script>
<?php


$idZone = $_REQUEST['idZone'];

//echo '<p>Vista Tablas, Zona:'.$idZone.'</p>';
	
?>


</head>
<body>

	<div class="container" style="width:90%">
		<div class="row">
			<div class="col-md-6">
		
				<div id="leftDiv">			
				</div>
			</div>
			<div class="col-md-6">

				<div id="rigthDiv">			
				</div>
				<br>
				<div id="waitingDiv">			
				</div>

			</div>
		</div>

		<div class="row">
			<div id="patientList">
				
			</div>
		</div>

	</div>

</body>

<script type="text/javascript">
	
$(document).ready(function() {
	var zoneId="<?php echo $idZone; ?>"
	//modulos y submodulos
	while(ajax1(zoneId,"mSm",1));
	//totem
	while(ajax1(zoneId,"tt",2));
	//waiting
	while(ajax1(zoneId,"wtg",3));
	
});

//Ajax list
function ajax1(zoneId,type,order){
	$.ajax({
		async:false, 
		url: '../services/getInfoTables.php',
		type: 'GET',
		data: {data: zoneId,type:type},
	})
	.done(function(e) {
		switch(order) {
	    	case 1:
	        fillTable(e);
	        break;

	    	case 2:
	        fillRtable(e);
	        break;

	        case 3:
	        fillWaiting(e);
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
	cont  = '<table class="table table-bordered table-striped "><tr><th></th><th>Espera</th><tr>';
	if(data != 0){
		cont += '<tr><td>Total de pacientes en espera: </td><td>'+ json.length +'</td></tr>';
		var date2 = new Date();
		var x=0,y=0,z=0;
		for (var i = 0; i < json.length; i++) {
			var date1 = new Date(json[0].datetime);
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
	$("#waitingDiv").html(cont);
	//console.log(json);
}


//fill right table (tothem and others)
function fillRtable (data) {
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
    cont  = '<table class="table table-bordered table-striped "><tr><th></th><th>Tothtem</th><tr>';
    if(data != 0){
    	cont += '<tr><td>Total Tickets:</td><td>'+json.length+'</td> </tr>';
	    for (var i = 0; i < onlyModules.length; i++) {
	    	cont += '<tr><td>Tickets '+onlyModules[i]+'</td><td>'+b[i]+'</td> </tr>';
	    };
    	cont += '<tr><td>Primer Ticket emitido:</td><td>'+json[0].datetime+'</td><tr><td>Ultimo Ticket emitido</td><td>'+json[json.length-1].datetime+'</td> </tr></table>';	
    }else{
    	cont += '<tr><td>Total Tickets:</td><td>0</td> </tr>';
    }
    $("#rigthDiv").html(cont);
}


//fill left table (modules)
function fillTable (data) {
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
	var totalPatient = 0;
	var tableText="",cols = 0,X=0,datas = ["Nombre ejecutiva:","Total Pacientes atendidos","Promedio de atencion","Minimo","Maximo"];
	for (var i = 0; i < onlyModules.length; i++) {
		for (var j = 0; j < json.length; j++) {
			if(onlyModules[i] == json[j].modulename){
				cols++;
			}
		};
		//console.log(cols);
		tableText += "<table class='table table-bordered table-striped '>";
		tableText += "<tr><td></td>"; 
		tableText += "<td colspan='"+(cols)+"' class='text-center'><b>"+ onlyModules[i] +"</b></td></tr>";
		tableText += "<tr><td></td>";
		for (var j = X; j < cols; j++) {
			if(json[j].submodulestate == "activo"){
				tableText += "<th class='text-center' bgcolor='green' style='color:white'>"+json[j].submodulename+"</th>";	
			}else{
				tableText += "<th class='text-center' bgcolor='red' style='color:white'>"+json[j].submodulename+"</th>";	
			}
		};
		
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
			        tableText += "<td class='text-center'>"+d.getSeconds()+"</td>";
			        break;
			        case 3:
			        var d = new Date(json[j].others.mintime);
			        tableText += "<td class='text-center'>"+d.getSeconds()+"</td>";
			        break;
			        case 4:
			        var d = new Date(json[j].others.maxtime);
					tableText += "<td class='text-center'>"+d.getSeconds()+"</td>";			       
			        break
				}

				//tableText += "<td class='text-center'>"+json[j].user+"</td>";
			};
			tableText +="</tr>";	
		};
		X=cols;
		tableText += "</tr>";

		//var totalHours = getPd(onlyModulesID[i],"pd");

		$.post('../services/getInfoTables.php',  {data: onlyModulesID[i],type:"pd"}, function(data, textStatus, xhr) {
			var totalHours = JSON.parse(data);
			
			var initialHour = totalHours[0].datetime;
			var finalHour = totalHours[totalHours.length-1].description;
			if(finalHour.indexOf("Cierre") !=-1){
				finalHour = new Date();
			}else{
				finalHour = totalHours[totalHours.length-1].datetime;
			}
			
			finalHour = new Date(finalHour);
			initialHour = new Date(initialHour);
			console.log(finalHour,initialHour);

		});


		
		tableText += "<tr><td align='right'>Productividad del modulo: </td><td colspan='"+cols+"'> </td></tr>"
		tableText+="</table><br>";
	};
	//console.log(json[1].others.maxtime);
	$("#leftDiv").html(tableText);

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

</script>


</html>