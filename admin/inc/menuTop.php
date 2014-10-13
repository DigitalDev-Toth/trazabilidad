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
        <a class="navbar-brand" href="#">FALP</a>
    </div>';

echo '<div class="collapse navbar-collapse navbar-ex1-collapse">';
//Menu Left Static
echo '<ul class="nav navbar-nav">';
echo '<li>' . itemMenu("trace", "Situación Actual", 'enable', true) . '</li>';
echo '<li>' . itemMenu("bitacora", "Bitácora", 'enable', true) . '</li>';
echo '<li>' . itemMenu("supervision", "Supervisión", 'enable', true) . '</li>';
echo '<li>' . itemMenu("trace", "Estadísticas", 'enable', true) . '</li>';
echo '</ul>';

//Menu Right
echo '<ul id="nav" class="nav navbar-nav navbar-right">';

//Dropdown
echo '<li class="dropdown">' . itemMenu("admin", "Configuracion", 'enable', false) . '</li>';
echo '<li>' . $_SESSION['Realname'] . '<a href="exit.php" >Salir</a></li>';
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
