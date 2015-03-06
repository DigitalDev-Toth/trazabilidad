<?
session_start();
include ("subMenu.php");
include ("role.php");

function itemMenu($item, $title, $state, $link_direct = true) {
	if ($state == 'enable') {
		$class = '';
		$href = 'href="inc/contentMain.php?module=' . $item . '"';
		$url = 'images/iconMenu/';
		$subMenu = getSubMenu($item);
		if (!$link_direct) {
			return '<a href = "#" class="dropdown-toggle" data-toggle="dropdown" title = ' . $title . '>' . $title . ' <b class="caret"></b></a>' . $subMenu;
		}
		return '<a ' . $href . ' id="menuTop' . ucfirst($item) . '" ' . $class . ' target="contentMain" title = "' . $title . '">' . $title . '</a>';

	}
}

//Declaracion NavBar bootstrap
echo '
<nav class="navbar navbar-inverse" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Desplegar</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php"> Trazabilidad</a>
    </div>';

echo '<div class="collapse navbar-collapse navbar-ex1-collapse">';
//Menu Left Static
echo '<ul class="nav navbar-nav">';

if(findRole("current_situation","show")){
	echo '<li> ' . itemMenu("trace", "<span class='glyphicon glyphicon-time'></span> Situación Actual", 'enable', true) . '</li>';
}
if(findRole("logbook_executive","show") || findRole("logbook_patient","show")){
	echo '<li id="bitacora_dropdown" class="dropdown">' . itemMenu("bitacora", "Bitácora", 'enable', false) . '</li>';	
}
/*

	***** Supervision RIP *****
if(findRole("surveillance","show")){
	echo '<li> ' . itemMenu("supervision", "<span class='glyphicon glyphicon-search'></span>  Supervisión", 'enable', true) . '</li>';
}

*/
if(findRole("statistics","show")){
	echo '<li>  ' . itemMenu("statistical", "<span class='glyphicon glyphicon-calendar'></span> Estadísticas", 'enable', true) . '</li>';
}


echo '</ul>';

//Menu Right
echo '<ul id="nav" class="nav navbar-nav navbar-right">';

//Dropdown
//echo '<li><span class="glyphicon glyphicon-info-sign" style="font-size:16px; aria-hidden="true"></span>  <span class="badge" style="font-size:16px;">42</span></li>';

echo '<li><button type="button" id="planPop" class="btn btn-lg btn-primary" "><span class="glyphicon glyphicon-bell"  aria-hidden="true"></span> <span class="badge" id="NotificationsP"></span></button></li>';
echo '<li><button type="button" id="alertPop" class="btn btn-lg btn-primary" "><span class="glyphicon glyphicon-user"  aria-hidden="true"></span> <span class="badge" id="NotificationsN"></span></button></li>';
if(findRole("settings","show")){
	echo '<li class="dropdown">' . itemMenu("admin", "Configuración", 'enable', false) . '</li>';	
}


echo '<li><a href="#" >'. $_SESSION['Username'] .'</a></li>';
echo '<li><a href="exit.php" >Salir <span class="glyphicon glyphicon-log-out"></span></a></li>';

/*

if (findRole("users", "show")) {$state = 'enable';} else { $state = 'disable';}
echo '<li>' . itemMenu("users", "Personas", $state, false) . '</li>';

if (findRole("visor", "show_menu")) {$state = 'enable';} else { $state = 'disable';}
echo '<li>' . itemMenu("visor", "Visor", $state, false) . '</li>';
echo '<li>' . itemMenu("trace", "Traza Pacientes", 'enable', true) . '</li>';
echo '<li>' . itemMenu("bitacora", "bitacora", 'enable', true) . '</li>';
echo '<li>' . itemMenu("statistical", "Estadistica", 'enable', true) . '</li>';*/
echo '</ul>';
//echo '<div id = "profile">' . $_SESSION['Realname'] . '<a href="exit.php" >Salir</a></div>';

echo '  </div>';
echo '	</nav>';


echo '<div id="showBitacora" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-viewer">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Bitácora</h4>
                </div>
                <div class="modal-body">
                    <div id="bitacoraContent" class="row well well-sm text-center"></div>
                </div>
            </div>
        </div>
    </div>';


?>
<style type="text/css">
	
.popover-content {
	max-height: 350px;
	overflow-y: scroll;
	overflow-x: hidden;
}
.popover{
    min-width: 400px ! important;
	max-width: 500px;
	width: auto;
}
.modal-lg {
	width: 90%;
}
</style>




<script type="text/javascript">


	var socket = io.connect('http://falp.biopacs.com:8000');  

	    
	function socketComet(){
	    socket.on('connect', function() {
	            socket.on('message', function(message) {
	           	var json = JSON.parse(message);
	           	if(message.indexOf("Alert") > -1){
	           		showAlert(json);
	           	}else{
	           		$("#NotificationsN").html('');
	    	   	}
	        });
	    });
	}

	function showAlert(json){
        $("#NotificationsN").html(json.length);
        $('#alertPop').on('show.bs.popover', function () {
        	var msg = '';
        	//$("")
        	msg = '<table class="table table-striped table-bordered table-condensed table-hover" style=" width: 400px">';
			for (var i = 0; i < json.length; i++) {
				msg += "<tr onclick='showBitacora(\""  +json[i].rut+"\")'><td>"+(i+1)+".- Paciente <b>"+json[i].rut+"</b> tiene un tiempo de espera de <b>"+json[i].max_wait+" minutos,</b> en espera para el modulo <b>"+ json[i].name +"</b> de la zona <b>"+ json[i].zname +"</b></td></tr>";
			};
			msg += '</table>';	
			$('#alertPop').attr('data-content', msg);
		});
	}
	$(document).ready(function() {
		$('#alertPop').popover({
		    trigger: 'click',
		    html: true,
		    title:'Pacientes',
		    placement: 'bottom',
		    content: 'Sin Notificaciones...',
		});

		$('#planPop').popover({
		    trigger: 'click',
		    html: true,
		    title:'Otras notificaciones',
		    placement: 'bottom',
		    content: 'Sin Notificaciones...',
		});

		socketComet();
		$("#alertPop").blur(function(event) {
			$("#alertPop").popover('hide');
		});
		$("#planPop").blur(function(event) {
			$("#planPop").popover('hide');
		});
	});

</script>
