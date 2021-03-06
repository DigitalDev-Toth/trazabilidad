<?php
include ("inc/libs/db.class.php");

//$institutions = "(".$institutions.")";
function getItemMenu($item, $title) {
	switch ($item) {
		case 'show_calendar_finalized_prescription':$itemCss = 'ex_finalizado';break;
		default:$itemCss = $item;
	}

	return '<li><a href="inc/contentMain.php?module=' . $item . '" id="menuLeft' . ucfirst($itemCss) . '"  target="main" >' . $title . '</a></li>';
}

function getSubMenu($content) {
	session_start();
	$subMenu = '';
	switch ($content) {

		case 'admin':
			$subMenu .= '<ul class="dropdown-menu">';
			$subMenu .= getItemMenu("zone", "Zonas");
			$subMenu .= getItemMenu("module_type", "Tipo Modulo");
			$subMenu .= getItemMenu("module", "Modulos");
			$subMenu .= getItemMenu("submodule", "Sub Modulos");
			$subMenu .= getItemMenu("module_special", "Modulos Especiales");
			$subMenu .= getItemMenu("tothtems", "TothTems");
			$subMenu .= getItemMenu("users", "Usuarios");
			$subMenu .= getItemMenu("employee", "Empleados");
			$subMenu .= getItemMenu("changePass", "Cambiar Contraseña");
			$subMenu .= getItemMenu("role", "Roles de Usuario");
			$subMenu .= getItemMenu("roles", "Permisos de Usuario");
			//$subMenu .= getItemMenu("multimedia", "Contenido Pantallas");
			
			$subMenu .= '</ul>';
			break;


		case 'bitacora':
			$subMenu .= '<ul class="dropdown-menu">';
			if(findRole("logbook_patient","show")){
				$subMenu .= getItemMenu("bitacora", "<span class='glyphicon glyphicon-user'> Pacientes");
			}
			if(findRole("logbook_executive","show")){
				$subMenu .= getItemMenu("bitacora_user", "<span class='glyphicon glyphicon-book'> Ejecutivos");
			}
			$subMenu .= '</ul>';
			break;

		/*case 'users':
			$subMenu .= '<ul>';
			$subMenu .= getItemMenu("changePass", "Cambiar Password");
			$subMenu .= getItemMenu("role", "Roles");
			$subMenu .= getItemMenu("roles", "Tipo de Roles");
			$subMenu .= getItemMenu("users", "Usuarios");
			$subMenu .= '<li class = "round_corner_menu_top"></li>';
			$subMenu .= '</ul>';
			break;*/

		case 'visor':
			$subMenu .= '<ul>';

			$db = new DB();
			$sql = "SELECT id, name FROM zone";
			$rows = $db->doSql($sql);
			do {
				$subMenu .= '<li><a href="../visor/index.php?idZone=' . $rows['id'] . '" target="main" >' . $rows['name'] . '</a></li>';
				//$subMenu .= getItemMenu("visor&zone=".$rows['id'],$rows['name']);
			} while ($rows = pg_fetch_assoc($db->actualResults));

			$subMenu .= '<li class = "round_corner_menu_top"></li>';
			$subMenu .= '</ul>';
			break;

		case 'patient':
			$subMenu .= '<ul>';

			$db = new DB();
			$sql = "SELECT id, name FROM zone";
			$rows = $db->doSql($sql);
			do {
				$subMenu .= '<li><a href="tothtem/pantallas/pantalla.php?zone=' . $rows['id'] . '" target="main" >' . $rows['name'] . '</a></li>';
				//$subMenu .= getItemMenu("visor&zone=".$rows['id'],$rows['name']);
			} while ($rows = pg_fetch_assoc($db->actualResults));

			$subMenu .= '<li class = "round_corner_menu_top"></li>';
			$subMenu .= '</ul>';
			break;

		case 'profile':
			$subMenu .= '<li><a href="exit.php" >Salir</a>';

			break;

	}
	return $subMenu;
}

?>
