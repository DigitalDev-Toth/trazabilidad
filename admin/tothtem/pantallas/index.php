<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php"); header('Content-Type: text/html; charset=utf8');  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>pantallas</title>
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
                <!--<a href="../../exit.php" class="btn btn-default">SALIR</a>  -->
                <button class="btn btn-primary" onclick="inactiveSubModule('change')">CAMBIAR SUBMÓDULO</button>  
                <button class="btn btn-default" onclick="inactiveSubModule('logout')">SALIR</button>  
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
                            			<button type="button" class="btn btn-default btn-lg" onclick="sendComet('minus')" id="minusButton" title="Volver"><span class="glyphicon glyphicon-minus"></span></button>
                            		</div>
                            		<div class="col-md-2">
	                               		<button type="button" class="btn btn-default btn-lg" onclick="sendComet('plus')" id="plusButton" title="Llamar paciente"><span class="glyphicon glyphicon-plus"></span></button>
                            			
                            		</div>
                            		<div class="col-md-1">
	                                	<button type="button" class="btn btn-default btn-lg" onclick="sendComet('isHere')" id="isHereButton" title="Inicio de Atención"><span class="glyphicon glyphicon-play"></span></button>
                            			
                            		</div>
                            		<div class="col-md-2">
	                                	<button type="button" class="btn btn-default btn-lg" onclick="sendComet('notHere')" id="notHereButton" title="No llegó el paciente"><span class="glyphicon glyphicon-remove-circle"></span></button>
                            			
                            		</div>
                            		<div class="col-md-1">
	                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet('finished')" id="finishedButton" title="Finalizar Atención"><span class="glyphicon glyphicon-thumbs-up"></span></button>
                            			
                            		</div>
                            			<div class="col-md-2">
	                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet('redirect')" id="redirectButton" title="Derivar"><span class="glyphicon glyphicon-share"></span></button>
                            			
                            		</div>
                            		<div class="col-md-1">
	                                	<button type="button" class="btn btn-default btn-lg" onclick="sendComet('exception')" id="exceptionButton" title="Derivar"><span class="glyphicon glyphicon-exclamation-sign"></span></button>
                            		</div>

                            		                            		                            		                            		

                            	</div>
                            </div>
                        
                        </div>


                        <!--
                        <div style="right: 85px;" class="col-lg-2 col-md-2" >
                            <label>Modulo</label><br>
                            <div id="buttonsModules" align="center">
                                <button type="button" style="width:45px"  class="btn btn-default btn-xs " onclick="changeModule(1)">  <span class="glyphicon glyphicon-chevron-up"></span></button>
                                <button id="module" type="button" class="btn btn-default btn-lg" disabled>A</button>
                                <button type="button" style="width: 45px;" class="btn btn-default btn-xs " onclick="changeModule(0)">  <span class="glyphicon glyphicon-chevron-down"></span></button>   
                            </div>
                        </div>
                        -->
                    </div>
                    <table id="contentTicket" class="table table-striped" >
                        <th>Numero de atencion</th><th>RUT</th><th>Hora pedido</th><th>¿Atendido?</th>
                    </table>
                </div>
            </div>
            <div class="col-md-6">

            		<div class="panel panel-default">
	                    <div class="panel-heading">Pacientes Derivados</div>
		                    <div class="panel-body" align="center">
		                        <div class="col-lg-10 col-md-10" >
		                            <label>Numero</label><br>
		                            <label id="content" style="font-size:70px;"></label>
		                            <div id="buttons">
		                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet()"><span class="glyphicon glyphicon-minus"></span></button>
		                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet(1)" id="fghgfh"><span class="glyphicon glyphicon-plus"></span></button>-
		                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet(3)"><span class="glyphicon glyphicon-repeat"></span></button>
		                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet(4)"><span class="glyphicon glyphicon-bullhorn"></span></button>
		                            </div>
		                        </div>
		                        <div style="right: 85px;" class="col-lg-2 col-md-2" >
		                            <label>Modulo</label><br>
		                            <div id="buttonsModules" align="center">
		                                <button type="button" style="width:45px"  class="btn btn-default btn-xs " onclick="changeModule(1)">  <span class="glyphicon glyphicon-chevron-up"></span></button>
		                                <button id="module" type="button" class="btn btn-default btn-lg" disabled>A</button>
		                                <button type="button" style="width: 45px;" class="btn btn-default btn-xs " onclick="changeModule(0)">  <span class="glyphicon glyphicon-chevron-down"></span></button>   
		                            </div>
		                        </div>
		                    </div>
		                    <table id="contentTicket" class="table table-striped" >
		                        <th>Numero de atencion</th><th>RUT</th><th>Hora pedido</th><th>¿Atendido?</th>
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
	                       		datos del paciente...
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
var initNumber;
var firstTicketId = '';
var ticketAttention = 0; //Indica qué número se está atendiendo
var myState = false; //Indica si el submódulo está atendiendo y/o llamando


//se extrae la modalidad , el ultimo numero y se rellena la tabla
$( document ).ready(function() {
    submodule=decodeURIComponent("<?php echo rawurlencode($_GET['id']); ?>");
    if(submodule != ""){
        var dataModality = JSON.parse(getModule(submodule));
        moduleInCourse=dataModality.modalityId;
        $("#modalityTitle").text(dataModality['modalityName']);
        initNumber = dataModality['modalityTicket'];
        refreshTable();
        setCurrentNumber();
        //setCurrentNumber('inCourse',initNumber);
       	//activeButtons('onload');
    }else{
        alert("Falta Modalidad!");
    }
});

