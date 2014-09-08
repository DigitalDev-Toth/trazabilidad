
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Trazabilidad</title>
    <link href="admin/tothtem/tothtem/css/bootstrap.css" rel="stylesheet">
    <link href="admin/tothtem/tothtem/css/stylish-portfolio.css" rel="stylesheet">
    <link href="admin/tothtem/tothtem/css/loader.css" rel="stylesheet">
    <link href="admin/tothtem/tothtem/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <script src="admin/tothtem/tothtem/js/jquery-2.0.3.js" type="text/javascript"></script>
    <script src="admin/tothtem/tothtem/js/validarut.js" type="text/javascript"></script>
    <script src="admin/tothtem/tothtem/js/bootbox.js"></script>
    <script src="admin/tothtem/tothtem/js/bootstrap.js"></script>

<style type="text/css">
body {
    overflow:hidden;
    -webkit-user-select: none;
    -moz-user-select: -moz-none;
    -ms-user-select: none;
    user-select: none;
       
}
.modal .modal-body {
    max-height: 300px;
    overflow-y: auto;
}
</style>
<!-- cursor: none;-->
</head>

<body>

<a href="#menu" id="_menu" style="display:block;"></a>
<div style="text-align: center;">
    <img src="admin/tothtem/tothtem/img/logoFalp.png" style="width: 400px; text-align: center;">
    <br/>
    <br/>
    <span>Sistema de Trazabilidad de Pacientes</span>
    <span>Versión 1.0b</span>
</div>

<br/>
<!-- Menu paciente -->
<div id="menu" class="header">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-content">

                <div id="menuButtons">
                    <div class="modal-body" style="text-align: center;">
                    	<button id='totem' type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;" class="btn btn-primary" title="Tótem para retiro de número de atención, Zona 1">
                    		<span class="glyphicon glyphicon-list-alt"></span> Tótem
                		</button> 
                  	</div>
                  	<div class="modal-body" style="text-align: center;">
                    	<button id="admin" type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 500px;" class="btn btn-primary" title="Ingreso a opciones de módulos para administrador, o movimiento de números para usuario">
                    		<span class="glyphicon glyphicon-calendar"></span> Gestión o pantalla de submódulo
                		</button> 
                  	</div>
                    <!--
                  	<div class="modal-body" style="text-align: center;">
                    	<button id="visor" type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;" class="btn btn-primary" title="Visualización de movimiento de pacientes en Zona 1">
                    		<span class="glyphicon glyphicon-eye-open"></span> Visualizador
                		</button> 
                  	</div>
                    -->
                </div>
                <br>
               
            </div>         
        </div>
    </div>
</div>
<!-- fin Menu paciente -->

<script type="text/javascript">
//globals
var maxMax=60000;
var maxMin=20000;
var init=0;
var logIN=0;
var click=0;
var cantidad=0;
var maxtime=maxMin;
var modulesOk=0;
var tothemIp='';

//***********************************************************

$(document).click(function(e) { 
    if (e.button == 0 && click==0) {
        $("#start").css({
        display:"block"
    });
        click=1;
    }
    

});
var totemId="";
$(document).ready(function() {
	$("#totem").click(function() {
      	window.open("admin/tothtem/tothtem/index.php", '_blank');
      	return false;
   	});
   	$("#admin").click(function() {
      	window.open("admin/login.php", '_blank');
      	return false;
   	});
	$("#visor").click(function() {
      	window.open("visor/index.php", '_blank');
      	return false;
   	});
	
});

$("#menu-close").click(function(e) {
    e.preventDefault();
    $("#sidebar-wrapper").toggleClass("active");
});
$("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#sidebar-wrapper").toggleClass("active");
});
$(function() {
    $('a[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                scrollTop: target.offset().top
                 }, 500);
                return false;
            }
        }
    });
});

//**************************************

