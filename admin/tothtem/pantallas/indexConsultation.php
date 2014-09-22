<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php"); header('Content-Type: text/html; charset=utf8');  }

include ('phps/findRole.php');
if(!findRole("medical_consultation","show_consultation")){
    echo '<script>alert("No tiene permisos para ingresar a esta consulta");
        window.history.back();
    </script>';
}
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
                <h1 class="page-header">Consulta Médica:
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
                 <div class="panel panel-primary">
                    <div class="panel-heading"><span class="glyphicon glyphicon-list"></span> Panel De Control Pacientes en curso</div>
                    <div class="panel-body" align="center">
                        <div class="row" >
                            <label>Datos Paciente</label><br>
                            <label id="content" style="font-size:70px;"></label>
                            <div class="row">
                                <div id="patientData">
                                    <table>
                                        <tr>
                                            <td>RUT:</td>
                                            <td>&nbsp;&nbsp;</td>
                                            <td id="patientRut"></td>
                                        </tr>
                                        <tr>
                                            <td>Nombre:</td>
                                            <td>&nbsp;&nbsp;</td>
                                            <td id="patientName"></td>
                                        </tr>
                                        <tr>
                                            <td>Hora Agenda:</td>
                                            <td>&nbsp;&nbsp;</td>
                                            <td id="patientWait"></td>
                                        </tr>
                                        <tr>
                                            <td>Hora Inicio Atención:</td>
                                            <td>&nbsp;&nbsp;</td>
                                            <td id="patientInit"></td>
                                        </tr>                                                                                                                        
                                    </table>
                                </div>
                                <br/>
                                <div id="buttons" class="text-center">
                                    <div class="col-md-1"></div>

                                    <!--<div class="col-md-1">
                                        <button type="button" class="btn btn-default btn-lg" onclick="sendComet('minus')" id="minusButton" title="Volver a llamar número anterior"><span class="glyphicon glyphicon-minus"></span></button>
                                    </div>-->

                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-default btn-lg" onclick="sendComet('plus')" id="plusButton" title="Llamar paciente"><span class="glyphicon glyphicon-plus"></span></button>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-default btn-lg" onclick="sendComet('notHere')" id="notHereButton" title="No llegó paciente" style="color: red;"><span class="glyphicon glyphicon-remove-circle"></span></button>   
                                    </div>

                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-default btn-lg" onclick="sendComet('finished')" id="finishedButton" title="Finalizar Atención"><span class="glyphicon glyphicon-thumbs-up"></span></button>
                                    </div>

                                    <div class="col-md-4">
                                        <input id="plantto" type="checkbox"> Plan de Tratamiento
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

                
                    </div>
                  
                </div>
            </div>
            <div class="col-md-6">

                <div class="panel panel-primary">
                    <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Panel De Control Pacientes en curso</div>
                        <div class="panel-body" align="center">
                            <table id="contentTicket" class="table table-striped" >
                                <th>Hora Agendada</th><th>RUT</th><th>Nombre</th>
                            </table>
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
var userId = "<?php echo $_SESSION['UserId']; ?>";

//se extrae la modalidad , el ultimo numero y se rellena la tabla
$(document).ready(function() {
    submodule=decodeURIComponent("<?php echo rawurlencode($_GET['id']); ?>");
    if(submodule != ""){
        var dataModality = JSON.parse(getModule(submodule));
        moduleInCourse=dataModality.modalityId;
        $("#modalityTitle").text(dataModality['subModuleName']);
        initNumber = dataModality['modalityTicket'];
        refreshTable();
        setCurrentNumber();
    }else{
        alert("Falta Modalidad!");
    }
//$(window).on('beforeunload', function(){ alert ('Bye now')});
});


