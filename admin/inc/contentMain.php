<?
	header('Content-Type: text/html; charset=latin1'); 
	session_start();
	if(!isset($_SESSION['alive'])) {
		echo '<script>window.parent.location.href="../login.php?exit=timeout";</script>'; 
	}
	else { 
		$_SESSION['alive'] = true; 
	}


	if(isset($_REQUEST['type']))
	{
		$value = $_REQUEST['type'];
		$link2 = "&type=$value";
	}
	else $link2="";
	echo '<head>
	<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
	<link href="../style/contentMain.css" rel="stylesheet" type="text/css" />
	</head>';
	$module=$_GET['module'];
	switch ($module) {
		case 'today':
			if(findRole("calendar", "show_menu")) { 			
				echo '<iframe id="main" name="main" src="modules/calendar/calendar.php?filter=examen" width="100%" height="99%" scrolling="no" frameborder="0" transparency marginheight="0" marginwidth="0"> </iframe>';
			}
		break;
		
		default:
		if ($content!='show_calendar' &&  $content!='default') {
			echo '<iframe name="main" src="main.php?modulo='.$module.''.$link2.'" width="100%" height="99%" scrolling="auto" frameborder="0" transparency marginheight="0" marginwidth="0">
								<p>Tu navegador no puede usar BioRis!</p></iframe>';
		}
			
		//include("modules/default.php");
	} 
?>		
