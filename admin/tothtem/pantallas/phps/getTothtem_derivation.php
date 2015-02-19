<?php
include ('../../../inc/libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);


$zone = $_REQUEST['zone'];

$db = NEW DB();

$sql = "SELECT  module.id ,module.name AS name,  module_type.name AS type_name, submodule.id AS submodule_id
        FROM module, submodule , module_type WHERE module.zone=$zone 
        AND submodule.module=module.id AND module.type=module_type.id 
        AND state='activo' AND module_type.name IN('Tothtem') LIMIT 1";

    $data = $db->doSql($sql);
    if($data){
        do{
            $moduleName = $data['name'];
            $module_type = $data['type_name'];
            $id = $data['id'];
            $submodule_id = $data['submodule_id'];
            $config[] = array(
                'module' => $id,
                'submodule' => $submodule_id
            );
        }while($data = pg_fetch_assoc($db->actualResults));
        echo json_encode($config);
    }else{
        echo "nan";
    }




?>