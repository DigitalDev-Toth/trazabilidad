<?php
session_start();
if (!isset($_SESSION['Username'])) {header("location: login.php");
	header('Content-Type: text/html; charset=utf8');}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es-es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Trazabilidad :: Home</title>
<link href="style/index.css" rel="stylesheet" type="text/css" />
<link href="inc/js/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="inc/js/bootstrap/css/navbar.css" rel="stylesheet" type="text/css" />
<link rel="SHORTCUT ICON" href="images/favicon.ico">
<script src="inc/js/jquery-1.8.1.min.js" type="text/javascript"></script>
<script src="inc/js/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="inc/js/menu.js" type="text/javascript"></script>
<script src="inc/js/jdMenu.js" type="text/javascript"></script>
<script src="inc/js/jd-name.js" type="text/javascript"></script>
<link rel="stylesheet" href="inc/js/tooltip/jquery.tooltip.css" />
<script src="inc/js/tooltip/lib/jquery.bgiframe.js" type="text/javascript"></script>
<script src="inc/js/tooltip/lib/jquery.dimensions.js" type="text/javascript"></script>
<script src="inc/js/tooltip/jquery.tooltip.js" type="text/javascript"></script>

<script src="http://falp.biopacs.com:8000/socket.io/socket.io.js"></script>
<script src="inc/js/bitacora.js"></script>
<script src="inc/js/datatablesN/js/jquery.dataTables.min.js"></script>
<script src="inc/js/datatablesN/js/dataTables.tableTools.js"></script>
<script src="inc/js/datatablesN/js/dataTables.bootstrap.js"></script>

<script>
	var show_menu_top = true;
	$(document).ready(function(){
		$(" #nav ul ").css({display: "none"}); // Opera Fix
		$(" #nav li").hover(function(){
			$(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
			},function(){
			$(this).find('ul:first').css({visibility: "hidden"});
			});

		//$(" #bitacora_dropdown").css({display: "none"}); // Opera Fix
		$(" #bitacora_dropdown").hover(function(){
			console.log('here');
			$(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
			},function(){
			$(this).find('ul:first').css({visibility: "hidden"});
			});
		$("#header").fadeIn("600");
	});

</script>
</head>



<body >
<?include ("inc/menuTop.php");?>
<iframe id= "contentMain" name="contentMain" src="inc/contentMain.php?module='supervision'" height= "100%" frameborder="0" transparency marginheight="0" marginwidth="0">
				<p>Tu navegador no puede el sistema</p>
</iframe>
</body>
</html>
