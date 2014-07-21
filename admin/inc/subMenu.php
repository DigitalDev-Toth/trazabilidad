<?php
include("inc/libs/db.class.php");

//$institutions = "(".$institutions.")";
function getItemMenu($item,$title) {
		switch ($item)
		{
			case 'show_calendar_finalized_prescription': $itemCss='ex_finalizado'; break;
			default: $itemCss = $item;
		}
		
	return '<li><a href="inc/contentMain.php?module='.$item.'" id="menuLeft'.ucfirst($itemCss).'"  target="main" >'.$title.'</a></li>';
	}
	
function getSubMenu($content) {
	session_start();
	$subMenu ='';
	switch ($content) {
				
		case 'admin':
			$subMenu .= '<ul>';
			$subMenu .= getItemMenu("zone","Zonas");
			$subMenu .= getItemMenu("module_type","Tipo Modulo");
			$subMenu .= getItemMenu("module","Modulos");
			$subMenu .= getItemMenu("submodule","Sub Modulos");
			$subMenu .= '<li class = "round_corner_menu_top"></li>';
			$subMenu .='</ul>';
		break;
		
		case 'users':
			$subMenu .= '<ul>';
			$subMenu .= getItemMenu("changePass","Cambiar Password");
			$subMenu .= getItemMenu("role","Roles");
			$subMenu .= getItemMenu("users","Usarios");
			$subMenu .= '<li class = "round_corner_menu_top"></li>';
			$subMenu .='</ul>';
		break;

		case 'visor':
			$subMenu .= '<ul>';
			
			$db = new DB();
			$sql = "SELECT id, name FROM zone";
			$rows = $db->doSql($sql);
			do {
				$subMenu .= getItemMenu("visor",$rows['name']);
			} while ($rows = pg_fetch_assoc($db->actualResults));

			$subMenu .= '<li class = "round_corner_menu_top"></li>';
			$subMenu .='</ul>';
		break;

		case 'profile':
			$subMenu .= '<li><a href="exit.php" >Salir</a>';
		
		break;
	}
	return $subMenu;
}


?>
