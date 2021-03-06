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
        <h class="navbar-brand" href="#"> Trazabilidad</h>
    </div>';

echo '<div class="collapse navbar-collapse navbar-ex1-collapse">';
//Menu Left Static
echo '<ul class="nav navbar-nav">';
echo '<li> ' . itemMenu("trace", "<span class='glyphicon glyphicon-time'></span> Situación Actual", 'enable', true) . '</li>';
echo '<li> ' . itemMenu("bitacora", " <span class='glyphicon glyphicon-list-alt'></span> Bitácora", 'enable', true) . '</li>';
echo '<li> ' . itemMenu("supervision", "<span class='glyphicon glyphicon-search'></span>  Supervisión", 'enable', true) . '</li>';
echo '<li>  ' . itemMenu("statistical", "<span class='glyphicon glyphicon-calendar'></span> Estadísticas", 'enable', true) . '</li>';
echo '</ul>';

//Menu Right
echo '<ul id="nav" class="nav navbar-nav navbar-right">';

//Dropdown
echo '<li class="dropdown">' . itemMenu("admin", "Configuración", 'enable', false) . '</li>';
echo '<li>' . $_SESSION['Realname'] . '<a href="exit.php" >Salir <span class="glyphicon glyphicon-log-out"></span></a></li>';
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
?>
