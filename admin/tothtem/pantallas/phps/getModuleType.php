<?php
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');
$submodule = $_REQUEST['submodule'];
$db = NEW DB();
$sql = "SELECT type from module where id= (select module from submodule where id=$submodule)";
$type = $db->doSql($sql);
echo $type['type'];

?>