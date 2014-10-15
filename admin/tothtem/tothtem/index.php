
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>TothTem</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/stylish-portfolio.css" rel="stylesheet">
    <link href="css/loader.css" rel="stylesheet">
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <script src="js/jquery-2.0.3.js" type="text/javascript"></script>
    <script src="js/validarut.js" type="text/javascript"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrap.js"></script>

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

<a href="#espera" id="wait" style="display:block;"></a>
<a href="#menu" id="_menu" style="display:block;"></a>
<a id="menu-toggle" href="#" class="btn btn-primary btn-lg toggle" onclick="goInactive();"><span class="glyphicon glyphicon-remove"></span> Salir</i></a>

<!-- pantalla principal-->
<div id="espera" class="header">
<img src="img/logoToth.png" style="position: fixed;width: 200px;opacity: 0.2;">
    <div class="vert-text" align="center">

        <img src="img/logoFalp.png" style="width: 400px;">
        <br>

        <!--<h1>TothTem</h1>-->
        <div id="start" style="display:block;">
           
           <h3>
            <a href="#login-menu" onclick="initMenu();" id="startB" class="btn btn-default btn-lg" style="display:none">Toque para comenzar<br>
            <span class="glyphicon glyphicon-hand-up"></span></a></h3>
           
            <div id="enableTothtem" >
                <label id="labelStart" style="font-size:22pt;">  Seleccione una opcion para comenzar</label>    
            </div>
            <div id="disableTothtem" >
               <label style="font-size:22pt;">  <span class="glyphicon glyphicon-ban-circle"></span>  FUERA DE SERVICIO</label>    
            </div>
            <br>

               <a id="rutOption" href="#login-menu" type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;" class="btn btn-primary"  onclick="initMenu(1);">
            	<span class="glyphicon glyphicon-home"></span> Rut Normal
            </a> 

            <br><br>

            <a id="dniOption" type="button" href="#login-menu" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;" class="btn btn-primary" onclick="initMenu(2);">
            	<span class="glyphicon glyphicon-globe"></span> DNI Extranjeros
            </a> 

            

        </div>
    </div>
</div>
<!-- fin pantalla principal-->
<!-- Login rut -->
<div id="login-menu" class="header">



    <div class="vert-text" id="OnlyRut">
    <h1 id="bigText">Ingrese Su Rut</h1>
		<div id="loadingLogo" class="loader">
		    <span></span> <span></span> <span></span> <span></span>
		</div>
	             
		<div id="inputText">    
			<form class="form-inline" role="form">
				<div class="" id="inputColor">
					<label class="control-label" id="inputLabel" for="rut"></label>
					<input type="text" class="form-control" name="rut" id="rut" placeholder="Ej.12.345.678-9" style="margin: 0 auto;text-align:center;"  maxlength="9"  />
					<span class="" id="inputIcon"></span><br>
					<label id="loadingLabel">Verificando Rut...</label>
				</div>
			</form>   
		</div>

		<div id="buttonsGrid">
			<div  style="width:155px;margin: 0 auto" align="center">
			    <div id="row1">
				    <input type="button" name="number" value="1" id="_1" class="btn btn-default btn-lg" onclick="teclado('number')"/>
				    <input type="button" name="number" value="2" id="_2" class="btn btn-default btn-lg" onclick="teclado('number')"/>
				    <input type="button" name="number" value="3" id="_3" class="btn btn-default btn-lg" onclick="teclado('number')"/>
			    </div>
			    <div id="row2">
				    <input type="button" name="number" value="4" id="_4" class="btn btn-default btn-lg" onclick="teclado('number')"/>
				    <input type="button" name="number" value="5" id="_5" class="btn btn-default btn-lg" onclick="teclado('number')"/>
				    <input type="button" name="number" value="6" id="_6" class="btn btn-default btn-lg" onclick="teclado('number')">
			    </div>
			    <div id="row3">
				    <input type="button" name="number" value="7" id="_7" class="btn btn-default btn-lg" onclick="teclado('number')">
				    <input type="button" name="number" value="8" id="_8" class="btn btn-default btn-lg" onclick="teclado('number')">
				    <input type="button" name="number" value="9" id="_9" class="btn btn-default btn-lg" onclick="teclado('number')">
			    </div>
			    <div id="row4">
				    <input type="button" name="number" value="0" id="_0" class="btn btn-default btn-lg" onclick="teclado('number')"/>
				    <input type="button" name="number" value="K" id="ka" class="btn btn-default btn-lg" onclick="teclado('number')" style="width: 44px;"/>
                    <button class="btn btn-default btn-lg" onclick="deleteString()" style="width: 44px;"><span class="glyphicon glyphicon-arrow-left"></span> </button>
                    <!--<button class="btn btn-default btn-lg" onclick="deleteString()"><span class="glyphicon glyphicon-arrow-left"></span> </button>-->
				    <input type="button" id="Borrar" value="Borrar"class="btn btn-default btn-lg" >
			    </div>
			</div><br>
	    <button class="btn btn-default btn-lg" id="login">Aceptar</button>
		</div>
	</div>




