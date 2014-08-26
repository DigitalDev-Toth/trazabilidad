<?
include("libs/db.class.php");
$calendar = $_REQUEST['calendar'];
$db = new DB();
$sql = "SELECT report FROM report_history WHERE calendar=$calendar";
$data = $db->doSql($sql);
echo $data['report'];
