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
			$subMenu .= getItemMenu("users","Cambiar Password");
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

function getSubMenuTrace($item,$title, $filter)  {
	switch ($item) {
		case 'prevision&type=agreement': $itemCss='prevision'; break;
		case 'prevision&type=person': $itemCss='prevision'; break;
		case 'show_calendar_scheduled': $itemCss='ex_agendado'; break;
		case 'show_calendar_confirmed':	$itemCss='ex_confirmado'; break;
		case 'show_calendar_payment': $itemCss='ex_pagado'; break;
		case 'show_calendar_waiting': $itemCss='ex_en_espera'; break;
		case 'show_calendar_admitted': $itemCss='ex_ingresado'; break;
		case 'show_calendar_assigned': $itemCss='ex_asignado'; break;
		case 'show_calendar_informed': $itemCss='ex_informado'; break;
		case 'show_calendar_finalized': $itemCss='ex_finalizado'; break;
		case 'show_calendar_released': $itemCss='ex_despachado'; break;
		case 'room&filter=schedule': $itemCss='room'; break;
		case 'scheduleDrInformant':	$itemCss='drInformante'; break;
		case 'show_calendar_waiting_prescription': $itemCss='ex_en_espera'; break;
		case 'show_calendar_admitted_prescription_trace':	$itemCss='ex_ingresado'; break;
		case 'show_calendar_finalized_prescription': $itemCss='ex_finalizado'; break;		
		case 'show_calendar_scheduled_prescription': $itemCss='ex_agendado'; break;
		case 'show_calendar_confirmed_prescription':	$itemCss='ex_confirmado'; break;
		case 'show_calendar_payment_prescription': $itemCss='ex_pagado'; break;
		default: $itemCss = $item;
	}
	
return '<li><a href="inc/main.php?module='.$item.'&filter='.$filter.'" id="menuLeft'.ucfirst($itemCss).'"  target="main" >'.$title.'</a></li>';
}

?>
