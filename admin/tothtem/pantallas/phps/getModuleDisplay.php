<?php

include ('../../tothtem/scripts/libs/db.class.php');

if( isset( $_REQUEST['zone'] ) ){

    $zone = $_REQUEST['zone'];
    $db = NEW DB();
    $sql = "SELECT module.id,module.name,module.type from module
            left join submodule as sm on sm.module=module.id 
            where zone =$zone and type !=1 and sm.state='activo' group by module.id";
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