
<?php
include ('../../libs/db.class.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','On');
$db = NEW DB();

$sql = "SELECT module.id as ids,module.name,schedules.*  FROM module,schedules where module.type = 1 and module.id = schedules.tothtem";
$row = $db->doSql($sql);
$i = 0;
if($row){
  do {
        $dataContent[$i] = $row;
        $i++;
  } while($row=pg_fetch_assoc($db->actualResults));
  echo json_encode($dataContent);
}






?>