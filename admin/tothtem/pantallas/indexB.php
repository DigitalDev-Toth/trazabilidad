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
    <script src="http://falp.biopacs.com:8000/socket.io/socket.io.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/comet.js"></script>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/2-col-portfolio.css" rel="stylesheet">
  
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
               
                <h1 class="page-header"><div id="ModuleHeader">...</div>
                    <small id="modalityTitle"></small>
                    <button class="btn btn-default getout pull-right" onclick="inactiveSubModule('logout',false)"><span class="glyphicon glyphicon-log-out"></span> SALIR</button>  
                    <!--<button class="btn btn-default getout pull-right" onclick="$(location).attr('href','../../exit.php');"><span class="glyphicon glyphicon-log-out"></span> SALIR</button>  -->
                    <p class="pull-right">&nbsp;</p>
                    <!--<button class="btn btn-primary getout pull-right" onclick="inactiveSubModule('change')"> <span class="glyphicon glyphicon-transfer"></span> CAMBIAR SUBMÓDULO</button>
                    <p class="pull-right">&nbsp;</p>-->

                    <button class="btn btn-primary getout pull-right" id="pause" onclick="inactiveSubModule('pause')"> <span class="glyphicon glyphicon-pause"></span> PAUSAR ATENCIÓN</button>
                    <span id="timeAttention" class="pull-right">-</span>
                </h1>

            </div>
        
        </div>

        <div class="row">
            <div class="col-md-12">
           		 <div class="panel panel-primary">
                    <div class="panel-heading"><span class="glyphicon glyphicon-list"></span>  Panel De Control Pacientes en curso</div>
                    <div class="panel-body" align="center">
                        <div class="row" >
                            <label>Numero</label><br/>
                            <label id="content" style="font-size:70px;"></label><br/>
                            <label id="timePatient" style="font-size:40px;"></label>

                            <div class="row">
                                <div class="col-md-4">
                                    
                                </div>
                                <div class="col-md-4">
                                    <div id="buttons" class="text-center">
                                
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-default btn-lg" onclick="sendComet('minus')" id="minusButton" title="Volver a llamar número anterior"><span class="glyphicon glyphicon-minus"></span></button>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-default btn-lg" onclick="sendComet('plus')" id="plusButton" title="Llamar paciente"><span class="glyphicon glyphicon-plus"></span></button>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-default btn-lg" onclick="sendComet('recall')" id="recallButton" title="Re-Llamar paciente"><span class="glyphicon glyphicon-bullhorn"></span></button>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-default btn-lg" onclick="sendComet('notHere')" id="notHereButton" title="No llegó paciente" style="color: red;"><span class="glyphicon glyphicon-remove-circle"></span></button>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-default btn-lg" onclick="sendComet('finished')" id="finishedButton" title="Finalizar Atención" ><span class="glyphicon glyphicon-thumbs-up"></span></button>                                         
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-default btn-lg" onclick="sendComet('redirect')" id="redirectButton" title="Derivar"><span class="glyphicon glyphicon-share"></span></button>                                        
                                        </div>
                                        <!--<div class="col-md-2">
                                            <button type="button" class="btn btn-default btn-lg" onclick="refreshTable()" id="refreshButton" title="Rechargar Tabla"><span class="glyphicon glyphicon-refresh"></span></button>
                                        </div>-->
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    
                                </div>
                            	
                            </div>
                        
                        </div>

                        <!-- MODAL no atendidos-->
						<div id="modalNoServe" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="row">
										<h4 id="noServeTitle" class="text-center">Pacientes No Atendidos...<h4>
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
									<!--<div class="row">
										
									</div>
									<table id="modalNoServeContent" class="table table-striped text-center">
									</table>-->
								</div>
							</div>
						</div>
						
                    </div>

                    <table id="contentTicket" class="table table-striped text-center" >
                        <th class="text-center">Numero de atencion</th><th class="text-center">RUT</th><th class="text-center">Hora Retiro de Ticket</th><th class="text-center">Tiempo de espera</th>
                    </table>


                </div>
            </div>
        </div>


        <div class="row">
        	<div class="col-md-12">
    			<div class="panel panel-primary">
                	<div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Datos Del Paciente</div>
                    <div class="panel-body" align="center">
                        <div class="col-lg-10 col-md-10" >
                       		<div class="row">
                                <div class="col-md-4 text-center">
                                    <div id="patientPicture"></div>
                                </div>
                                <div class="col-md-7">
                                <div id="patientData"></div>    
                            </div>
                            </div>
                           
                            
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