//resetea variables para cuando sale el usuario
function resetInput(ind){
    $( "#login" ).animate({fontSize: "18px"}, {queue: false,duration: 500}).prop( "disabled", true );
    logIN=0;
    init=0;
    normalLogin();
    $('#loadingLabel').fadeOut('slow');
    $('#loadingLogo').fadeOut('slow');
    $('#rut').attr("disabled", false);
    $('#patientName').text('');
    $("#inputColor").removeClass();
    $("#inputIcon").removeClass();
    $("#inputColor").addClass("form-group has-warning has-feedback");
    $("#inputIcon").addClass("glyphicon glyphicon-user form-control-feedback");
    if(ind){
        $("#inputIcon").fadeOut("slow");
        $("#inputIcon").fadeIn("slow");
    }
}
//comprueba si el rut es correcto en el input
function Nentradas() {
    var digito=this.value;
    //document.getElementById('rut').value +=this.value;
    resetInput(0);
    cantidad++;
    if(digito!="-" || digito!="."){
        document.getElementById('rut').value +=this.value;
        //var rut = $("#rut").val();
        //cantidad++;
        if(cantidad==9 || cantidad==8 || cantidad==11 || cantidad==10){
            var rutC=verRut($("#rut").val().toUpperCase(),0);
            $("#inputColor").removeClass();
            $("#inputIcon").removeClass();
            $("#inputIcon").fadeOut("slow");
            if(rutC==true){
                document.getElementById('rut').value = verRut($("#rut").val(),1).toUpperCase();
                $("#inputColor").addClass("form-group has-success has-feedback");
                $("#inputIcon").addClass("glyphicon glyphicon-ok form-control-feedback");
                $("#login").animate({fontSize: "26px"}, {queue: false,duration: 500}).prop( "disabled", false );
            }else{
                $("#inputColor").addClass("form-group has-error has-feedback");
                $("#inputIcon").addClass("glyphicon glyphicon-remove form-control-feedback");
                $("#login").animate({fontSize: "18px"}, {queue: false,duration: 500}).prop( "disabled", true );
            }
            $("#inputIcon").fadeIn("slow");
        }
    }
}
function deleteString(){
    var rutVar=$("#rut").val().toUpperCase();
    rutVar = rutVar.substring(0, rutVar.length - 1);
    $("#rut").val(rutVar);
    cantidad--;
    $("#login").animate({fontSize: "18px"}, {queue: false,duration: 500}).prop( "disabled", true );
}
//foco al input
function foco(){
	//habilita menu salir
    $("#menu-toggle").fadeIn(1000);
}


//mensaje de bienvenida
function Welcome(){
    var now=new Date();
    var hours=now.getHours();
    if(hours>=0 && hours<=11){
        return "Buenos Dias";
    }else{
        if(hours>=12 && hours<=19){
            return "Buenas Tardes";  
        }else{
            return "Buenas Noches";
        }
    }
}

//animacion menu principal
function swap(){
    $("#labelStart").fadeOut('slow', function() {
      $("#labelStart").fadeIn('slow');
    });
    $( "#glypHand" ).animate({fontSize: "26px"}, {queue: true,duration: 1500});
    $( "#glypHand" ).animate({fontSize: "16px"}, {queue: true,duration: 1500});
    window.setTimeout(function() { swap() }, 4000);
}

function SearchOnLogin(datos){
	$("#MyExams").css({display:"none"});
	/*
    $("#MyExams").css({display:"block"});
    var dataJson =null; 
    dataJson=JSON.parse(datos);
    var datos=null;
    var dateT=new Date();
    var mes=dateT.getMonth()+1;
    var dia=dateT.getDate();
    var anio=dateT.getFullYear();
    if (mes < 10) { mes = '0' + mes; }
    if (dia < 10) { dia = '0' + dia; }
    var FechaF=anio+"-"+mes+"-"+dia;
    var msg=dia+"-"+mes+"-"+anio;
    var exams=[];
    var j=0;
    $('#WelcomeLabel').text(Welcome()+" "+dataJson[0]['name'].toUpperCase());
    $('#patientName').text(dataJson[0]['rut']);

    */
    //FALP
    //solo mostrar el rut , nombre ficticio
    $('#WelcomeLabel').text(Welcome()+" (return patientName_webservice)");
    $('#patientName').text($("#rut").val().toUpperCase());

    /*
    for(var i in dataJson){
        datos += dataJson[i];
        console.log(dataJson[i]['date_c']);
        if(FechaF==dataJson[i]['date_c']){
            console.log("AGENDAMIENTO!");
            Agendamientos(msg,dataJson[i]);
        }else{
            var dateExam=new Date(dataJson[i]['date_c']);
            var month=dateExam.getMonth()+1;
            var day=dateExam.getDate()+1;
            var year=dateExam.getFullYear();
            if(month>=mes && day>dia && year>=anio){
                 if (month < 10) { month = '0' + month;}
                 if (day < 10) { day = '0' + day; }
                 alerts("<b>Recuerde que tiene examen de </b>'"+dataJson[i]['exam_name']+"' <b>para el dia "+ day+"-"+month+"-"+year+"</b>",0);
            }
        }
        if(dataJson[i]['state']=='validado'){
            //examenes sin despachar
            console.log("examen "+j+" -> "+dataJson[i]['state']);
            exams[j]=i;
            j++;
        }
    }
    if(j>=1){
        var messages="<b>Tiene "+j+" examenes para despachar:</b><br>";
        var historys=[];
        for(var i=0;i<j;i++){
            messages+=("<b>"+i+1+"</b>~"+dataJson[exams[i]]['exam_name']+" <b>con fecha</b> "+dataJson[exams[i]]['date_c']+"<br>");
            var data=getHistoryReport(dataJson[exams[i]]['id']);
            historys[i]=data;
        }
        // dataJson[exam[j]]['exam'];
        printHistory(messages+"\n <b>¿Desea Imprimirlos todos?</b>",historys);
    }
    */

}

