<?php
include ("libs/db.conf.php");
$localhost = pg_pconnect("host=$hostname port=$port dbname=$dbname user=$username password=$password");

if (!isset($_SESSION)) {
	session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
	$_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {

	$loginUsername = $_POST['username'];
	$password = md5(base64_decode($_POST['password']));

	$MM_redirecttoReferrer = false;

	//$LoginRS__query=sprintf("SELECT * FROM users WHERE username='%s' AND password='%s'", get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password));
	$LoginRS__query = "SELECT * FROM users WHERE username='$loginUsername' AND password='$password'";

	$LoginRS = pg_query($localhost, $LoginRS__query);
	$row = pg_fetch_assoc($LoginRS);

	if ($row) {

		$id = $row['id'];
		$ip = $_SERVER['REMOTE_ADDR'];
		if ($ip == "::1") {
			$ip = getHostByName(getHostName());
		}
		$idRole = $row['role'];
		$name = $row['realname'];
		$role = $row['role'];
		$permission = $row['permission'];//colocar en SM
		//$queryModules = "SELECT * FROM submodule WHERE module IN (SELECT module FROM users_modules WHERE users=$id) AND ip='$ip'";
		
		//$queryModules = "SELECT * FROM submodule WHERE ip='$ip' AND users=$id"; ORIGINAL, evalúa IP
		$queryModules = "SELECT * FROM submodule WHERE users=$id LIMIT 1";

		$dataModule = pg_query($localhost, $queryModules);
		$data = pg_fetch_assoc($dataModule);

		if ($row['employee']) {
			$employee = pg_fetch_assoc(pg_query($localhost, "SELECT * FROM employee WHERE id=" . $row['employee']));
			$_SESSION['UserEmployee'] = $employee['name'] . ' ' . $employee['lastname'];
		} else {
			$_SESSION['UserEmployee'] = 'Usuario Externo';
		}
		// $role = pg_fetch_assoc(pg_query($localhost,"SELECT name FROM role WHERE id=".$row['role']));
		//declare two session variables and assign them
		//$_SESSION['Realname'] = 'Administrador';
		$_SESSION['Username'] = $loginUsername;
		$_SESSION['Realname'] = $name;
		$_SESSION['UserRole'] = $role['realname'];
		$_SESSION['idRole'] = $idRole;
		$_SESSION['UserId'] = $id;
		$_SESSION['Role'] = $role;
		$_SESSION['permission'] = $permission;//colocar en SM
		$_SESSION['alive'] = TRUE;
		if ($_SESSION['idRole'] != 1) {
			if ($data) {
				echo $data['id'] . "-sub".$id;
			} else {
				echo 10;
			}
		} else {
			echo $_SESSION['idRole'];
		}

		//if ($data) {
		//echo $data['id'] . "-sub";

	} else {
		echo 0;
		//echo "Error en el nombre de Usuario o contraseña";
	}
}
?>
