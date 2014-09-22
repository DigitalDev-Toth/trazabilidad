<?
include('../tothtem/scripts/libs/db.class.php');

function findRole($module, $type)
{
	$user = $_SESSION['UserId'];
	$userType = $_SESSION['Usertype'];
	if($userType!="Administrador")
	{
		$db = new DB();
		$sql = "SELECT * FROM users_roles where users=$user and roles in(SELECT id FROM roles WHERE module='$module' AND type='$type')";
		$row = $db->doSql($sql);
		if($row) return TRUE;
		else return FALSE;
	}
	else return TRUE;
}
?>
