<?php
include ('../libs/db.class.php');
$module = $_REQUEST['module'];


$dbAlias = new DB();
$sqlAlias = "SELECT alias FROM module_special WHERE module=$module";
$resultAlias = $dbAlias->doSql($sqlAlias);
do{
    $data[] = array(
        "value" => $resultAlias['alias']
        );
}while($resultAlias=pg_fetch_assoc($dbAlias->actualResults));

echo json_encode($data);
?>