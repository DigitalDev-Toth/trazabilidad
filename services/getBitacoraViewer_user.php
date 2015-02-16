<?php
include ('../admin/inc/libs/db.class.php');


$user = $_REQUEST['user'];

//get user data
$db = NEW DB();
$sql = "SELECT id, name, username, state FROM users WHERE id=$user";
$users = $db->doSql($sql);

do {
	$array[]= $users;
} while($users=pg_fetch_assoc($db->actualResults));

echo json_encode($array);

?>
