<?php

include 'libs/bootstrapStyle.php';

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>



</head>
<body>

<div class="row well">

	<div class="row text-center">
		<div class="col-md-3 col-md-offset-0">
			<p class="">Visualizacion</p>	
		</div>
		<div class="col-md-2 col-md-offset-6">
			<p class="">Zona</p>	
		</div>
	</div>
           
	<div class="row text-center">

		<div class="col-md-3 col-md-offset-0">
			<div class="btn-group">
			  <button type="button" class="btn btn-default" id="visorButton">Visor</button>
			  <button type="button" class="btn btn-default" id="tableButton">Tablas</button>
			</div>
		</div>
		<div class="col-md-2 col-md-offset-6">
			<h3 id="loading"><i class="fa fa-spinner fa-spin"></i></h3>
			<select class="form-control" id="selectorZone" style="display:none">
			</select>
		</div>
	</div>
</div>

<div class="row"> 
	<iframe src="" id="actualIframe" frameborder="0" allowfullscreen></iframe>
</div>




</body>

<script>

var switchZone = 0;
var currentZone = 1;

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




});



function changeZone (idZone,sw) {

	if(sw==0){
		idZone="../../visor/index.php?idZone="+idZone;
		$( document ).width();
		$('#actualIframe').height($( document ).height()*0.8);
		$('#actualIframe').width($( document ).width());
		$('#actualIframe').fadeOut('500', function() {
			$('#actualIframe').attr('src', idZone);	
			$('#actualIframe').fadeIn('500', function() {
				
			});
		});
	}else{
		idZone="modules/tablesView.php?idZone="+idZone;
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