</div>

<!-- fin Login rut -->

<!-- Menu paciente -->
<div id="menu" class="header">
    <div class="vert-text"><h2><label id="WelcomeLabel"></label></h2>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                        <label id="patientName"></label>
                        </h4>
                    </div>

                    <div id="menuButtons">
                        
                    </div>


                  


                      <!--
                    <div class="modal-body">
                        <button type="button" style='padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;' class="btn btn-primary" onclick=""><span class="glyphicon glyphicon-time"></span> Ticket Informaciones</button>
                    </div>
                    <div class="modal-body">
                        <button type="button" style='padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;' class="btn btn-primary" onclick=""><span class="glyphicon glyphicon-time"></span> Ticket Pago</button>
                    </div>



                    <div class="modal-body" >
                        <a href="#about"><button type="button" style='padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;' class="btn btn-primary" onclick="showInfo();"><span class="glyphicon glyphicon-info-sign"></span> Informaciones</button></a>
                    </div>
                    
                    
                    <div id="MyExams" class="modal-body">
                        <a href="#about"><button type="button" style='padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;' class="btn btn-primary" onclick="showMyExam();"><span class="glyphicon glyphicon-list-alt"></span> Mis Examenes</button></a>
                    </div>
                    -->
                   
                    <br>
                   
                </div>         
            </div>
        </div>
    </div>
</div>
<!-- fin Menu paciente -->
<!-- menu informaciones
<div id="about" class="header">
    <div class="vert-text"><h2><label id="WelcomeLabel"></label></h2>
        <div class="modal-dialog">
                <div class="modal-content">-->
                    <!--<div class="modal-header">
                        <h4 class="modal-title"><label id="patientName"></label></h4>
                    </div>-->
                    <!--
                    <div id="showMyExams">
                        <div class="modal-body" style="max-height: 420px;    overflow-y: auto;">
                            <div id="AllMyExams">   
                           
                            </div>                               
                        </div>
                    </div>

                    <div id="showInfo">
                        <div class="modal-body">
                            <a href="#about"><button type="button" style='padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;' class="btn btn-primary">Precios</button></a>
                        </div>
                        <div class="modal-body">
                            <a href="#about"><button type="button" style='padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;' class="btn btn-primary">Examenes</button></a>
                        </div>
                        <div class="modal-body">
                            <a href="#about"><button type="button" style='padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;' class="btn btn-primary">Contacto</button></a>
                        </div>
              
                    </div>
                    <br>
                    <div class="modal-body">
                            <a href="#menu"><button type="button" style='padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;' class="btn btn-info"><span class="glyphicon glyphicon-arrow-left"></span> Volver</button></a>
                    </div>
                </div>         
        </div>

    </div>
</div>
-->
<iframe src="" id="printIframe"></iframe>


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
var selAttention=false;
var getAccept=false;//Consulta si presionó botón aceptar
var inputTypeT='';
setup();

//***********************************************************
$("#espera").click(function(event) {
    if(modulesOk==1){
        //$( "#startB" ).click();
    }else{
        bootbox.alert("<span class='glyphicon glyphicon-warning-sign'></span> Tothtem Fuera De Servicio <br><br> Favor de consultar otro ToThtem", function() {
        window.setTimeout(setNull, 5000);
        });
    }
    
});

