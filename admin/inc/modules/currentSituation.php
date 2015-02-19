<?php

include 'libs/bootstrapStyle.php';

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>

<style type="text/css">
	
.popover {
	min-width: 650px ! important;
	max-width: 800px;
	width: auto;
}
</style>

</head>
<body>

   
	<div class="row text-center">


	    <div class="row text-center well well-sm ">
			<div class="col-md-2">
				<label style="margin-top: 8px;" class="pull-right"><span class="glyphicon glyphicon-th-list"></span> Situacion Actual</label>
			</div>

			<div class="col-md-3">
				<div class="btn-group">
			 		<button type="button" class="btn btn-default" id="visorButton">Visualizacion</button>
					<button type="button" class="btn btn-default" id="tableButton">Resumen</button>

				</div>
			</div>


			<div class="col-md-1">
				<label style="margin-top: 8px;" class="pull-right">Zonas: </label>	
			</div>

			<div class="col-md-2">
				<h4 id="loading"><i class="fa fa-spinner fa-spin"></i></h4>
				<select class="form-control" id="selectorZone" style="display:none">
				</select>
			</div>

			<div class="col-md-1 ">

		

			    <button id="popoverData" class="btn" href="#" data-content="" rel="popover" data-placement="bottom" data-original-title="Leyenda" data-trigger="hover" data-html="true"  disable><span class="glyphicon glyphicon-info-sign"></span> Leyenda</button>
			</div>

			

		</div>


	



		
	
	</div>


<div class="text-center"> 

	<iframe src="" id="actualIframe" frameborder="0"  allowfullscreen></iframe>
</div>




</body>

<script>

var switchZone = 0;
var currentZone = 1;
var popotito='';
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
	    changeZone(currentZone,switchZone);
	})
	.fail(function() {
		window.setTimeout('location.reload()', 100);

	})
	.always(function() {
		console.log("complete");
	});

	


    $('#popoverData').popover({
   		title: 'Leyenda',
    	content: '' ,
  	});
		




  


});



function changeZone (idZone,sw) {
	popotito='';
	if(switchZone == 0){
		popotito +=	'<p><span class="glyphicon glyphicon-pushpin" ></span><b> Posición de la ficha:</b> Lugar actual del paciente</p><hr>';
		popotito +=  '<p><span class="glyphicon glyphicon-map-marker" style="color:red"></span><b> Ficha Roja:</b> Paciente lleva esperando Sobre el limite de la sala</p>';
		popotito +=	'<p><span class="glyphicon glyphicon-map-marker" style="color:yellow"></span><b> Ficha Amarilla:</b> Paciente lleva esperando la mitad del limite de espera de la sala</p>';
		popotito +=	'<p><span class="glyphicon glyphicon-map-marker" style="color:green"></span><b> Ficha Verde:</b> Paciente en espera </p><hr>';
	}
	popotito +=	'<p><span class="glyphicon glyphicon-book" style="color:red"></span><b> Sub Modulo Rojo:</b> No activo </p>';
	popotito +=	'<p><span class="glyphicon glyphicon-book" style="color:yellow"></span><b> Sub Modulo Amarillo:</b> En pausa</p>';
	popotito +=	'<p><span class="glyphicon glyphicon-book" style="color:green"></span><b> Sub Modulo Verde:</b> Activo</p><hr>';
    $("#popoverData").attr('data-content', popotito);
	if(sw==0){
		idZone="../../visor/index.php?idZone="+idZone;
		$( document ).width();
		$('#actualIframe').height($( document ).height()*0.8);
		$('#actualIframe').width($( window ).width());
		$('#actualIframe').fadeOut('500', function() {
			$('#actualIframe').attr('src', idZone);	
			$('#actualIframe').fadeIn('500', function() {
				
			});
		});
	}else{
		idZone="modules/currentSituation/sumary.php?idZone="+idZone;
		$( document ).width();
		$('#actualIframe').height($( document ).height()*0.8);
		$('#actualIframe').width($( document ).width());
		$('#actualIframe').fadeOut('500', function() {
			$('#actualIframe').attr('src', idZone);	
			$('#actualIframe').fadeIn('500', function() {
				
			});
		});
	}

	
}


//change current zone
$("#selectorZone").change(function() {
	currentZone=this.value;
	changeZone(this.value,switchZone);
});

$("#visorButton").click(function(event) {
	switchZone=0;
	changeZone(currentZone,switchZone);
});
$("#tableButton").click(function(event) {
	switchZone=1;
	changeZone(currentZone,switchZone);
});



</script>
</html>


