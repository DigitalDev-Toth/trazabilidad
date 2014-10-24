<?php
include ('../libs/db.class.php');
$zone = $_REQUEST['zone'];



$dbPosition = new DB();
$sqlPosition = "SELECT position FROM module WHERE zone=$zone";
$resultPosition = $dbPosition->doSql($sqlPosition);
do{//Quita los registros ya existentes de posición
    $data[] = array(
        "type" => "module_position",
        "value" => $resultPosition['position']
        );
}while($resultPosition=pg_fetch_assoc($dbPosition->actualResults));


$dbAlias = new DB();
$sqlAlias = "SELECT alias FROM module WHERE zone=$zone AND NOT alias=''";
$resultAlias = $dbAlias->doSql($sqlAlias);
do{
    $data[] = array(
        "type" => "module_alias",
        "value" => $resultAlias['alias']
        );
}while($resultAlias=pg_fetch_assoc($dbAlias->actualResults));

echo json_encode($data);
?>