function initMenu(idType){
	$("#menu-toggle").fadeIn(1000);
	if(idType==1){
		$('#ka').show();
		$('#bigText').text('Ingrese Su Rut');
		inputTypeT=1;
	}else{
		$('#ka').hide();
		$('#bigText').text('Ingrese Su DNI');
		$("#login").animate({fontSize: "26px"}, {queue: false,duration: 0}).prop( "disabled", false );
		inputTypeT=2;

	}
	$("#OnlyRut").show('fast');
}



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
	//establece id totem
    activesModules();
    resetInput(1);
    $("#login").prop( "disabled", true );
    $("#inputColor").addClass("form-group has-warning has-feedback");
    $("#inputIcon").addClass("glyphicon glyphicon-user form-control-feedback");
    $("#menu-toggle").hide();
    teclado('number');
    swap();
});

$("#login").click(function() {
    changeLogin();
});

$("#Borrar").click(function() {
	document.getElementById('rut').value ="";
	if(inputTypeT!=2){
		cantidad=0;
   		resetInput(1);
	}
    $("#rut").focus();

});

$(window).keyup(function(event){
    if((event.keyCode>=48 && event.keyCode<=57) || event.keyCode==75){ 
        init++;
        if(init==8 || init ==9){
            var rutBoolean=verRut($("#rut").val().toUpperCase(),0);
            if(rutBoolean==true && logIN==0){
                logIN=1;
                changeLogin();
            }
        }
    }else{
          event.preventDefault(); 
    }
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


//loop , cambiar con comet 
function activesModules(){
    var results=tothtemConfig();
    if(results==0){
        modulesOk=0;
        $("#disableTothtem").show();
        $("#enableTothtem").hide();
    }else{
        modulesOk=1;
        $("#enableTothtem").show();
        $("#disableTothtem").hide();
    }
    window.setTimeout(function(){
        activesModules();
    }, 20000);
}




//imprime ticket de atencion (TicketOption indica el id del módulo correspondiente; en caso de ser módulo especial, se envía además el id de este)
function PrintTicket(ticketOption,moduleSpecial){
	/*option:
		caja:0
		informaciones :1
		recep:2;
		etc...
	*/
	//if(totemId!=""){


    var urlTicket="scripts/returnTicket.php?rut=";
    var rut= $("#rut").val().toUpperCase();
    //var id="&totemId="+totemId;
    var id="&totemId="+tothemIp;
    var ticketOption="&ticketOption="+ticketOption;
    var moduleSpecial="&moduleSpecial="+moduleSpecial;
    var chain=urlTicket+rut+id+ticketOption+moduleSpecial;

    //imprime el ticket
    document.getElementById("printIframe").src = chain;
    //window.open(urlTicket+rut);
    bootbox.alert("Imprimiendo ticket...", function() {
        window.setTimeout(setNull, 5000);
    });
    $("#BootboxButton").css({display:"none"});
    window.setTimeout(function(){
        bootbox.hideAll();
        $("#BootboxButton").css({display:"display"});
    }, 3000);
    window.setTimeout(function(){
        bootbox.dialog({
                message: "¿Desea otra operacion?",
                title: "Atencion",
                buttons: {
                    success: {
                    label: "Si",
                    className: "btn-success",
                    callback: function() {
                    }
                },
                main: {
                    label: "No",
                    className: "btn-primary",
                    callback: function() {
                        goInactive();
                        }
                    }
                }
            });
    }, 3200);
    /*}else{
    	alert("no existe id totem!!");
    }*/
}

//muestra los examenes menu "mis examenes"
function showMyExam(){
    $( "#AllMyExams" ).empty();
    var rut = $("#rut").val().toUpperCase();
    $("#showMyExams").css({display:"block"});
    $("#showInfo").css({display:"none"});
    document.getElementById('rut').value = rut;
    $.post('scripts/findRut.php', {rut: rut}, function(data, textStatus, xhr) {    
        var url = 'scripts/searchCalendar.php?patient='+data;
        $.ajax({
            type: "POST",
            url:url,
            async: true,
            success: function(datos){
                var dataJson =null; 
                dataJson=JSON.parse(datos);
                var exams=[];
                var j=0;
                for(var i in dataJson){
                    if(dataJson[i]['state']=='validado' || dataJson[i]['state']=='despachado'){
                        //examenes sin despachar
                        exams[j]=i;
                        j++;
                    }
                }
                if(j>=1){
                    var historys=[];
                    var content = "<table class='table table-striped'>";
                    content+= '<tr><th> N </th><th> Examen </th><th>Fecha</th><th>Imprimir</th></tr>';
                    for(var i=0;i<j;i++){
                        var data=getHistoryReport(dataJson[exams[i]]['id']);
                        //content+= '<tr><th>'+(i+1)+'</th><td>' + dataJson[exams[i]]['exam_name'] + '</td><td>' + dataJson[exams[i]]['date_c']+ '</td><td><button onclick='+'printReport("'+data+'")'+'><span class="glyphicon glyphicon-print"></span></button></td><td><button onclick='+'printLinks("'+data+'")'+'><span class="glyphicon glyphicon-qrcode"></span></button></td></tr>';
                        content+= '<tr><th>'+(i+1)+'</th><td>' + dataJson[exams[i]]['exam_name'] + '</td><td>' + dataJson[exams[i]]['date_c']+ '</td><td><button onclick='+'printReport("'+data+'")'+'><span class="glyphicon glyphicon-print"></span></button></td></tr>';
                        historys[i]=data;
                    }
                    //content += "</table><br><strong>Imprimir Direccion Web para ver sus examenes </strong><span class='glyphicon glyphicon-qrcode'></span>";
                    content+= "</table><br><button onclick='printLinks()' type='button' style='padding:12px 20px;font-size: 15px;border-radius: 33px;width: 365px;' class='btn btn-primary'>Imprimir Direccion Web para ver sus examenes <span class='glyphicon glyphicon-print'></span></button>"

                    $('#AllMyExams').append(content);
                    //lista de examnes en exam[i] del json!2
                    // dataJson[exam[j]]['exam'];
                    //printHistory(messages+"\n ¿Desea Imprimirlos?",historys);
                }else{
                    $("#AllMyExams").html('<span><b>No tiene ningun tipo de examen para mostrar</b><br></span>'); 
                }
            },
            error: function (obj, error, objError){
                console.log(error);
            }
        });
    });
}
//imprime el examen 
function printReport(id){
    //var url='http://vibra.bioris.cl/inc/modules/report/viewerTothem.php?history_view='+id;
    var url='http://ns2.toth.cl/es14b/inc/modules/report/viewerTothem.php?history_view='+id;
    document.getElementById("printIframe").src = url;
    window.setTimeout(setNull, 5000);
    bootbox.alert("Imprimiendo Examen...", function() {});
    $("#BootboxButton").css({display:"none"});
    window.setTimeout(function(){
        bootbox.hideAll();
    }, 6000);
}
//imprime el link del examen + qr
function printLinks(){
     //var url='http://vibra.bioris.cl/inc/modules/report/viewerTothem.php?history_view='+id;
    rut = $("#rut").val().toUpperCase();
    var md5=getMd5Id(rut);
    //var url='http://ns2.toth.cl/es14b/inc/modules/report/viewerTothem.php?history_view='+id;
    var url='http://ns2.digitaldev.org/tothtem/examenes/index.php?id='+md5;
    var urlQr='setLink.php?urlQr='+url;
    //window.open(urlQr);
    document.getElementById("printIframe").src = urlQr;
    window.setTimeout(setNull, 5000);
    bootbox.alert("Imprimiendo Su url...", function() {});
    $("#BootboxButton").css({display:"none"});
    window.setTimeout(function(){
        bootbox.hideAll();
    }, 6000);

}
//deja nulo el iframe para imprimir
function setNull(){
    document.getElementById("printIframe").src = "about:blank";
}
//solo muestra informacion ocultando los examenes
function showInfo(){
    $("#showMyExams").css({display:"none"});
    $("#showInfo").css({display:"block"});
}
//registra el numeros del teclado virtual
function teclado(type){
	
	var numeros = document.getElementsByName("number");
	for (var i=0; i < numeros.length; i++) { 
    	numeros[i].onclick=Nentradas;
	}
	
  
    
}



//resetea variables para cuando sale el usuario
function resetInput(ind){
	if(inputTypeT!=2){
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
  
}
//comprueba si el rut es correcto en el input
function Nentradas() {
    var digito=this.value;
    //document.getElementById('rut').value +=this.value;
    resetInput(0);
    if(inputTypeT==2){
    	document.getElementById('rut').value +=this.value;
    }else{
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

}
function deleteString(){
    var rutVar=$("#rut").val().toUpperCase();
    if(inputTypeT!=2){
  	    $("#login").animate({fontSize: "18px"}, {queue: false,duration: 500}).prop( "disabled", true );
    }
    rutVar = rutVar.substring(0, rutVar.length - 1);
    $("#rut").val(rutVar);
    cantidad--;

}




//mensaje de bienvenida
function Welcome(){
    var now=new Date();
    var hours=now.getHours();
    if(hours>=0 && hours<=11){
        return "Buenos Días";
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
//login del paciente , comprueba si el rut es correcto o no
//desabilitado para falp , aun sin examenes
function loginPatient(){

    var rut = $("#rut").val().toUpperCase();
    var descrip='';
    if(verRut(rut,0) || inputTypeT==2){
        getAccept=true;
        //rut valido
        maxtime=maxMax;
        if(inputTypeT!=2){
        	rut=verRut(rut,1);
        	descrip='Ingreso de RUT Totem';
        }else{
        	descrip='Ingreso de DNI Totem';
        }
        
        //Se consulta si el rut ya tiene algún ticket
        $.post('scripts/findLogsRut.php',{ rut: rut}, function(data, textStatus, xhr) {
            if(data==0){
                console.log(tothemIp);
                $.post('scripts/insertLogs.php',{ rut: rut, description: descrip, ip: tothemIp, action: 'in', cometType: 'tothtem' }, function(data, textStatus, xhr) {
                    //BACKEND PARA EL COMET
                    $.post('../../../visor/comet/backend.php',{msg: data},function(data, textStatus, xhr){
                    });
                });


                document.getElementById('rut').value = rut;
                /*
                $.post('scripts/findRut.php', {rut: rut}, function(data, textStatus, xhr) {
                    $('#WelcomeLabel').text(Welcome());
                    if(data ==0) {
                        document.getElementById('_menu').click();
                        $("#MyExams").css({display:"none"});
                    }
                    else{ 
                        var url = 'scripts/searchCalendar.php?patient='+data;
                        $.ajax({
                                type: "POST",
                                url:url,
                                async: true,
                                success: function(datos){
                                    SearchOnLogin(datos);
                                },
                                error: function (obj, error, objError){
                                    console.log(error);
                                }
                        });
                        document.getElementById('_menu').click();
                    }
                });
                */
                //solo falp
                tothtemConfig();
                $("#_menu").click();
                SearchOnLogin("null");
            }else{
                var jsonData = JSON.parse(data);
                bootbox.dialog({
                    message: "Ud ya posee ticket de atención",
                    title: "",
                    buttons: {
                        success: {
                            label: "Re-imprimir Ticket",
                            className: "btn-success",
                            callback: function() {
                                var rut= $("#rut").val().toUpperCase();

                                var chain = "scripts/returnTicketRePrint.php?rut="+rut+"&newticket="+jsonData.ticket;

                                //imprime el ticket
                                document.getElementById("printIframe").src = chain;
                                bootbox.alert("Imprimiendo ticket...", function() {
                                    goInactive();
                                });
                            }
                        },
                        main: {
                            label: "Nuevo Ticket",
                            className: "btn-primary",
                            callback: function() {

                                $.ajax({type :"post",url : "scripts/insertLogs.php",data : "rut="+rut+"&description=Vuelve a sacar ticket Totem&ip="+tothemIp+"&action=lv&cometType=tothtem&ticketid="+jsonData.ticketid,
                                    success:function(data){
                                        /*$.ajax({type :"post",url : "../../../visor/comet/backend.php",data : "msg="+data,//Se envía comet indicando que el paciente ha vuelto al tótem
                                            success:function(data){*/
                                        window.setTimeout(function(){
                                            $.ajax({type :"post",url : "scripts/insertLogs.php",data : "rut="+rut+"&description=Ingreso de RUT Totem&ip="+tothemIp+"&action=in&cometType=tothtem",
                                                success:function(data){
                                                    $.ajax({type :"post",url : "../../../visor/comet/backend.php",data : "msg="+data,//
                                                        success:function(){
                                                            document.getElementById('rut').value = rut;
                                                            tothtemConfig();
                                                            $("#_menu").click();
                                                            SearchOnLogin("null");
                                                            selAttention=false;
                                                        }
                                                    });
                                                }
                                            });
                                        }, 1000);
                                            //}
                                        //});
                                    }
                                });

                            }
                        },
                        danger: {
                            label: "Cancelar",
                            className: "btn-danger",
                            callback: function() {
                                goInactive();
                            }
                        }
                    }
                });
            }
        });

    }
}

function tothtemConfig(){
    //tothemIp="<?php echo $_SERVER['REMOTE_ADDR'];?>";
    tothemIp="<?php echo $_REQUEST['tothtem'];?>";
    //console.log(tothemIp);
    var result = null;
    var scriptUrl = "scripts/tothtemConfig.php?ip=" + tothemIp;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        },
        error: function(data) {
            console.log("error tothtem config");
        }
    });
    if(result!="nan"){
        setTothtemConfig(JSON.parse(result));
        return 1;
    }else{
        return 0;
    }
}

function getActivesModules(){
    //var tothemIp="<?php echo $_SERVER['REMOTE_ADDR'];?>";
    tothemIp="<?php echo $_REQUEST['tothtem'];?>";
    var result = null;
    var scriptUrl = "scripts/getActivesModules.php?ip=" + tothemIp;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        },
        error: function(data) {
            console.log("error tothtem config");
        }
    });

    var jsonModules=JSON.parse(result);

    $("#menuButtons").html('');
    //console.log(jsonModules);

    for (var i = 0; i < jsonModules.length; i++) {
        if(jsonModules[i]['moduleType']!='Especial'){        
            $("#menuButtons").append('<div class="modal-body"><button type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;"" class="btn btn-primary" onclick="PrintTicket('+jsonModules[i]['id']+',0); selAttention=true;"><span class="glyphicon glyphicon-time"></span> '+jsonModules[i]['moduleName'] +'</button>   </div>' );
        }else{
            var moduleId = jsonModules[i]['id'];
            $.post('scripts/getActivesModulesSpecial.php', {module: moduleId}, function(data, textStatus, xhr) {
                if(data!='nan'){
                    var jsonData = JSON.parse(data);
                    for(j=0; j < jsonData.length;j++){
                        var widthButton = '';
                        if(jsonData[j]['name'].length<17) widthButton='width: 300px;';
                        else widthButton='';
                        $("#menuButtons").append('<div class="modal-body"><button type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;'+widthButton+'" class="btn btn-primary" onclick="PrintTicket('+moduleId+','+jsonData[j]['id']+'); selAttention=true;"><span class="glyphicon glyphicon-time"></span> '+jsonData[j]['name'] +'</button>   </div>' );
                    }
                }
            });
        }
    };
    //jsonModules[i]['moduleName']
}


