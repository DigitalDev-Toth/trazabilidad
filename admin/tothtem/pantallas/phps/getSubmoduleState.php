<?php
include ('../../../inc/libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);


$submodule = $_REQUEST['submodule'];

$db = NEW DB();
$sql = "SELECT state FROM submodule WHERE id=$submodule";
$data = $db->doSql($sql);

echo $data['state'];

?>