<?php
include ('libs/db.class.php');
$rut = $_REQUEST['rut'];
$db = NEW DB();
$sql = "SELECT id FROM patient WHERE rut='$rut'";
$row = $db->doSql($sql);

if(!$row){
    echo 0;
}else{
    echo md5($row['id']);
}
?>