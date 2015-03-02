<?php
//include ('../scripts/libs/db.class.php');
include ('../../libs/db.class.php');

$id=$_REQUEST['id'];

//get user data
$db = NEW DB();
$sql = "SELECT username, e.name AS name, lastname, rut, phone, birthdate, address, mail
		FROM users u
		LEFT JOIN employee e ON e.id=u.employee
		WHERE u.id=$id";
$users = $db->doSql($sql);

do {
	$array[]= $users;
} while($users=pg_fetch_assoc($db->actualResults));

echo json_encode($array);

?>

