<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php"); header('Content-Type: text/html; charset=utf8');  }
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pantalla</title>
  	<script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/comet.js"></script>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/2-col-portfolio.css" rel="stylesheet">
  
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <h1 class="page-header">Pantalla:
                    <small id="modalityTitle">...</small>
                </h1>
            </div>
            <div class="col-lg-3">
                <button class="btn btn-primary getout" onclick="inactiveSubModule('change')">CAMBIAR SUBMÓDULO</button>  
                <button class="btn btn-default getout" onclick="inactiveSubModule('logout')">SALIR</button>  
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
           		 <div class="panel panel-default">
                    <div class="panel-heading">Panel De Control Pacientes en curso</div>
                    <div class="panel-body" align="center">
                        <div class="row" >
                            <label>Numero</label><br>
                            <label id="content" style="font-size:70px;"></label>
                            <div class="row">
                            	<div id="buttons" class="text-center">
                            		<div class="col-md-1"></div>

                            		<div class="col-md-1">
                            			<button type="button" class="btn btn-default btn-lg" onclick="sendComet('minus')" id="minusButton" title="Volver a llamar número anterior"><span class="glyphicon glyphicon-minus"></span></button>
                            		</div>
                            		<div class="col-md-2">
	                               		<button type="button" class="btn btn-default btn-lg" onclick="sendComet('plus')" id="plusButton" title="Llamar paciente"><span class="glyphicon glyphicon-plus"></span></button>
                            			
                            		</div>
                            		<!--<div class="col-md-1">
	                                	<button type="button" class="btn btn-default btn-lg" onclick="sendComet('isHere')" id="isHereButton" title="Inicio de Atención"><span class="glyphicon glyphicon-play"></span></button>
                            			
                            		</div>-->
                            		<div class="col-md-2">
	                                	<button type="button" class="btn btn-default btn-lg" onclick="sendComet('notHere')" id="notHereButton" title="No llegó paciente" style="color: red;"><span class="glyphicon glyphicon-remove-circle"></span></button>
                            			
                            		</div>
                            		<div class="col-md-1">
	                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet('finished')" id="finishedButton" title="Finalizar Atención"><span class="glyphicon glyphicon-thumbs-up"></span></button>
                            			
                            		</div>
                            			<div class="col-md-2">
	                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet('redirect')" id="redirectButton" title="Derivar"><span class="glyphicon glyphicon-share"></span></button>
                            			
                            		</div>
                           		                            		                            		                            		

                            	</div>
                            </div>
                        
                        </div>

                        <!-- MODAL no atendidos-->
						<div id="modalNoServe" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="row">
										<h4 class="text-center">Pacientes No Atendidos...<h4>
									</div>
									<table id="modalNoServeContent" class="table table-striped text-center">
									</table>
								</div>
							</div>
						</div>

						<!-- MODAL Derivación-->
						<div id="modalDerived" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<h4 class="text-center">Módulo a Derivar...<h4>
									<div id="menuButtons">
					                    
				                	</div>
									<div class="row">
										
									</div>
									<table id="modalNoServeContent" class="table table-striped text-center">
									</table>
								</div>
							</div>
						</div>
						
                    </div>
                    <table id="contentTicket" class="table table-striped" >
                        <th>Numero de atencion</th><th>RUT</th><th>Hora Retiro de Ticket</th>
                    </table>
                </div>
            </div>
        </div>


        <div class="row">
        	<div class="col-md-12">
    			<div class="panel panel-default">
                	<div class="panel-heading">Paciente</div>
                    <div class="panel-body" align="center">
                        <div class="col-lg-10 col-md-10" >
                       		Datos del paciente...
                   	 	</div>
                	</div>
    			</div>
        	</div>
        </div>

        <hr>
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Toth 2014 &copy;</p>
                </div>
            </div>
        </footer>
    </div>
<script type="text/javascript">

var submodule="";
var moduleInCourse='';
var currentModule=0;
var currentNumber='';
var initNumber; //Primer número de Ticket por orden numérico
var firstTicketId = '';
var firstTicketIdDerived = '';
var ticketAttention = 0; //Indica qué número se está atendiendo (id del ticket)
var myState = false; //Indica si el submódulo está atendiendo y/o llamando