<div id="standBy" class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
          <b> <h4><span class="glyphicon glyphicon-fullscreen"></span> <p>Expandir</p></h4></b>

        </div>
    </div>
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
var subModuleType ='';
var noRedirect = false; //Evita que se puedan derivar pacientes en caso de que el módulo no tenga asociada derivación
var exitLog = true;

var socket = io.connect('http://falp.biopacs.com:8000');

var waitingInterval = setInterval(function(){},5000);
var attentionInterval = '';
var patientInterval = '';
var actualComet = '';//Utilizado para re-llamar al paciente
//se extrae la modalidad , el ultimo numero y se rellena la tabla

/////////////////////EVENTOS//////////////////////////////////

$(document).ready(function() {
    socketComet();
    $("#standBy").hide();

    submodule=decodeURIComponent("<?php echo rawurlencode($_GET['id']); ?>");
    if(submodule != ""){
        $.post('phps/getModuleType.php', { submodule: submodule}, function(data, textStatus, xhr) {
            subModuleType=data;
            if(data != 12){//Módulo especial
                $("#contentTicket").html('<th class="text-center">Numero de atencion</th><th class="text-center">RUT</th><th class="text-center">Hora Retiro de Ticket</th><th class="text-center">Tiempo de espera</th>')
            }else{
                $("#contentTicket").html('<th class="text-center">Numero de atencion</th><th class="text-center">RUT</th><th class="text-center">Motivo</th><th class="text-center">Hora Retiro de Ticket</th><th class="text-center">Tiempo de espera</th>')    
            ////////////CARGA DE PACIENTES////////////

            $("#buttons").append('<div class="col-md-2"><button type="button" class="btn btn-default btn-lg" onclick="getExceptions();" id="patientsButton" title="Pacientes..."><span class="glyphicon glyphicon-user"></span></button></div>');


            //////////////////////////////////////
            }

            var dataModality = JSON.parse(getModule(submodule));
            moduleInCourse=dataModality.modalityId;
            $("#modalityTitle").text(dataModality['modalityName']);
            $("#ModuleHeader").text(dataModality['subModuleName']);
            initNumber = dataModality['modalityTicket'];
            refreshTable();
            setCurrentNumber();
            getActivesModules();
        });
        
        
    }else{
        alert("Falta Modalidad!");
    }
    attentionTime();
});

function closeWindow(){ 
    window.open('','_self',''); 
    window.close(); 
} 

$(window).on('beforeunload', function(e) {
    if(exitLog==true){
        return 'Se cerrara su sesion';
    }
});
$(window).on('unload', function(e) {
    
    return inactiveSubModule('logout',true);
});



window.onresize = function(event) {
    var widthP = $(window).width();  
    var heightP = $(window).height(); 
    if(widthP <= 300 && heightP < 300){
        $("#AllPage").hide();
        $("#standBy").show();
    }else{
        $("#AllPage").show();
        $("#standBy").hide();
    }

};

