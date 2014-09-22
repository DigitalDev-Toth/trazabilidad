<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: login.php"); header('Content-Type: text/html; charset=utf8');  }
/*
include ('inc/role.php');
if(!findRole("administration","show_administration")){
    echo '<script>alert("No tiene permisos para ingresar a Administracion");
        window.history.back();
    </script>';
}*/
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es-es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title>Trazabilidad :: Home</title>
<link href="style/index.css" rel="stylesheet" type="text/css" /> 
<link href="style/menuTop.css" rel="stylesheet" type="text/css" />
<link rel="SHORTCUT ICON" href="images/favicon.ico"> 
<script src="inc/js/jquery-1.2.6.js" type="text/javascript"></script>
<script src="inc/js/menu.js" type="text/javascript"></script>
<script src="inc/js/jdMenu.js" type="text/javascript"></script>
<script src="inc/js/jd-name.js" type="text/javascript"></script>
<link rel="stylesheet" href="inc/js/tooltip/jquery.tooltip.css" />
<script src="inc/js/tooltip/lib/jquery.bgiframe.js" type="text/javascript"></script>
<script src="inc/js/tooltip/lib/jquery.dimensions.js" type="text/javascript"></script>
<script src="inc/js/tooltip/jquery.tooltip.js" type="text/javascript"></script>
<script>
	var show_menu_top = true;
	$(document).ready(function(){
		$(" #nav ul ").css({display: "none"}); // Opera Fix
		$(" #nav li").hover(function(){
				$(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
				},function(){
				$(this).find('ul:first').css({visibility: "hidden"});
				});
		$("#header").fadeIn("600");
	});
	
</script>
</head>
<body>
<? include("inc/menuTop.php");?>
<iframe id= "contentMain" name="contentMain" src="inc/contentMain.php" height= "100%" frameborder="0" transparency marginheight="0" marginwidth="0">
				<p>Tu navegador no puede usar BioRis!</p>
</iframe>
</body>
</html>