//se extrae la modalidad , el ultimo numero y se rellena la tabla
$(document).ready(function() {
    submodule=decodeURIComponent("<?php echo rawurlencode($_GET['id']); ?>");
    if(submodule != ""){
        var dataModality = JSON.parse(getModule(submodule));
        moduleInCourse=dataModality.modalityId;
        $("#modalityTitle").text(dataModality['modalityName']);
        initNumber = dataModality['modalityTicket'];
        refreshTable();
        //refreshTableDerived();
        setCurrentNumber();
    }else{
        alert("Falta Modalidad!");
    }
$(window).on('beforeunload', function(){ alert ('Bye now')});
});


function inactiveSubModule(typeButton){//Desactiva el submódulo y genera log de cierre de sesión
    $.post('phps/activeSubModule.php', {type: 'inactivo', user: "<?php echo $_SESSION['UserId']; ?>", submodule: submodule}, function(data, textStatus, xhr) {
        if(typeButton=='logout'){
            $(location).attr('href','../../exit.php');
        }else{
            $(location).attr('href','selector.php');
        }
    });
}

function setCurrentNumber(){//Muestra el número actual que se está atendiendo
	$.post('phps/getCallTicket.php', {submodule: submodule}, function(data, textStatus, xhr) {
		if(data!=0){
			var jsonData = JSON.parse(data);
			var number = jsonData[0].ticket;

			if(number<10){
		    	number='00'+number;
		    }
		    if(number>=10 && number<100){
		      	number='0'+number;
		    }
		    $('#content').fadeOut("slow",function(){
		        $('#content').fadeIn("fast");
	            $('#content').text(number);
		    });
		    activeButtons(jsonData[0].attention);
		    ticketAttention = jsonData[0].ticketid;
		}else{
			$('#content').text('Standby');
		}
    });
}

function getModule(idSubModule){//Obtiene el ID del submódulo actual
    var result = null;
    var scriptUrl = "phps/getModule.php?idSubModule=" + idSubModule;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
    });
    return result;
}

function insertLog(description,action,cometType,attentionNew,ticketId,module){//Inserción de log y escritura de comet
	var totalResult = getTicketData(ticketId);
	if(totalResult!=0){
		var jsonData = JSON.parse(totalResult);
		$.post('services/insertLogs.php', {rut: jsonData[0].rut,description:description,action:action,subModule:submodule,cometType:cometType,attentionNew:attentionNew,ticketId:ticketId,module:module} , function(data, textStatus, xhr) {
		    checkComet(data);
		});
	}
}

function checkComet(data){
  $.ajax({
        url: '../../../visor/comet/backend.php',
        type: 'GET',
        dataType: 'default',
        data: {msg: data},
    })
    .done(function(e) {
        console.log(e);
    })
    .fail(function(e) {
        console.log(e);
    })
    .always(function() {
           setCurrentNumber();
    });
}



// original
/*function insertLog(description,action,cometType,attentionNew,ticketId,module){//Inserción de log y escritura de comet
    var totalResult = getTicketData(ticketId);
    if(totalResult!=0){
        var jsonData = JSON.parse(totalResult);
        $.post('services/insertLogs.php', {rut: jsonData[0].rut,description:description,action:action,subModule:submodule,cometType:cometType,attentionNew:attentionNew,ticketId:ticketId,module:module} , function(data, textStatus, xhr) {
            $.post('../../../visor/comet/backend.php',{msg: data},function(data, textStatus, xhr){
                console.debug("backend->"+data);
                console.debug("textStatus->"+textStatus);
                setCurrentNumber();
                //HISTORIAL DE TICKETS ATENDIDOS EN FOOTER
            });
        });
    }
}*/




/*
	first state : waiting

	minus : previous 
	plus  : call
	play  : on_serve
	stop  : not_serve
	like  : served
	arrow : derived 

*/

function sendComet(type){//Genera la acción de los distintos botones a través de un alias

	if(type==='minus'){
		getNoServeTickets();
	}
	/*if(type==='plus'){
		myState = true;
		activeButtons('call');
		ticketAttention = firstTicketId;
		insertLog('Siguiente Ticket','cl','module','call',ticketAttention);
	}*/
	if(type==='isHere' || type==='plus'){
		myState = true;
		activeButtons('on_serve');
		ticketAttention = firstTicketId;
		insertLog('Ticket ha venido','in','module','on_serve',ticketAttention);
	}
	if(type==='notHere'){
		myState = false;
		insertLog('Ticket Ausente','lb','module','no_serve',ticketAttention);
		$('#content').css('color','black');
	}
	if(type==='finished'){
		myState = false;
		//activeButtons('call');
		insertLog('Ticket Finalizado','lb','module','served',ticketAttention);
		$('#content').css('color','black');
	}
	if(type==='redirect'){
		getActivesModules();
		$('#modalDerived').modal('show');
	}
	if(type==='exception'){
		$('#modalException').modal('show');
	}

	if(type==='plusDerived'){
		myState = true;
		activeButtons('on_serve');
		ticketAttention = firstTicketIdDerived;
		insertLog('Ticket ha venido','in','module','on_serve',ticketAttention);
		//insertLog('Siguiente Ticket','cl','module','call',ticketAttention);
		$('#content').css('color','red');
	}
}

