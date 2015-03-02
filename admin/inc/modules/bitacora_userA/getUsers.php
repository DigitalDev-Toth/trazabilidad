<?php
//include ('../scripts/libs/db.class.php');
include ('../../libs/db.class.php');

//get user data
$db = NEW DB();
$sql = "SELECT id, name, username, state FROM users WHERE role=2";
$users = $db->doSql($sql);

do {
	$array[]= $users;
} while($users=pg_fetch_assoc($db->actualResults));

echo json_encode($array);

?>

