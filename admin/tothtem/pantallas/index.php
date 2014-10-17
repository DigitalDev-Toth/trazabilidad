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
            <div class="col-md-12">
               
                <h1 class="page-header"><div id="ModuleHeader">...</div>
                    <small id="modalityTitle"></small>
                        <button class="btn btn-default getout pull-right" onclick="inactiveSubModule('logout')"><span class="glyphicon glyphicon-log-out"></span> SALIR</button>  
                        <p class="pull-right">&nbsp;</p>
                        <button class="btn btn-primary getout pull-right" onclick="inactiveSubModule('change')"> <span class="glyphicon glyphicon-transfer"></span> CAMBIAR SUBMÓDULO</button>
                        <p class="pull-right">&nbsp;</p>
                        <button class="btn btn-primary getout pull-right" id="pause" onclick="inactiveSubModule('pause')"> <span class="glyphicon glyphicon-pause"></span> PAUSAR ATENCIÓN</button>

                </h1>

            </div>
        
        </div>

        <div class="row">
            <div class="col-md-12">
           		 <div class="panel panel-primary">
                    <div class="panel-heading"><span class="glyphicon glyphicon-list"></span>  Panel De Control Pacientes en curso</div>
                    <div class="panel-body" align="center">
                        <div class="row" >
                            <label>Numero</label><br>
                            <label id="content" style="font-size:70px;"></label>
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
                                            <button type="button" class="btn btn-default btn-lg" onclick="sendComet('notHere')" id="notHereButton" title="No llegó paciente" style="color: red;"><span class="glyphicon glyphicon-remove-circle"></span></button>
                                        
                                        </div>
                                        <div class="col-md-2">
                                        <button type="button" class="btn btn-default btn-lg" onclick="sendComet('finished')" id="finishedButton" title="Finalizar Atención" ><span class="glyphicon glyphicon-thumbs-up"></span></button>
                                            
                                        </div>
                                        <div class="col-md-2">
                                        <button type="button" class="btn btn-default btn-lg" onclick="sendComet('redirect')" id="redirectButton" title="Derivar"><span class="glyphicon glyphicon-share"></span></button>
                                            
                                        </div>
                                        <div class="col-md-2">
                                        <button type="button" class="btn btn-default btn-lg" onclick="refreshTable()" id="redirectButton" title="Rechargar Tabla"><span class="glyphicon glyphicon-refresh"></span></button>
                                            
                                        </div>
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

//se extrae la modalidad , el ultimo numero y se rellena la tabla


/////////////////////EVENTOS//////////////////////////////////

$(document).ready(function() {
    submodule=decodeURIComponent("<?php echo rawurlencode($_GET['id']); ?>");
    if(submodule != ""){
        $.post('phps/getModuleType.php', { submodule: submodule}, function(data, textStatus, xhr) {
            subModuleType=data;
            if(data != 12){
                $("#contentTicket").html('<th class="text-center">Numero de atencion</th><th class="text-center">RUT</th><th class="text-center">Hora Retiro de Ticket</th><th class="text-center">Tiempo de espera</th>')
            }else{
                $("#contentTicket").html('<th class="text-center">Numero de atencion</th><th class="text-center">RUT</th><th class="text-center">Motivo</th><th class="text-center">Hora Retiro de Ticket</th><th class="text-center">Tiempo de espera</th>')    
            }

            var dataModality = JSON.parse(getModule(submodule));
            moduleInCourse=dataModality.modalityId;
            $("#modalityTitle").text(dataModality['modalityName']);
            $("#ModuleHeader").text(dataModality['subModuleName']);
            initNumber = dataModality['modalityTicket'];
            refreshTable();
            setCurrentNumber();

        });

        
    }else{
        alert("Falta Modalidad!");
    }

});

function closeWindow(){ 
    window.open('','_self',''); 
    window.close(); 
} 

$(window).on('beforeunload', function(e) {
    return 'Se cerrara su sesion';
});
$(window).on('unload', function(e) {
    
    return inactiveSubModule('logout');
});

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

}


