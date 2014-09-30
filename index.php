
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
    <link href="admin/tothtem/pantallas/js/jqwidgets/jqwidgets/styles/jqx.base.css" rel="stylesheet">
    <script src="admin/tothtem/tothtem/js/jquery-2.0.3.js" type="text/javascript"></script>
    <script src="admin/tothtem/tothtem/js/validarut.js" type="text/javascript"></script>
    <script src="admin/tothtem/tothtem/js/bootbox.js"></script>
    <script src="admin/tothtem/tothtem/js/bootstrap.js"></script>
    <script src="admin/tothtem/pantallas/js/jqwidgets/jqwidgets/jqx-all.js"></script>
    <script src="admin/tothtem/pantallas/js/bootstrap.js"></script>

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
    <span>Versión 0.4</span>
</div>

<br/>
<!-- Menu paciente -->
<div id="menu" class="header">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-content">

                <div id="menuButtons">
                    <div class="modal-body" style="text-align: center;">
                    <button id="admin" type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 500px;" class="btn btn-primary" title="Ingreso a opciones de módulos para administrador, o movimiento de números para usuario">
                            <span class="glyphicon glyphicon-calendar"></span> Gestión o pantalla de submódulo
                        </button> 
                  	</div>
                  	<div class="modal-body" style="text-align: center;">
                        Seleccione Zona para mostrar:
                        <div id="listZones" style="margin-left: auto; margin-right: auto;"></div><br/>
                        <button id="totem" type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 300px;" class="btn btn-primary" title="Tótem para retiro de número de atención, Zona 1">
                            <span class="glyphicon glyphicon-list-alt"></span> Tótem
                        </button> 	
                  	</div>
                    <div class="modal-body" style="text-align: center;">
                    	<button id="display" type="button" style="padding:12px 25px;font-size: 25px;border-radius: 33px;width: 400px;" class="btn btn-primary" title="Visualización Pantalla de Pacientes">
                    		<span class="glyphicon glyphicon-eye-open"></span> Pantalla de Pacientes
                		</button> 
                  	</div>
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
    getZone();
	$("#totem").click(function() {
        /*
        1 Hall Central
        2 Consultas Medicas
        3 Carpa
        4 Plataforma Central
        5 Plataforma Oriente
        */
        if($("#listZones").jqxDropDownList("getSelectedItem").value!=2){
      	    window.open("admin/tothtem/tothtem/index.php?tothtem="+$("#listZones").jqxDropDownList("getSelectedItem").value, '_blank');
      	    return false;
        }else{
            alert("No hay Tótem en Consultas Médicas");
        }
   	});
   	$("#admin").click(function() {
      	window.open("admin/login.php", '_blank');
      	return false;
   	});
	$("#display").click(function() {
      	window.open("admin/tothtem/pantallas/pantalla.php?zone="+$("#listZones").jqxDropDownList("getSelectedItem").value, '_blank');
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

function getZone(){
    var source =
    {
        datatype: "json",
        datafields: [
            { name: 'id' },
            { name: 'name' }
        ],
        url: "admin/tothtem/pantallas/phps/getSelectors.php?type=zone&all=yes",
        async: false
    };
    var dataAdapter = new $.jqx.dataAdapter(source);
    $("#listZones").jqxDropDownList({selectedIndex: 0, source: dataAdapter, displayMember: "name", valueMember: "id", width: 200, height: 30});

}

</script>
   

</body>

</html>
