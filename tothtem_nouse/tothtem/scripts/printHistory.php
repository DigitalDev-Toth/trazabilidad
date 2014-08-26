<?php
if(isset($_GET['history_view']))
{
	include("libs/db.class.php");
	$id = $_GET['history_view'];
	$db = new DB("report_history", "id");
	$row = $db->doSql("SELECT report FROM report_history WHERE id=$id");
	echo $row['report'];
}

?>