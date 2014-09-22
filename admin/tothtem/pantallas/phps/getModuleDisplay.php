<?php

include ('../../tothtem/scripts/libs/db.class.php');

if( isset( $_REQUEST['zone'] ) ){

    $zone = $_REQUEST['zone'];
    $db = NEW DB();
    $sql = "SELECT id,name,type FROM module WHERE zone = $zone and type != 1";
    $modules = $db->doSql($sql);
    do{
        $data[] = array(
            "id" => $modules['id'],
            "name" => $modules['name'],
            "type" => $modules['type'],
        );
    } while($modules=pg_fetch_assoc($db->actualResults));
    echo json_encode($data);
}else{
    echo 'NoOK';
}

?>