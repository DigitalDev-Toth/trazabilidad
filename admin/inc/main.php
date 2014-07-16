<?
	session_start();
	if(!isset($_SESSION['alive'])) { echo '<script>window.parent.location.href="../login.php?exit=timeout";</script>'; }
	else { $_SESSION['alive'] = true; }

?>
<html>
<head>
	</head>
<link href="../style/main.css" rel="stylesheet" type="text/css" />
<?
	@$modulo=$_GET['modulo'];
	switch ($modulo)
			{
			// Menú Administrador //
			case 'users':
			include("modules/users.php");
			break;
			
			case 'role':
			include("modules/role.php");
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
			
			default:
			include("modules/search.php");
			break;
			}
?>
</body>
</html>