//cambia las animaciones del login
function changeLogin(){
    $('#buttonsGrid').fadeOut('slow');
    $('#inputText').fadeOut('slow');
    $('#bigText').fadeOut('slow', function() {
        $('#loadingLogo').fadeIn('slow');
        $('#bigText').text('Verificando Rut...');
        $('#bigText').fadeIn('slow');
    });
    window.setTimeout(function(){
        $('#loadingLogo').fadeOut('slow');
        $('#bigText').fadeOut('slow'); 
        $('#rut').attr("disabled", true);
         loginPatient();
    }, 3000);
}
//normaliza las animaciones del login
function normalLogin(){
    $('#buttonsGrid').fadeIn('slow');
    $('#inputText').fadeIn('slow');
    $('#bigText').fadeIn('slow', function() {
        $('#loadingLogo').fadeOut('slow');
        $('#bigText').text('Ingrese Su Rut');
        $('#bigText').fadeIn('slow');
    });
    window.setTimeout(function(){
        $('#bigText').fadeIn('slow'); 
        $('#rut').attr("disabled", false);
    }, 100);
}

//alertas en general , (1.- si /no) (2.-evaluacion) (3.- si/No para imprimir)
function alerts(messages , type){
    if(type==2){
       bootbox.dialog({
            message: "¿Como ha sido su atencion en el examen de:<br>"+messages+"?",
            title: "Gracias por su visita",
            buttons: {
                success: {
                    label: "Buena",
                    className: "btn-success",
                    callback: function() {
                        //return
                    }
                },
                main: {
                    label: "media",
                    className: "btn-primary",
                    callback: function() {
                        //return
                    }
                },
                danger: {
                    label: "mala",
                    className: "btn-danger",
                    callback: function() {
                        //return
                    }
                }
            }
        });
    }else{
        if(type==1){
            bootbox.dialog({
            message: messages,
            title: "Atencion",
            buttons: {
                success: {
                label: "Si",
                className: "btn-success",
                callback: function() {
                    console.log("si");
                    }
                },
            main: {
                label: "No",
                className: "btn-primary",
                callback: function() {
                    console.log("No");
                }
            }
            }
            });
        }else{
            if(type==3){
                console.log("ok 3");
                bootbox.dialog({
                    message: messages,
                    title: "Atencion",
                    buttons: {
                        success: {
                        label: "Si",
                        className: "btn-success",
                        callback: function() {
                            PrintTicket();
                            }
                        },
                    main: {
                        label: "No",
                        className: "btn-primary",
                        callback: function() {
                            console.log("No");
                        }
                    }
                }
            });
            }else{
                bootbox.alert(messages, function() {
                    console.log("Ok");
                });
            }
        }
    }
}

function getHistoryReport(idCalendar){
    var result = null;
    var scriptUrl = "scripts/findHistory.php?idCalendar=" + idCalendar;
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
function getMd5Id(rutP){
    var result = null;
    var scriptUrl = "scripts/getMd5.php?rut=" + rutP;
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
//inicio del timer
function startTimer() {
    timeoutID = window.setTimeout(goInactive, maxtime);
}
 //reset del timer
function resetTimer(e) {
    window.clearTimeout(timeoutID);
    goActive();
}
//cuando pasa a inactivo
function goInactive() {
    resetInput(1);
    //$("#menu-toggle").css({display:"none"});
    $( "#menu-toggle" ).fadeOut( 1000 );
    document.getElementById('rut').value ="";
    //pasa a inactivo
    bootbox.hideAll();
    cantidad=0;
    maxtime=maxMin;
    document.getElementById('wait').click();
}
 //activo
function goActive() {
    startTimer();
}

</script>
   

</body>

</html>