function setTothtemConfig(){
    getActivesModules();
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

    $('#patientName').text($("#rut").val().toUpperCase());
    var namePatient = '';
    $.post('scripts/getPatientName.php',{rut: $('#patientName').html()},function(data, textStatus, xhr){
        if(data!="0"){
            var dataJson = JSON.parse(data);
            namePatient = dataJson[0]['name']+' '+dataJson[0]['lastname'];
            $('#WelcomeLabel').text(Welcome()+' '+namePatient);
        }else{
            $('#WelcomeLabel').text(Welcome());
        }

    });

    //$('#WelcomeLabel').text(Welcome()+' '+namePatient);
    /*
    for(var i in dataJson){
        datos += dataJson[i];
        if(FechaF==dataJson[i]['date_c']){
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
        if(inputTypeT==2){
			$('#bigText').text('Verificando DNI...');
        }else{
        	$('#bigText').text('Verificando Rut...');
        }
        
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
	window.setTimeout(function(){
 		$('#buttonsGrid').fadeIn('slow');
	    $('#inputText').fadeIn('slow');
	    $('#bigText').fadeIn('slow', function() {
	        $('#loadingLogo').fadeOut('slow');
	        $('#bigText').text('Ingrese Su Rut');
	        $('#bigText').fadeIn('slow');
	    });

    }, 500);

    window.setTimeout(function(){
        $('#bigText').fadeIn('slow'); 
        $('#rut').attr("disabled", false);
    }, 500);
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
//estado del agendamiento
function Agendamientos(date,datosJson){
    var htmlString = $(".responseCalendar").html();
    var initialHour=datosJson['hour_c'];
    var state=datosJson['state'];
    var examen=datosJson['exam_name'];
    //1-hora
    var now=new Date();
    var hours=now.getHours();
    var minutes=now.getMinutes();
    //diferencia
    var QueryMinutes=(parseInt(hours)*60)+parseInt(minutes);
    var examH=initialHour[0]+initialHour[1];
    var examM=initialHour[3]+initialHour[4];
    var ExamMinutes=(parseInt(examH)*60)+(parseInt(examM));
	var minutes=ExamMinutes - QueryMinutes;
    if(minutes<=0 && minutes> -15){
    	alerts("<b>Usted tiene un atrazo de "+parseInt(minutes*-1)+" minutos. Confirme su llegada en recepcion</b>",0);
    }else{
        if(ExamMinutes>QueryMinutes && minutes<120){
            if(state=="confirmado" || state=="agendado"){
    			alerts("<b>¿Desea un numero de atencion para pagar su examen de :</b> "+examen+" ?",3);
            }else{
                if(state=="pagado"){
                	alerts("<b>Tiene un examen de "+examen+" los proximos "+minutes+" minutos desea pasar a espera?</b>",1);
                }else{
                    if(state!="en espera"){
                        alerts("<b>Tiene un examen de "+examen+" los proximos "+minutes+" minutos desea pasar a espera?</b>",1);
                    }
                }
            }
        }else{
            if(minutes>120){
            	alerts("<b>Recuerde que tiene un examen de "+examen+" para hoy "+ date +" a las "+ initialHour+" horas</b>",0);
            }else{
                if(minutes<0 && minutes>-120){
                	alerts(examen,2);
                }
            }
        }
    }
}
//imprime el examen
function printHistory(messages,historys){
    bootbox.dialog({
        message: messages,
        title: "Atencion",
        buttons: {
            success: {
                label: "Si",
                className: "btn-success",
                callback: function() {
                    for(var i=0;i<historys.length;i++){
                        console.log("imprimir->"+historys[i]);
                        printReport(historys[i]);
             
                    }
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
}

//eventos para resetear el timer
function setup() {
    this.addEventListener("keypress", resetTimer, false);
    this.addEventListener("click", resetTimer, false);
    this.addEventListener("mousedown", resetTimer, false);
    this.addEventListener("DOMMouseScroll", resetTimer, false);
    this.addEventListener("mousewheel", resetTimer, false);
    this.addEventListener("touchmove", resetTimer, false);
    startTimer();
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
    if(selAttention==false && getAccept==true){
        // AGREGAR COMET QUE ENVÍE AL LIMBO EN CASO DE QUE NO HAGA NADA
        $.post('scripts/insertLogs.php',{ rut: $("#patientName").html(), description: 'No seleccionó atención', ip: tothemIp, action: 'lb', cometType: 'tothtem' }, function(data, textStatus, xhr) {

            //BACKEND PARA EL COMET
            $.post('../../../visor/comet/backend.php',{msg: data},function(data, textStatus, xhr){
            });
        });
    }

    resetInput(1);
    //$("#menu-toggle").css({display:"none"});
    $( "#menu-toggle" ).fadeOut( 1000 );
    document.getElementById('rut').value ="";
    //pasa a inactivo
    bootbox.hideAll();
    cantidad=0;
    maxtime=maxMin;
    document.getElementById('wait').click();
    getAccept=false;


}
 //activo
function goActive() {
    startTimer();
}

</script>
   

</body>

</html>