function inactiveSubModule(typeButton){//Desactiva el submódulo y genera log de cierre de sesión
    $.post('phps/activeSubModule.php', {type: 'inactivo', user: userId, submodule: submodule}, function(data, textStatus, xhr) {
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
            /*$('#content').fadeOut("slow",function(){
                $('#content').fadeIn("fast");
                $('#content').text(number);
                $('#patientRut').text(jsonData[0].rut);
                
                $('#patientWait').text(jsonData[0].waitingDatetime);
                $('#patientInit').text(jsonData[0].datetime);

                $.post('phps/getPatientName.php', {rut: jsonData[0].rut}, function(data, textStatus, xhr) {
                    var dataJson = JSON.parse(data);
                    namePatient = dataJson[0]['name']+' '+dataJson[0]['lastname'];
                    $('#patientName').text(namePatient);
                });
            });*/
            $('#patientData').fadeOut("slow",function(){
                $('#content').text('');
                $('#patientData').fadeIn("slow");
                $('#patientRut').text(jsonData[0].rut);
                
                $('#patientWait').text(jsonData[0].waitingDatetime);
                $('#patientInit').text(jsonData[0].datetime);

                $.post('phps/getPatientName.php', {rut: jsonData[0].rut}, function(data, textStatus, xhr) {
                    var dataJson = JSON.parse(data);
                    namePatient = dataJson[0]['name']+' '+dataJson[0]['lastname'];
                    $('#patientName').text(namePatient);
                });
            });
            activeButtons(jsonData[0].attention);
            ticketAttention = jsonData[0].ticketid;
        }else{
            $('#patientData').fadeOut("slow",function(){
                //$('#patientData').fadeIn("slow");
                $('#content').text('Standby');
                $('#content').fadeIn("slow");
                $('#patientRut').text('');
                $('#patientName').text('');
                $('#patientWait').text('');
                $('#patientInit').text('');
            });

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
        //console.log(e);
    })
    .fail(function(e) {
        //console.log(e);
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
        myState = true;
        activeButtons('on_serve');
        ticketAttention = firstTicketId;
        insertLog('Inicio de atención paciente','in','module','on_serve',ticketAttention);
    }
    if(type==='notHere'){
        myState = false;
        insertLog('Paciente Ausente','lb','module','no_serve',ticketAttention);
        $('#content').css('color','black');
    }
    if(type==='finished'){
        myState = false;
        //activeButtons('call');
        if($('#plantto').prop('checked')==true){
            insertLog('Atención Finalizada - Plan de Tratamiento','lp','module','served',ticketAttention);
        }else{
            insertLog('Atención Finalizada','lb','module','served',ticketAttention);
        }
        $('#content').css('color','black');
    }
    if(type==='exception'){
        $('#modalException').modal('show');
    }

}


function refreshTable(){ //Actualiza la tabla de pacientes en espera
    var totalResult = getLast5Tickets(submodule,initNumber);
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
                        //$('#contentTicket').append('<tr class="info"><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td></tr>');
                        $('#contentTicket').append('<tr class="info"><td>'+ticketsTable[i]['datetime']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['patient_name']+'</td></tr>');
                    }else{
                        //$('#contentTicket').append('<tr><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td>  </tr></tr>');  
                        $('#contentTicket').append('<tr><td>'+ticketsTable[i]['datetime']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['patient_name']+'</td></tr>');
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
    //var scriptUrl = "phps/getMedicalWaiting.php?medical="+userId;
    var scriptUrl = "phps/getMedicalWaiting.php?medical=16&submodule="+idModule;
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
        $('.getout').attr('disabled', false);
    }
    if(type=='next'){
        $('#plusButton').attr('disabled', false);
        $('#minusButton').attr('disabled', false);
        $('#notHereButton').attr('disabled', true);
        $('#finishedButton').attr('disabled', true);
        $('.getout').attr('disabled', false);
    }

    if(type=='on_serve'){
        $('#plusButton').attr('disabled', true);
        $('#minusButton').attr('disabled', true);
        $('#notHereButton').attr('disabled', false);
        $('#finishedButton').attr('disabled', false);
        $('.getout').attr('disabled', true);
    }

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
        $("#menuButtons").append('<div class="modal-body"><button type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;"" class="btn btn-primary" onclick="derive('+jsonModules[i]['id']+');"><span class="glyphicon glyphicon-time"></span> '+jsonModules[i]['moduleName'] +'</button>   </div>' );
    };

}

</script>
</body>
</html>