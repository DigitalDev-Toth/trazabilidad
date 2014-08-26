<?php
//include ('../scripts/libs/db.class.php');
include ('../../tothtem/scripts/libs/db.class.php');
//get data

session_start();
//$userId = $_SESSION['UserId'];

$userId = $_REQUEST['userId'];
$type = $_REQUEST['type'];

$db = NEW DB();
if($type=='zone'){
    $sql="SELECT z.id AS id, z.name AS name
        FROM zone z
        LEFT JOIN module m ON m.zone=z.id
        LEFT JOIN users_modules um ON um.module=m.id
        WHERE um.users=$userId GROUP BY z.id, z.name";
    $zones = $db->doSql($sql);
    do{
        $data[] = array(
            "id" => $zones['id'],
            "name" => $zones['name']
        );
    } while($zones=pg_fetch_assoc($db->actualResults));
    echo json_encode($data);


}elseif($type=='module'){
    $zone = $_REQUEST['zone'];

    $sql="SELECT m.id AS id, m.name AS name
        FROM module m
        LEFT JOIN users_modules um ON um.module=m.id
        WHERE um.users=$userId AND m.zone=$zone ORDER BY m.id";
    $modules = $db->doSql($sql);
    do{
        $data[] = array(
            "id" => $modules['id'],
            "name" => $modules['name']
        );
    } while($modules=pg_fetch_assoc($db->actualResults));
    echo json_encode($data);


}elseif($type=='submodule'){
    $module = $_REQUEST['module'];

    $sql="SELECT *
        FROM submodule
        WHERE module=$module AND state='inactivo' ORDER BY id";
    $submodules = $db->doSql($sql);
    do{
        $data[] = array(
            "id" => $submodules['id'],
            "name" => $submodules['name']
        );
    } while($submodules=pg_fetch_assoc($db->actualResults));
    echo json_encode($data);
}

?>