function derive(moduleTo){//Deriva el ticket al módulo seleccionado
	myState = false;
	//activeButtons('call');
	insertLog('Ticket Derivado','to','module','derived',ticketAttention,moduleTo);//En este caso, insertlog recibirá el módulo al que se deriva
	$('#modalDerived').modal('hide');
	$('#content').css('color','black');
    refreshTable();
}


function refreshTable(){ //Actualiza la tabla de pacientes en espera
    var totalResult=getLast5Tickets(submodule,initNumber);
    if(totalResult==0){
        $('#contentTicket tr').has('td').remove();
        $('#contentTicket').append('<tr><td>No hay pacientes en espera...</td></tr>');
        if(myState==false){
        	activeButtons('onload');
        }
    }else{
        var ticketsTable = JSON.parse(totalResult);
        firstTicketId = ticketsTable[0]['ticketid'];
        var cant=Object.keys(ticketsTable).length;
        $('#contentTicket').fadeOut('slow', function() {
            $('#contentTicket tr').has('td').remove();
                for (var i=0;i<cant;i++) {
                    if(i==0){

                        $('#contentTicket').append('<tr class="info"><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td></tr>');
                    }else{
                        $('#contentTicket').append('<tr><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td>  </tr></tr>');  
                    }
                }
            $('#contentTicket').fadeIn('slow');
            if(myState==false){
            	activeButtons('next');
            }
        });
    }
}

function getLast5Tickets(idModule,last){//Devuelve los últimos 5 pacientes en espera
	if(last==null) last=0;
    var result = null;
    var scriptUrl = "phps/lastTickets.php?submodule="+idModule+"&last="+last;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
    });
    return result;
}

function getNoServeTickets(){//Devuelve y muestra los últimos 10 tickets que no fueron atendidos
    var scriptUrl = "phps/noServeLastTickets.php?submodule="+submodule+"&type=no_serve";
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            $('#modalNoServeContent').html('');
            if(data!=0){
            	var jsonData = JSON.parse(data);
            	$('#modalNoServeContent').append('<tr><th>Ticket</th><th>Paciente</th><th>Tiempo</th><th></th></tr>');
            	for(i=0;i<jsonData.length;i++){
            		$('#modalNoServeContent').append('<tr><td>'+jsonData[i]['ticket']+'</td><td>'+jsonData[i]['rut']+'</td><td>'+jsonData[i]['datetime']+'</td><td><button type="button" class="btn btn-primary" onclick="firstTicketId='+jsonData[i]['ticketid']+'; sendComet(&quot;plus&quot;); $(&quot;#modalNoServe&quot;).modal(&quot;hide&quot;);">Usar</button></td></div>');
            	}
            }else{
            	$('#modalNoServeContent').append('<tr ><th class="text-center">No hay pacientes </th></tr>');
            }

    
            $('#modalNoServe').modal('show');
        } 
    });
}

function getExceptions(){//Obtiene el total de los pacientes en espera del módulo
    var scriptUrl = "phps/noServeLastTickets.php?submodule="+submodule+"&type=exception";
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            $('#modalNoServeContent').html('');
            var jsonData = JSON.parse(data);

            if(jsonData.length!=0){
            	$('#modalNoServeContent').append('<tr><th>Ticket</th><th>Paciente</th><th>Tiempo</th><th></th></tr>');
            	for(i=0;i<jsonData.length;i++){
            		$('#modalNoServeContent').append('<tr><td>'+jsonData[i]['ticket']+'</td><td>'+jsonData[i]['rut']+'</td><td>'+jsonData[i]['datetime']+'</td><td><button type="button" class="btn btn-primary" onclick="firstTicketId='+jsonData[i]['ticketid']+'; sendComet(&quot;plus&quot;); $(&quot;#modalNoServe&quot;).modal(&quot;hide&quot;);">Usar</button></td></div>');
            	}
            }else{
            	$('#modalNoServeContent').append('<tr><th>No hay pacientes </th></tr>');
            }

    
            $('#modalNoServe').modal('show');
        } 
    });
}


