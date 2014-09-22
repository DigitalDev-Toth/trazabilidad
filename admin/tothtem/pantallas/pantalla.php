<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>Pantalla Pacientes</title>
	  	<script src="js/jquery-1.10.2.js"></script>
	    <script src="js/bootstrap.js"></script>
	    <script src="js/jquery.zrssfeed.js" type="text/javascript"></script>
	    <script src="js/jquery.vticker.js" type="text/javascript"></script>
	    <script src="js/jquery.zweatherfeed.js" type="text/javascript"></script>
	    <link href="css/bootstrap.css" rel="stylesheet">
	    <script src="js/cometDisplay.js"></script>
  	</head>
  <body>



<div class="panel panel-default">
  <div class="panel-heading">
    <h2 class="panel-title  text-center" id="zoneName" >FALP</h2>
  </div>
  <div class="panel-body">

  		<h2>
  	 	<div class="row text-center">
	  	  	<div class="col-md-3 well well-sm">
	  	  		Area
	  	  	</div>
	  	  	<div class="col-md-3 well well-sm">
	  	  		Modulo
	  	  	</div>
	  	  	 	<div class="col-md-3 well well-sm">
	  	  		Numero
	  	  	</div>
	  	  	<div class="col-md-3 well well-sm">
	  	  		Ultimos llamados
	  	  	</div>
  	 	</div>
      	<div id='content' class="text-center">
  		</div>
  		</h2>

  </div>
</div>




 <footer>
    <div class="row">
        <div class="col-lg-12">
            <p>Toth 2014 &copy;</p>
        </div>
    </div>
</footer>
<script type="text/javascript">



//****************************************
//document ready
//****************************************
var zone;
$(document).ready(function() {
	zone = decodeURIComponent("<?php echo rawurlencode($_GET['zone']); ?>");
	getZoneName(zone);
	initConfig(zone);
});

//****************************************
//Get zone name for header
//****************************************
function getZoneName(zone){
	$.post('phps/getZone.php', {idZone: zone} , function(data, textStatus, xhr) {
		var json = JSON.parse(data);
		$("#zoneName").html("FALP - "+json.zoneName);
	});
}

//****************************************
//initial config
//****************************************
function initConfig(zone){
	if(zone != ''){
		getActivesModules(zone);
		getLastTickets(zone);
		return true;
	}else{
		alert('falta id zone');
	}

}

//****************************************
//get last ticket
//****************************************
function getLastTickets(zone){
	$.post('phps/getModuleTicketsPatients.php', {zone: zone } , function(data, textStatus, xhr) {
		//console.log(data);
		var jsonData= JSON.parse(data);
		for (var i = 0; i < jsonData.length; i++) {
			changeNumber(jsonData[i] ,1);
		};
		
	});
}

//****************************************
//get 3 last tickes called
//****************************************
function lastTicketsCalled(module , lc){
	if(module != null){
		$.post('phps/lastTicketsCalled.php', { module: module } , function(data, textStatus, xhr) {
			var json = JSON.parse(data);
		
			
			$(lc).html('');
			var htmlC='';
			for (var i = 0; i < json.length; i++) {
				
				
				htmlC += '<div class="row">'+fixNumber(json[i].ticket)+'  '+ fixHours(json[i].datetime) +'</div>'
			};
			
			$(lc).html(htmlC);
		});
	}
	
}

//****************************************
//fix date to hours
//****************************************
function fixHours(date){
	var d = new Date(date);
	var hour = d.getHours() < 10 ? '0' + d.getHours() : d.getHours();
	var minutes = d.getMinutes() < 10 ? '0' + d.getMinutes() : d.getMinutes();
	return hour+":"+minutes;
}