///////////////OBTENCIÓN REGISTROS///////////////////////////////////////
function getPatientData(ticketId){
    var totalResult = getTicketData(ticketId);
    var jsonData = JSON.parse(totalResult);
    var namePatient = '';
    $.post('../tothtem/scripts/getPatientName.php',{rut: jsonData[0]['rut']},function(data, textStatus, xhr){

        if(data!='0'){
            var dataJson = JSON.parse(data);
            namePatient = '<br><p>Nombre:'+dataJson[0]['name']+' '+dataJson[0]['lastname']+'</p>';
            namePatient +='<p>Fecha de Nacimiento:'+dataJson[0]['birthdate'] +'</p>';
            namePatient +='<p>Genero:'+gender(dataJson[0]['gender'])+'</p>';
            namePatient +='<p>Direccion:'+dataJson[0]['address']+'</p>';
            $('#patientData').html(namePatient);
            $('#patientPicture').html('<img src="http://placehold.it/200x200">');
            //$("#patientPicture").html('<img src="http://1.bp.blogspot.com/_jSIwJJQzdUU/TOIWjGmPkCI/AAAAAAAAAEo/GkjnGk1v76s/s1600/kermit4_Kermit_the_Frog-s1000x600-93067.jpg" style="width: 200px; height:200px;">');

        
        }else{
           
            $('#patientPicture').html('');
            $('#patientData').html('<p>Sin Datos Asociados al paciente</p>');
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

function getActivesModules(){
    var result = null;
    //var scriptUrl = "phps/getActivesModules.php?module=" + moduleInCourse;
    var scriptUrl = "phps/getDerivationModules.php?module=" + moduleInCourse;
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
    if(result!=0){
        var jsonModules=JSON.parse(result);
        $("#menuButtons").html('');
        for (var i = 0; i < jsonModules.length; i++) {
            if(jsonModules[i]['moduleType']!='Especial'){
                $("#menuButtons").append('<div class="modal-body"><button type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;"" class="btn btn-primary" onclick="derive('+jsonModules[i]['id']+');"><span class="glyphicon glyphicon-time"></span> '+jsonModules[i]['moduleName'] +'</button>   </div>' );
            }else{
                var moduleId = jsonModules[i]['id'];
                $.post('phps/getActivesModulesSpecial.php', {module: moduleId}, function(data, textStatus, xhr) {
                    if(data!='nan'){
                        var jsonData = JSON.parse(data);
                        for(j=0; j < jsonData.length;j++){
                            var widthButton = '';
                            if(jsonData[j]['name'].length<17) widthButton='width: 300px;';
                            else widthButton='';
                            $("#menuButtons").append('<div class="modal-body"><button type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;'+widthButton+'" class="btn btn-primary" onclick="derive('+moduleId+');"><span class="glyphicon glyphicon-time"></span> '+jsonData[j]['name'] +'</button>   </div>' );
                        }
                    }
                });
            }
        };
    }else{
        noRedirect=true;
        $('#redirectButton').attr('disabled', true);
    }

}


function inactiveSubModule(typeButton,doLog){//Desactiva el submódulo y genera log de cierre de sesión
    var actionType = 'inactivo'
    if(typeButton=='pause'){ 
        actionType='pausado';
        $('.panel-primary').removeClass('panel-primary').addClass('panel-info');
        $('#pause').html('<span class="glyphicon glyphicon-play"></span> REANUDAR ATENCIÓN');
        $('#pause').attr('onclick', 'inactiveSubModule("replay")');
        activeButtons('pause');
        $('#content').text('En Pausa');
        clearInterval(attentionInterval);
    }
    if(typeButton=='replay'){ 
        actionType='re-activo';
        $('.panel-info').removeClass('panel-info').addClass('panel-primary');
        $('#pause').html('<span class="glyphicon glyphicon-pause"></span> PAUSAR ATENCIÓN');
        $('#pause').attr('onclick', 'inactiveSubModule("pause")');
        refreshTable();
        $('#content').text('Esperando...');
        //attentionTime();
    }

    if(typeButton!='logout'){
        $.post('phps/activeSubModule.php', {type: actionType, user: "<?php echo $_SESSION['UserId']; ?>", submodule: submodule}, function(data, textStatus, xhr) {
            socket.send(data);
            if(typeButton=='replay'){
                attentionTime();
            }
        });



    }else if(typeButton=='logout' && doLog==false){
        exitLog=false;
        $.post('phps/activeSubModule.php', {type: actionType, user: "<?php echo $_SESSION['UserId']; ?>", submodule: submodule}, function(data, textStatus, xhr) {
                socket.send(data);
                $(location).attr('href','../../exit.php');
            //});
        });
        
    }else if(typeButton=='logout' && doLog==true){
        if(exitLog==true){
            $.post('phps/activeSubModule.php', {type: actionType, user: "<?php echo $_SESSION['UserId']; ?>", submodule: submodule}, function(data, textStatus, xhr) {
                socket.send(data);
            });
        }    
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

/*function getAllTicketsSpecial(){//Devuelve todos los pacientes en espera de módulos especiales
    var result = null;
    var scriptUrl = "phps/lastTicketsAll.php?submodule="+submodule;
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
            $('#noServeTitle').html('Pacientes rezagados...');
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
                $('#modalNoServeContent').append('<tr><th>Ticket</th><th>Paciente</th><th>Motivo</th><th>Tiempo</th><th></th></tr>');
                for(i=0;i<jsonData.length;i++){
                    $('#modalNoServeContent').append('<tr><td>'+jsonData[i]['ticket']+'</td><td>'+jsonData[i]['rut']+'</td><td>'+jsonData[i]['name']+'</td><td>'+jsonData[i]['datetime']+'</td><td><button type="button" class="btn btn-primary" onclick="firstTicketId='+jsonData[i]['ticketid']+'; sendComet(&quot;plus&quot;); $(&quot;#modalNoServe&quot;).modal(&quot;hide&quot;);">Usar</button></td></div>');
                }
            }else{
                $('#modalNoServeContent').append('<tr><th>No hay pacientes </th></tr>');
            }

            $('#noServeTitle').html('Pacientes en espera...');
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



function refreshTable(){ //Actualiza la tabla de pacientes en espera
    var totalResult=getLast5Tickets(submodule,initNumber);
    if(totalResult==0){
        $('#contentTicket tr').has('td').remove();
        if(subModuleType == 12){
            $('#contentTicket').append('<tr><td>No hay pacientes en espera...</td><td></td><td></td><td></td><td></td></tr>');
        }else{
            $('#contentTicket').append('<tr><td>No hay pacientes en espera...</td><td></td><td></td><td></td></tr>');    
        }
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
                if(subModuleType != 12){//Módulo especial
                    if(i==0){
                        $('#contentTicket').append('<tr class="info"><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td><td class="waitingTime"><span>'+hourDiff(ticketsTable[i]['datetime'])+'</span><span style="display: none;">'+ticketsTable[i]['datetime']+'</span></td></tr>');
                    }else{
                        $('#contentTicket').append('<tr><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td><td class="waitingTime"><span>'+hourDiff(ticketsTable[i]['datetime'])+'</span><span style="display: none;">'+ticketsTable[i]['datetime']+'</span></td></tr>');  
                    }    
                }else{
                    if(i==0){
                        $('#contentTicket').append('<tr class="info"><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['name']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td><td class="waitingTime"><span>'+hourDiff(ticketsTable[i]['datetime'])+'</span><span style="display: none;">'+ticketsTable[i]['datetime']+'</span></td></tr>');
                    }else{
                        $('#contentTicket').append('<tr><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['name']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td><td class="waitingTime"><span>'+hourDiff(ticketsTable[i]['datetime'])+'</span><span style="display: none;">'+ticketsTable[i]['datetime']+'</span></td></tr>');  
                    } 
                  
                }
     
            }

            $('#contentTicket').fadeIn('slow');
            if(myState==false){
                activeButtons('next');
            }
        });
    }
    clearInterval(waitingInterval);
    waitingInterval = setInterval(function(){updateWaitingTime();},5000);
}

function setCurrentNumber(){//Muestra el número actual que se está atendiendo
    $.post('phps/getCallTicket.php', {submodule: submodule}, function(data, textStatus, xhr) {
        if(data!=0){
            var jsonData = JSON.parse(data);
            var number = jsonData[0].ticket;

            if(number.length==2){
                number='00'+number;
            }
            if(number.length==3){
                number='0'+number;
            }
            $('#content').fadeOut("slow",function(){
                $('#content').fadeIn("fast");
                $('#content').text(number);
            });
            activeButtons(jsonData[0].attention);
            ticketAttention = jsonData[0].ticketid;
            timePatient();
        }else{
            //$('#content').text('Standby');
            $('#content').text('Esperando...');

        }
    });
}

function getSubModuleState(){//Muestra el número actual que se está atendiendo
    $.post('phps/getSubModuleState.php', {submodule: submodule}, function(data, textStatus, xhr) {
        if(data=='pausado'){
            actionType='pausado';
            $('.panel-primary').removeClass('panel-primary').addClass('panel-info');
            $('#pause').html('<span class="glyphicon glyphicon-play"></span> REANUDAR ATENCIÓN');
            $('#pause').attr('onclick', 'inactiveSubModule("replay")');
            activeButtons('pause');
            $('#content').text('En Pausa');
        }
    });
}
///////////////ESCRITURA REGISTROS///////////////////////////////////////


function insertLog(description,action,cometType,attentionNew,ticketId,module){//Inserción de log y escritura de comet
    var totalResult = getTicketData(ticketId);
    if(totalResult!=0){
        var jsonData = JSON.parse(totalResult);
        $.post('services/insertLogs.php', {rut: jsonData[0].rut,description:description,action:action,subModule:submodule,cometType:cometType,attentionNew:attentionNew,ticketId:ticketId,module:module} , function(data, textStatus, xhr) {
            if(data!=0){

            	//Caso de derivación a otra zona
                if(module!=moduleInCourse && description=='Ticket Derivado'){
                	var cometOrigin = JSON.parse(data);
                	
                	cometOrigin.description = "Ticket Finalizado";
                	cometOrigin.action = "lb";
                	socket.send(JSON.stringify(cometOrigin));

					$.post('phps/getTothtem_derivation.php', {zone: cometOrigin.zone} , function(data2, textStatus, xhr) {
						var dataNew = JSON.parse(data2);
						
	                	cometOrigin.description = "Ingreso a sala";
	                	cometOrigin.action = "in";
	                	var submoduleOrigin = cometOrigin.submodule;
	                	cometOrigin.submodule = dataNew[0].submodule;
	                	cometOrigin.module = dataNew[0].module;
	                	cometOrigin.comet = "tothtem";
	                	socket.send(JSON.stringify(cometOrigin));

	                	cometOrigin.description = "Ingreso a espera";
	                	cometOrigin.action = "to";
	                	cometOrigin.submodule = submoduleOrigin;
	                	cometOrigin.module = module.toString();	                	
	                	cometOrigin.comet = "module";
	                	setTimeout(function(){
	                		socket.send(JSON.stringify(cometOrigin));
	                	},1500);
	                	//{"comet":"module","rut":"17.443.625-8","datetime":"2015-02-16 17:09:04","description":"Ticket Finalizado","zone":"1","action":"lb","submodule":"83","module":"34","newticket":"255B","idticket":"830","name":"JULIO ESPINOZA"}
	                	//socket.send(data);
	                	//{"comet":"tothtem","rut":"17.443.625-8","datetime":"2015-02-16 17:07:51","description":"Ingreso de RUT Totem","zone":"1","action":"in","submodule":"47","module":"33","name":"JULIO ESPINOZA"}
						//a espera            
						//{"comet":"module","rut":"17.172.852-5","datetime":"2015-02-16 17:01:55","description":"Retiro de ticket N\u00ba 254 , M\u00f3dulo 34","zone":"1","action":"to","submodule":"47","module":"34","newticket":"254B","name":"ENZO LATORRE BARRA"}
	                	setCurrentNumber();
	                });
                }else{
                	checkComet(data);
                	if(description=='Ticket ha venido'){
                		actualComet=data;
                	}
                }
                if(attentionNew =='on_serve'){
                    myState = true;
                    activeButtons('on_serve');
                    
                    var dataComet = JSON.parse(data);
                    getPatientData(dataComet.idticket);
                }

                
            }
            refreshTable();

        });
    }
}

function checkComet(data){
    socket.send(data);
    setCurrentNumber();
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

function sendComet(type){//Genera la acción de los distintos botones a través de un alias

    if(type==='minus'){
        getNoServeTickets();
    }

    if(type==='isHere' || type==='plus'){
        ticketAttention = firstTicketId;
        insertLog('Ticket ha venido','in','module','on_serve',ticketAttention);
        //timePatient();
    }
    if(type==='notHere'){
        myState = false;
        insertLog('Ticket Ausente','lb','module','no_serve',ticketAttention);
        $('#content').css('color','black');
        $('#patientPicture').html('');
        $('#patientData').html('');
        clearInterval(patientInterval);
        $('#timePatient').html('');
        
    }
    if(type==='finished'){
        myState = false;
        insertLog('Ticket Finalizado','lb','module','served',ticketAttention);
        $('#content').css('color','black');
        $('#patientPicture').html('');
        $('#patientData').html('');
        clearInterval(patientInterval);
        $('#timePatient').html('');
        
    }
    if(type==='redirect'){
        //getActivesModules();
        $('#modalDerived').modal('show');
    }
    if(type==='exception'){
        $('#modalException').modal('show');
    }
    if(type=='recall'){
    	socket.send(actualComet);
    }

}

function derive(moduleTo){//Deriva el ticket al módulo seleccionado
    myState = false;
    //activeButtons('call');
    insertLog('Ticket Derivado','to','module','derived',ticketAttention,moduleTo);//En este caso, insertlog recibirá el módulo al que se deriva
    $('#modalDerived').modal('hide');
    $('#content').css('color','black');
    //refreshTable();
    $('#patientPicture').html('');
    $('#patientData').html('');
    clearInterval(patientInterval);
    $('#timePatient').html('');
	
}


///////////////DISEÑO, OBJETOS VISIBLES, OTROS///////////////////////////
function activeButtons(type){//Activa o inactiva botones
    if(type=='onload'){
        $('#buttons :input').attr('disabled', true);
        $('#minusButton').attr('disabled', false);
        $('#exceptionButton').attr('disabled', false);
        $('#patientsButton').attr('disabled', false);
        $('.getout').attr('disabled', false);
    }
    if(type=='next'){
        $('#plusButton').attr('disabled', false);
        $('#minusButton').attr('disabled', false);
        $('#recallButton').attr('disabled', true);
        $('#notHereButton').attr('disabled', true);
        $('#finishedButton').attr('disabled', true);
        $('#redirectButton').attr('disabled', true);
        $('#exceptionButton').attr('disabled', false);
        $('#plusDerivedButton').attr('disabled', true);
        $('#patientsButton').attr('disabled', false);
        $('.getout').attr('disabled', false);
        if(subModuleType==12)$('#patientButton').attr('disabled', false);
    }

    if(type=='on_serve'){
        $('#plusButton').attr('disabled', true);
        $('#minusButton').attr('disabled', true);
        $('#recallButton').attr('disabled', false);
        $('#notHereButton').attr('disabled', false);
        $('#finishedButton').attr('disabled', false);
        if(noRedirect==false) $('#redirectButton').attr('disabled', false);
        $('#exceptionButton').attr('disabled', true);
        $('#plusDerivedButton').attr('disabled', true);
        $('#patientsButton').attr('disabled', true);
        $('.getout').attr('disabled', true);
        if(subModuleType==12)$('#patientButton').attr('disabled', false);
    }

    if(type=='pause'){
        $('#plusButton').attr('disabled', true);
        $('#minusButton').attr('disabled', true);
        $('#recallButton').attr('disabled', false);
        $('#notHereButton').attr('disabled', true);
        $('#finishedButton').attr('disabled', true);
        $('#redirectButton').attr('disabled', true);
        $('#exceptionButton').attr('disabled', true);
        $('#plusDerivedButton').attr('disabled', true);
        $('#patientsButton').attr('disabled', true);
        $('.getout').attr('disabled', false);
        if(subModuleType==12)$('#patientButton').attr('disabled', false);
    }

}

function hourDiff(initialHour){//Calcula el tiempo de espera en minutos
    var initialHour = new Date(initialHour);
    var finishedHour = new Date();
    finishedHour.setSeconds(finishedHour.getSeconds() + 15);

    
    if (finishedHour < initialHour) {
        finishedHour.setDate(finishedHour.getDate() + 1);
    }
    var diff = finishedHour - initialHour;

    return Math.floor(diff / 1000 / 60)+' Minutos';
    //return Math.floor(diff / 1000)+' Segundos';

}

function updateWaitingTime(){ //Actualiza tiempo de espera de cada paciente
    var waitingPatients = document.getElementsByClassName('waitingTime').length;
    for(var i=0;i<waitingPatients;i++){
        document.getElementsByClassName('waitingTime')[i].childNodes[0].innerHTML = hourDiff(document.getElementsByClassName('waitingTime')[i].childNodes[1].innerHTML);
    }
}

function attentionTime(){//Tiempo en que ha estado disponible el usuario
    var initTime;
    $.post('phps/getAttentionTime.php', {user: "<?php echo $_SESSION['UserId']; ?>"}, function(data, textStatus, xhr) {
        initTime = data;
        $('#timeAttention').html(initTime);
    });

    //initTime = '16:59:50';
    
        
    attentionInterval = setInterval(function(){
        var time = $('#timeAttention').html().split(':');
        time[2]++;
        if(time[2]<10) time[2]='0'+time[2];
        if(time[2]=='60'){
           time[2]='00';
           time[1]++;
           if(time[1]<10) time[1]='0'+time[1];
        }
        
        if(time[1]=='60'){
           time[1]='00';
           time[0]++;
           if(time[0]<10) time[0]='0'+time[0];
        }

        $('#timeAttention').html(time[0]+':'+time[1]+':'+time[2]);
    },1000);
}

function timePatient(){//Tiempo de atención del paciente actual

    var initTime;
    $.post('phps/getAttentionTimePatient.php', {submodule: submodule}, function(data, textStatus, xhr) {
        initTime = data;
        $('#timePatient').html(initTime);
    });
     
    clearInterval(attentionInterval);
    attentionTime();
    patientInterval = setInterval(function(){
        var time = $('#timePatient').html().split(':');
        time[2]++;
        if(time[2]<10) time[2]='0'+time[2];
        if(time[2]=='60'){
           time[2]='00';
           time[1]++;
           if(time[1]<10) time[1]='0'+time[1];
        }
        
        if(time[1]=='60'){
           time[1]='00';
           time[0]++;
           if(time[0]<10) time[0]='0'+time[0];
        }

        $('#timePatient').html(time[0]+':'+time[1]+':'+time[2]);
    },1000);
}


//so doge, wow, much code
/*$("#patientPicture").hover(function() {
    //CHLOE$("#patientPicture").html('<img src="http://i0.kym-cdn.com/entries/icons/original/000/014/285/not.jpg" style="height: 200px; width: 200px;">');
    //KERMIT
    $("#patientPicture").html('<img src="http://media.giphy.com/media/DpB9NBjny7jF1pd0yt2/giphy.gif" style="height: 200px; width: 200px;">');
}, function() {
    //KERMIT
    $("#patientPicture").html('<img src="http://1.bp.blogspot.com/_jSIwJJQzdUU/TOIWjGmPkCI/AAAAAAAAAEo/GkjnGk1v76s/s1600/kermit4_Kermit_the_Frog-s1000x600-93067.jpg" style="width: 200px; height:200px;">');
    //$("#patientPicture").html('<img src="http://placehold.it/200x200">');
});*/


function gender(gen){
    if(gen=='M'){
        return 'MASCULINO';
    }else{
        return 'FEMENINO';
    }
}


</script>
</body>
</html>