function getTicketData(ticketid){//Recoge la información del ticket solicitado (ticket y log)
    var scriptUrl = "phps/getTicketData.php?id="+ticketid;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
    });
    return result;
}


function activeButtons(type){//Activa o inactiva botones
	if(type=='onload'){
		$('#buttons :input').attr('disabled', true);
		$('#minusButton').attr('disabled', false);
		$('#exceptionButton').attr('disabled', false);
		$('.getout').attr('disabled', false);
    }
	if(type=='next'){
		$('#plusButton').attr('disabled', false);
      	$('#minusButton').attr('disabled', false);
		//$('#isHereButton').attr('disabled', true);
      	$('#notHereButton').attr('disabled', true);
      	$('#finishedButton').attr('disabled', true);
      	$('#redirectButton').attr('disabled', true);
      	$('#exceptionButton').attr('disabled', false);
      	$('#plusDerivedButton').attr('disabled', true);
      	$('.getout').attr('disabled', false);
    }
	/*if(type=='call'){
		$('#plusButton').attr('disabled', true);
      	$('#minusButton').attr('disabled', true);
		//$('#isHereButton').attr('disabled', false);
      	$('#notHereButton').attr('disabled', true);
      	$('#finishedButton').attr('disabled', true);
      	$('#redirectButton').attr('disabled', true);
      	$('#exceptionButton').attr('disabled', true);
      	$('#plusDerivedButton').attr('disabled', true);
      	$('.getout').attr('disabled', true);
    }*/
	if(type=='on_serve'){
		$('#plusButton').attr('disabled', true);
      	$('#minusButton').attr('disabled', true);
		//$('#isHereButton').attr('disabled', true);
      	$('#notHereButton').attr('disabled', false);
      	$('#finishedButton').attr('disabled', false);
      	$('#redirectButton').attr('disabled', false);
      	$('#exceptionButton').attr('disabled', true);
      	$('#plusDerivedButton').attr('disabled', true);
      	$('.getout').attr('disabled', true);
    }

}


///////////////////////////DERIVADOS///////////////////////////////////////
/*
function refreshTableDerived(){ //Actualiza la tabla de pacientes en espera
    var totalResult=getDerivedTickets();
    if(totalResult==0){
        $('#contentTicketDerived tr').has('td').remove();
        $('#contentTicketDerived').append('<tr><td>No hay pacientes en espera...</td></tr>');
        if(myState==false){
        	$('#plusDerivedButton').attr('disabled', true);
        }
    }else{
        var ticketsTable = JSON.parse(totalResult);
        firstTicketIdDerived = ticketsTable[0]['ticketid'];
        var cant=Object.keys(ticketsTable).length;
        $('#contentTicketDerived').fadeOut('slow', function() {
            $('#contentTicketDerived tr').has('td').remove();
                for (var i=0;i<cant;i++) {
                    if(i==0){

                        $('#contentTicketDerived').append('<tr class="info"><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td></tr>');
                    }else{
                        $('#contentTicketDerived').append('<tr><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td>  </tr></tr>');  
                    }
                }
            $('#contentTicketDerived').fadeIn('slow');
            if(myState==false){
            	$('#plusDerivedButton').attr('disabled', false);
            }
        });
    }
}

function getDerivedTickets(){//Devuelve los últimos 5 pacientes en espera
    var result = null;
    var scriptUrl = "phps/getDerivedTickets.php?submodule="+submodule;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
    });
    return result;
}*/

function getActivesModules(){
    var result = null;
    var scriptUrl = "phps/getActivesModules.php?module=" + moduleInCourse;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        },
        error: function(data) {
           
        }
    });

    var jsonModules=JSON.parse(result);
    $("#menuButtons").html('');
    for (var i = 0; i < jsonModules.length; i++) {
        $("#menuButtons").append('<div class="modal-body"><button type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;"" class="btn btn-primary" onclick="derive('+jsonModules[i]['id']+');"><span class="glyphicon glyphicon-time"></span> '+jsonModules[i]['moduleName'] +'</button>   </div>' );
    };

}

</script>
</body>
</html>