function inactiveSubModule(typeButton){//Desactiva el submódulo y genera log de cierre de sesión
    var actionType = 'inactivo'
    if(typeButton=='pause'){ 
        actionType='pausado';
        $('.panel-primary').removeClass('panel-primary').addClass('panel-info');
        $('#pause').html('<span class="glyphicon glyphicon-play"></span> REANUDAR ATENCIÓN');
        $('#pause').attr('onclick', 'inactiveSubModule("replay")');
        activeButtons('pause');
        $('#content').text('En Pausa');
    }
    if(typeButton=='replay'){ 
        actionType='re-activo';
        $('.panel-info').removeClass('panel-info').addClass('panel-primary');
        $('#pause').html('<span class="glyphicon glyphicon-pause"></span> PAUSAR ATENCIÓN');
        $('#pause').attr('onclick', 'inactiveSubModule("pause")');
        refreshTable();
        $('#content').text('Esperando...');
    }
    $.post('phps/activeSubModule.php', {type: actionType, user: "<?php echo $_SESSION['UserId']; ?>", submodule: submodule}, function(data, textStatus, xhr) {
        $.ajax({
            url: '../../../visor/comet/backend.php',
            type: 'GET',
            dataType: 'default',
            data: {msg: data},
        });
        if(typeButton=='logout'){
            $(location).attr('href','../../exit.php');
        }else if(typeButton=='change'){
            $(location).attr('href','selector.php');
        }
    });
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
                if(subModuleType != 12){
                    if(i==0){
                        $('#contentTicket').append('<tr class="info"><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td><td>'+hourDiff(ticketsTable[i]['datetime'])+'</td></tr>');
                    }else{
                        $('#contentTicket').append('<tr><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td><td>'+hourDiff(ticketsTable[i]['datetime'])+'</td></tr>');  
                    }    
                }else{
                    if(i==0){
                        $('#contentTicket').append('<tr class="info"><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['name']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td><td>'+hourDiff(ticketsTable[i]['datetime'])+'</td></tr>');
                    }else{
                        $('#contentTicket').append('<tr><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['name']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td><td>'+hourDiff(ticketsTable[i]['datetime'])+'</td></tr>');  
                    } 
                  
                }
     
            }

            $('#contentTicket').fadeIn('slow');
            if(myState==false){
                activeButtons('next');
            }
        });
    }
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
                checkComet(data);
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
    }
    if(type==='notHere'){
        myState = false;
        insertLog('Ticket Ausente','lb','module','no_serve',ticketAttention);
        $('#content').css('color','black');
        $('#patientPicture').html('');
        $('#patientData').html('');
    }
    if(type==='finished'){
        myState = false;
        insertLog('Ticket Finalizado','lb','module','served',ticketAttention);
        $('#content').css('color','black');
        $('#patientPicture').html('');
        $('#patientData').html('');
    }
    if(type==='redirect'){
        getActivesModules();
        $('#modalDerived').modal('show');
    }
    if(type==='exception'){
        $('#modalException').modal('show');
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
}


///////////////DISEÑO, OBJETOS VISIBLES, OTROS///////////////////////////
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
        $('#notHereButton').attr('disabled', true);
        $('#finishedButton').attr('disabled', true);
        $('#redirectButton').attr('disabled', true);
        $('#exceptionButton').attr('disabled', false);
        $('#plusDerivedButton').attr('disabled', true);
        $('.getout').attr('disabled', false);
    }

    if(type=='on_serve'){
        $('#plusButton').attr('disabled', true);
        $('#minusButton').attr('disabled', true);
        $('#notHereButton').attr('disabled', false);
        $('#finishedButton').attr('disabled', false);
        $('#redirectButton').attr('disabled', false);
        $('#exceptionButton').attr('disabled', true);
        $('#plusDerivedButton').attr('disabled', true);
        $('.getout').attr('disabled', true);
    }

    if(type=='pause'){
        $('#plusButton').attr('disabled', true);
        $('#minusButton').attr('disabled', true);
        $('#notHereButton').attr('disabled', true);
        $('#finishedButton').attr('disabled', true);
        $('#redirectButton').attr('disabled', true);
        $('#exceptionButton').attr('disabled', true);
        $('#plusDerivedButton').attr('disabled', true);
        $('.getout').attr('disabled', false);
    }

}

function hourDiff(initialHour){
    var initialHour = new Date(initialHour);
    var finishedHour = new Date();
    finishedHour.setSeconds(finishedHour.getSeconds() + 15);

    
    if (finishedHour < initialHour) {
        finishedHour.setDate(finishedHour.getDate() + 1);
    }
    var diff = finishedHour - initialHour;

    return Math.floor(diff / 1000 / 60)+' Minutos';
}

//so doge, wow, much code
/*$("#patientPicture").hover(function() {
    $("#patientPicture").html('<img src="http://i0.kym-cdn.com/entries/icons/original/000/014/285/not.jpg">');
}, function() {
  $("#patientPicture").html('<img src="http://placehold.it/200x200">');
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