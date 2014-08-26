<?php
include ('libs/db.class.php');
$calendar = $_REQUEST['idCalendar'];
$db = NEW DB();
$sql = "SELECT id FROM report_history WHERE calendar='$calendar'";
$row = $db->doSql($sql);
if(!$row){
    echo 0;
}else{
    echo $row['id'];
}
?>