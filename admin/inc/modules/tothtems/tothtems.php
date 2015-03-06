<?php

include 'libs/bootstrapStyle.php';

?>
<script src="http://falp.biopacs.com:8000/socket.io/socket.io.js"></script>
<html>
	<head>
		<meta charset="utf-8">
		<title></title>
	</head>
	<body>
		   
	<div class="row text-center">
	    <div class="row text-center well well-sm ">
			<div class="col-md-2">
				<label style="margin-top: 8px;" class="pull-right"><span class="glyphicon glyphicon-th-list"></span> Configuración TothTem</label>
			</div>
		</div>
		<div class="container">
			<div class= "row">
				<div class="col-md-6 col-sm-6">
						<table class="table table-condensed table-bordered text-center">
							<thead>
								<tr>
									<th>N°</th><th>ID</th><th>Nombre</th><th>Hora inicio</th><th>Hora fin</th><th>Funcionamiento</th><th>Estado</th>
								</tr>
							</thead>
							<tbody id ="table">
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
	</body>

	<script type="text/javascript">
	var socket = io.connect('http://falp.biopacs.com:8000');
	$(document).ready(function() {
		$.ajax({
			url: '../inc/modules/tothtems/getTothtems.php',
			type: 'post'
		})
		.done(function(e) {
			var json = JSON.parse(e);
			showTothtems(json);
			changeHours(json);
		})
		.fail(function(e) {
			console.log(e);
		})
		.always(function() {
			console.log("complete");
		});
		
	});

	function showTothtems(json) {
		console.log(json);
		var html = '';
		if(json.length > 0){
			for (var i = 0; i < json.length; i++) {
				html += '<tr>';
				html += '<td>'+(i+1)+'</td>';
				html += '<td>'+json[i].ids +'</td>';
				html += '<td>'+json[i].name +'</td>';
				//html += '<td>'+json[i].start +'</td>';
				html += '<td>'+ returnTime(json[i].ids,json[i].hstart,'start')+'</td>';
				//html += '<td>'+json[i].end +'</td>';
				html += '<td>'+ returnTime(json[i].ids,json[i].hend,'end')+'</td>';
				html += '<td>'+ type(json[i].type,json[i].ids) +'</td>';
				html += '<td>'+ state(json[i].state,json[i].ids) +'</td>';
				html += '</tr>';
			};
			$("#table").html(html);
		}else{
			$("#table").html('Sin datos');
		}
	}
	function type (currentType, id) {
		var  html = '<select class="form-control" id="t'+id+'" onchange="setType('+id+')">';
		if(currentType == 'f'){
				html += '<option value="0">Programado</option>';
				html += '<option value="1" selected>Manual</option>';
		}else{
				html += '<option value="0" selected>Programado</option>';
				html += '<option value="1">Manual</option>';
		}
			html += '</select>';
		return html;
	}
	function state (currentType, id) {
		var  html = '<select class="form-control" id="s'+id+'" onchange="setState('+id+')">';
		if(currentType == 'f'){
				html += '<option value="1">ACTIVO</option>';
				html += '<option value="0" selected>INACTIVO</option>';
		}else{
				html += '<option value="1" selected>ACTIVO</option>';
				html += '<option value="0">INACTIVO</option>';
		}
			html += '</select>';
		return html;
	}
	function setState (id) {
		var state = $("#s"+id).val();
		if(	state == 1){
			state = true;
		}else{
			state = false;
		}
		$.post('../inc/modules/tothtems/saveChanges.php', {id:id,action:'state',state:state}, function(result){
		   console.log(result);
		   sendMessage(id,state);
		})
	}

	function setType (id) {
		var currenType = $("#t"+id).val();
		//0 programado
		if(currenType == 0){
			var start = $("#hstart"+id).val()+":"+$("#mstart"+id).val();
			var end   = $("#hend"+id).val()+":"+$("#mend"+id).val();
			$.post('../inc/modules/tothtems/saveChanges.php', {id:id,action:'function',state:true,end:end,start:start}, function(result){
		   		console.log(result);
			})
		}else{
			$.post('../inc/modules/tothtems/saveChanges.php', {id:id,action:'function',state:false}, function(result){
		   		console.log(result);
			})
		}
	}

	function returnTime(id, hour, type) {
		var html = '<select id="h'+type+id+'"  onchange="changeActualFunc('+id+')">';
		for (var i = 0; i < 24; i++) {
			if(i<10) i = "0"+i;
			html += '<option value="'+i+'">'+i+'</option>';
		};
		html += '</select>';
		html += ':';
		html += '<select id="m'+type+id+'" onchange="changeActualFunc('+id+')">';

		for (var i = 0; i < 60; i= parseInt(i)+5) {
			if(i<10) i = "0"+i;
			html += '<option value="'+i+'" >'+i+'</option>';
		};
		
		html += '</select>';


		return html;
	}
	function changeActualFunc (id) {
		$("#t"+id).val('1');
	}
	function changeHours (json) {
		for (var i = 0; i < json.length; i++) {
			var hourStart = json[i].hstart.split(":");
			var hourEnd = json[i].hend.split(":");
			$("#hstart"+json[i].ids).val(hourStart[0]);
			$("#mstart"+json[i].ids).val(hourStart[1]);
			$("#hend"+json[i].ids).val(hourEnd[0]);
			$("#mend"+json[i].ids).val(hourEnd[1]);
		};
	}

	function sendMessage(id,state){
		var message = '{"comet": "tothemAction", "id": "'+id+'" , "state":"'+state+'"}';
		socket.send(message);
	}
	</script>

</html>