function inactiveSubModule(typeButton){
    $.post('phps/activeSubModule.php', {type: 'inactivo', user: "<?php echo $_SESSION['UserId']; ?>", submodule: submodule}, function(data, textStatus, xhr) {
        if(typeButton=='logout'){
            $(location).attr('href','../../exit.php');
        }else{
            $(location).attr('href','selector.php');
        }
    });
}

function setCurrentNumber(){
	console.log(submodule);
	$.post('phps/getCallTicket.php', {submodule: submodule}, function(data, textStatus, xhr) {
		console.log(data);
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
		}else{
			$('#content').text('S/N');
		}
    });
}



function getModule(idSubModule){
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

function refreshTickets(idsubModule,attention,tickets){
    var result = null;
    var scriptUrl = "phps/refreshTickets.php?submodule="+idsubModule+"&attention="+attention+"&tickets="+tickets;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
    });
    $.post('../../../visor/comet/backend.php',{msg: result},function(data, textStatus, xhr){
    });
    return result;
}

function insertLog(description,action,cometType,attentionOriginal,attentionNew,ticketId){
	var totalResult =getLast5Tickets(submodule,initNumber);
	if(totalResult!=0){
		var jsonData = JSON.parse(totalResult);
		$.post('services/insertLogs.php', {rut: jsonData[0].rut,description:description,action:action,subModule:submodule,cometType:cometType,attentionOriginal:attentionOriginal,attentionNew:attentionNew,ticketId:ticketId} , function(data, textStatus, xhr) {
		    $.post('../../../visor/comet/backend.php',{msg: data},function(data, textStatus, xhr){
			});
		});
	}
}

/*
	first state : waiting

			minus : previous 
			plus  : call
			play  : on_serve
			stop  : not_serve
			like  : served
			arrow : derived 

*/

function sendComet(type){


	if(type==='minus'){
		myState = true;
	}
	if(type==='plus'){
		myState = true;
		activeButtons('call');
		ticketAttention = firstTicketId;
		insertLog('Siguiente Ticket','cl','module','waiting','call',firstTicketId);
		setCurrentNumber();
	}
	if(type==='isHere'){
		insertLog('Ticket ha venido','cl','module','call','on_serve');
		//refreshTickets(submodule,'on_serve',(initNumber-1));
	}
	if(type==='notHere'){
		myState = false;
		insertLog('Ticket Ausente','cl','module','call','no_serve',ticketAttention);
		ticketAttention=0;
	}
	if(type==='finished'){
		myState = false;
	}
	if(type==='redirect'){
		myState = false;
	}

 /* 	if(type==1){
    		initNumber++;
        //remove last ticket
        var attention='';
        if($("#checkServe").is(':checked')) {  
          attention='on_serve';
        }else{
          attention='no_serve';
        }
        refreshTickets(submodule,attention,(initNumber-1));
        //comet2.doRequest("!");
  	}
  	if(type==2){
        if(initNumber>1){
          initNumber--;
        }
  	}
  	if(type==3){
    		if (confirm('reset?')) {
    			initNumber=1;
    		}
  	}
    currentNumber=initNumber;
    if(currentNumber<10){
      currentNumber='00'+currentNumber;
    }
    if(currentNumber>=10 && currentNumber<100){
      currentNumber='0'+currentNumber;
    }
    $('#content').fadeOut("slow",function(){
        $('#content').fadeIn("fast");
        if(currentNumber!='null'){
            $('#content').text(currentNumber);
        }else{
            $('#content').text('S/N');
        } 
    });
	//$('#buttons :input').attr('disabled', true);
  	//window.setTimeout(function(){
      //	$('#buttons :input').attr('disabled', false);
    //}, 1000);

    var currentModuleLetter=$("#module").text();
    //write document
  	//comet.doRequest(initNumber+"?"+module+"?"+currentModuleLetter);
    //refreshTable();*/
}



function refreshTable(){
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

                        $('#contentTicket').append('<tr class="info"><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime']+'</td><td> <input type="checkbox" id="checkServe" checked></td></tr>');
                    }else{
                        $('#contentTicket').append('<tr><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime']+'</td>  </tr></tr>');  
                    }
                }
            $('#contentTicket').fadeIn('slow');
            if(myState==false){
            	activeButtons('next');
            }
        });
    }
}

function getLast5Tickets(idModule,last){
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

function activeButtons(type){
	if(type=='onload'){
		$('#buttons :input').attr('disabled', true);
    }
	if(type=='next'){
		$('#plusButton').attr('disabled', false);
      	$('#minusButton').attr('disabled', false);
		$('#isHereButton').attr('disabled', true);
      	$('#notHereButton').attr('disabled', true)
      	$('#finishedButton').attr('disabled', true)
      	$('#redirectButton').attr('disabled', true)
      	$('#exceptionButton').attr('disabled', false)
    }
	if(type=='call'){
		$('#plusButton').attr('disabled', true);
      	$('#minusButton').attr('disabled', true);
		$('#isHereButton').attr('disabled', false);
      	$('#notHereButton').attr('disabled', false)
      	$('#finishedButton').attr('disabled', true)
      	$('#redirectButton').attr('disabled', true)
      	$('#exceptionButton').attr('disabled', true)
    }
	if(type=='on_serve'){
		$('#plusButton').attr('disabled', true);
      	$('#minusButton').attr('disabled', true);
		$('#isHereButton').attr('disabled', true);
      	$('#notHereButton').attr('disabled', true)
      	$('#finishedButton').attr('disabled', false)
      	$('#redirectButton').attr('disabled', false)
      	$('#exceptionButton').attr('disabled', true)
    }
		
}


</script>
</body>
</html>