//****************************************
// fill each row 
//****************************************
function fillModules(name,id){
	var content='';
	content += "<div class='row' id='RW"+id+"'> " +
					"<div class='col-md-3' style='padding-top: 55px;'>"+name+"</div>"+
						"<div class='col-md-3' style='padding-top: 55px;'>" +
							"<div class='row'>"+
								"<div id='SM"+id+"'>"+
							"</div></div>"+
						"</div>"+
						"<div class='col-md-3' > " +
							"<div class='row' >"+
								"<h1><div id='NM"+id+"' style='font-size: 90px;'></h1>"+
							"</div>"+

						"</div>"+

						"<div class='col-md-3'>" +
							"<div id='LC"+id+"'> </div>"+
						"</div>"+
			    "</div><hr>";
			    
	$('#content').append(content);

}


//****************************************
//Change the current number of module
//****************************************//****************************************
function changeNumber(data,type){
	if(data.module != null){
		var sm = '#SM'+data.module; //submodule name
		var nm = '#NM'+data.module; // number/ticket
		var lc = '#LC'+data.module; // last called
		var rw = '#RW'+data.module; //row id
		var modulename,ticket;
		lastTicketsCalled(data.module,lc);

		console.log(data);
		if(data.action != 'lb'){
			if(type==1){
				modulename=data['moduleName'];
				ticket=data['moduleTicket'];		
			}else{
				$.post('phps/getSubmoduleName.php', {sub_module: data.submodule} , function(dataR, textStatus, xhr) {
					modulename=dataR;
					ticket=data.newticket;
				});
			}	
			$(sm).fadeOut('fast', function() {
				$(sm).text(modulename);
				$(sm).fadeIn('fast');
			});
			$(nm).fadeOut('fast', function() {
				
				$(nm).text(fixNumber(ticket));
				$(nm).fadeIn('fast');
			});

			$(rw).css('background-color', 'rgb(121, 175, 245)');
			$(rw).css({
			    transition : 'background-color 1s ease-in-out',
			    "background-color": "rgb(121, 175, 245)'"
			});
			setTimeout(function() {
				$(rw).css('background-color', 'white');
				$(rw).css({
				    transition : 'background-color 2s ease-in-out',
				    "background-color": "white'"
				});
			}, 2000);
		}
		if(data.action == 'lb'){
			$(sm).fadeOut('fast', function() {
				$(sm).text('-');
				$(sm).fadeIn('fast');
			});
			$(nm).fadeOut('fast', function() {
				$(nm).text('-');
				$(nm).fadeIn('fast');
			});
		}
	
	}else{
		
		lastTicketsCalled(data.moduleId,('#LC'+data.moduleId));
	}
	

}

//****************************************
// fix the current o last tickets: 7B -> 007-B
//****************************************
function fixNumber(number){
	var letter = number.slice(-1);
	var onlyNumbers = number.substring(0, number.length - 1);
	if(onlyNumbers.length == 1){
		onlyNumbers = '00'+onlyNumbers;
	}
	if(onlyNumbers.length == 2){
		onlyNumbers = '0'+onlyNumbers;
	}
	return onlyNumbers+'-'+letter;
}

//****************************************
//get only actives modules of the zone
//****************************************
function getActivesModules(zone){
    var result = null;
    var scriptUrl = "phps/getModuleDisplay.php?zone=" + zone;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        },
        error: function(data) {
            console.log("error config");
        }
    });
    var jsonModules=JSON.parse(result);
	$("#content").html('');
    for (var i = 0; i < jsonModules.length; i++) {
        if(jsonModules[i]['type']!=12){        
        	fillModules(jsonModules[i]['name'],jsonModules[i]['id']);
        }else{
            var moduleId = jsonModules[i]['id'];
            $.post('phps/getActivesModulesSpecial.php', {module: moduleId}, function(data, textStatus, xhr) {
                if(data!='nan'){
                    var jsonData = JSON.parse(data);
                    for(j=0; j < jsonData.length;j++){
                        fillModules(jsonData[j]['name'],jsonData[j]['id']);
                    }
                }
            });
        }
    };
 
}


</script>

</body>
</html>