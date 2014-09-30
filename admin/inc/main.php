<?
	session_start();
	if(!isset($_SESSION['alive'])) { echo '<script>window.parent.location.href="../login.php?exit=timeout";</script>'; }
	else { $_SESSION['alive'] = true; }

?>
<html>
	<head>
		<link href="../style/main.css" rel="stylesheet" type="text/css" />
	</head>
	<body style="overflow:visible;">
<?
	@$modulo=$_GET['modulo'];
	switch ($modulo)
			{
			// Menú Administrador //
			case 'users':
			include("modules/users.php");
			break;

			case 'users_modules':
			include("modules/users_modules.php");
			break;			

			case 'modules':
			include("modules/modules.php");
			break;
			
			case 'role':
			include("modules/role.php");
			break;

			case 'roles':
			include("modules/roles.php");
			break;

			case 'users_roles':
			include("modules/users_roles.php");
			break;

			case 'changePass':
			include("modules/change_pass.php");
			break;

			case 'module_type':
			include("modules/module_type.php");
			break;

			case 'zone':
			include("modules/zone.php");
			break;

			case 'module':
			include("modules/module.php");
			break;

			case 'submodule':
			include("modules/submodule.php");
			break;

			case 'module_special':
			include("modules/module_special.php");
			break;

			case 'display':
			include("modules/show_displays.php");
			break;

			case 'statistical':
			include("modules/statistical.php");
			break;

			case 'trace':
			include("modules/trace.php");
			break;

			case 'supervision':
			include("modules/supervision.php");
			break;

			case 'bitacora':
			include("modules/bitacora.php");
			break;
		

			default:
			//include("modules/search.php");
			break;
			}
?>
</body>
</html>
