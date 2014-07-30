<?php
include ('scripts/libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);

$ip = $_REQUEST['ip'];

$db = NEW DB();
$sql = "SELECT * FROM ";
$config = $db->doSql($sql);

$jsonConfig = array('config1' => "", 'config2' => "");

echo json_encode($jsonConfig);

?>