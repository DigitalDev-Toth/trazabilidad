

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

echo '<p>Vista Tablas, Zona:'.$idZone.'</p>';
	
?>


</head>
<body>

	<div class="container" style="width:90%">
		<div class="row">
			<div class="col-md-6">
				<h3 class="text-center">Modulos</h3>
				<div id="leftDiv">			
				</div>
			</div>
			<div class="col-md-6">
			<h3 class="text-center">Otros</h3>
				<div id="rigthDiv">			
				</div>
			</div>
		</div>
	</div>

</body>

<script type="text/javascript">
	
$(document).ready(function() {

	var zoneId="<?php echo $idZone; ?>"

	$.ajax({
		url: '../services/getInfoTables.php',
		type: 'GET',
		data: {data: zoneId,type:'mSm'},
	})
	.done(function(e) {
		fillTable(e);
	})
	.fail(function(e) {
		console.log(e);
	});

	$.ajax({
		url: '../services/getInfoTables.php',
		type: 'GET',
		data: {data: zoneId,type:'tt'},
	})
	.done(function(e) {
		fillRtable(e);
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	

	
});


//fill right table (tothem and others)
function fillRtable (data) {
	var json = JSON.parse(data);
	var cont = '';
	var modules = [];
	console.log("tickets->"+json.length);

	for (var i = 0; i < json.length; i++) {
		modules.push(json[i].name);	
	};

	var onlyModules = [];
	$.each(modules, function(i, el){
	    if($.inArray(el, onlyModules) === -1) onlyModules.push(el);
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
    cont += '<tr><td>Total Tickets:</td><td>'+json.length+'</td> </tr>';

    for (var i = 0; i < onlyModules.length; i++) {
    	cont += '<tr><td>Tickets '+onlyModules[i]+'</td><td>'+b[i]+'</td> </tr>';
    	
    };
    	cont += '<tr><td>Primer Ticket emitido:</td><td>'+json[0].datetime+'</td><tr><td>Ultimo Ticket emitido</td><td>'+json[json.length-1].datetime+'</td> </tr></table>';
    	
    		
    $("#rigthDiv").html(cont);





}


//fill left table (modules)
function fillTable (data) {
	var modules = [];
	var json = JSON.parse(data);
	for (var i = 0; i < json.length; i++) {
		modules.push(json[i].modulename);	
	};

	var onlyModules = [];
	$.each(modules, function(i, el){
	    if($.inArray(el, onlyModules) === -1) onlyModules.push(el);
	});


	var tableText="";

	var cols = 0;

	var X=0;
	var datas = ["Nombre ejecutiva activa","Total Pacientes atendidos","Promedio de atencion","Minimo","Maximo"];
	for (var i = 0; i < onlyModules.length; i++) {
		
		for (var j = 0; j < json.length; j++) {
			if(onlyModules[i] == json[j] .modulename){
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
				tableText += "<td class='text-center'>ss</td>";
			};
			tableText +="</tr>";	
		};
		

		
		X=cols;
		tableText += "</tr>";
		tableText+="</table><br>";

	};
	


	$("#leftDiv").html(tableText);



}



</script>


</html>