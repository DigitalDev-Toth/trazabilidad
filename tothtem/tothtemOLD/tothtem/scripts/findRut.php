<?php
include ('libs/db.class.php');
$rut = $_REQUEST['rut'];
$db = NEW DB();
$sql = "SELECT * FROM patient WHERE rut='$rut'";
$row = $db->doSql($sql);
if(!$row){
    echo 0;
}else{
    echo $row['id'];
}
?>