<?php


if(isset($_GET['id']))
{
		
	include ('../scripts/libs/db.class.php');
	$id = $_GET['id'];
	$db = new DB("report_history", "id");
	$row = $db->doSql("SELECT report FROM report_history WHERE id=(SELECT history FROM history_calendar WHERE id=$id)");
	echo ''.$row['report'];
}

?>