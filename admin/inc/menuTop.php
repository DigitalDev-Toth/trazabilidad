<?
session_start();
include("subMenu.php");
include("role.php");

function itemMenu($item,$title,$state, $link_direct=true)
{
	if($state=='enable')
	{
		$class = '';
		$href  = 'href="inc/contentMain.php?module='.$item.'"';
		$url   = 'images/iconMenu/';
		$subMenu = getSubMenu($item);
		if(!$link_direct) {
				return '<a href = "#" title = '.$title.'>'.$title.$subMenu.'</a>'; 
		}  
		return '<a id="menuTop'.ucfirst($item).'" '.$class.' target="contentMain" '.$href.' title = '.$title.'>'.$title.$subMenu.'</a>'; 
	 
	}
}
echo '<div id = "header">
			<ul id="nav">';

				if(findRole("administration", "show_menu")) 
				{$state='enable';}
				else { $state='disable';}
				echo '<li>'.itemMenu("admin","Administrar",$state, false).'</li>';

				if(findRole("users", "show")) 
				{$state='enable';}
				else { $state='disable';}
				echo '<li>'.itemMenu("users","Personas",$state, false).'</li>';

				if(findRole("visor", "show_menu")) 
				{$state='enable';}
				else { $state='disable';}
				echo '<li>'.itemMenu("visor","Visor",$state, false).'</li>';

				/*if(findRole("visor", "show_menu")) 
				{$state='enable';}
				else { $state='disable';}*/
				echo '<li>'.itemMenu("display","Pantalla",'enable', true).'</li>';

				/*if(findRole("admin", "show_menu")) 
				{$state='enable';}
				else { $state='disable';}
				echo '<li>'.itemMenu("admin","Administrar",$state, false).'</li>';

				/*echo '<li>'.itemMenu("admin","Administrar", true, false).'</li>';
				echo '<li>'.itemMenu("users","Personas", true, false).'</li>';
				echo '<li>'.itemMenu("visor","Visor", true, false).'</li>';*/
				

				
	echo '</ul>';
			echo '<div id = "profile">'.$_SESSION['Realname'].'<a href="exit.php" >Salir</a></div>';	
	echo '	</div>';
?>
