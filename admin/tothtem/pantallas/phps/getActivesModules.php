<?php
include ('../../../inc/libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);


$module = $_REQUEST['module'];

$db = NEW DB();
$sql = "SELECT  module.id ,module.name 
		FROM module , submodule , module_type WHERE module.zone =(
		SELECT module.zone FROM module WHERE module.id=$module) AND submodule.module=module.id AND module.type=module_type.id 
		AND state='activo' AND module_type.name!='Tothtem' GROUP BY module.id ,module.name 
		";

//echo $sql;
$data = $db->doSql($sql);
if($data){
    do{
        $moduleName = $data['name'];
        $id = $data['id'];
        $config[] = array(
        	'id' => $id,
            'moduleName' => $moduleName
        );
    }while($data = pg_fetch_assoc($db->actualResults));
    echo json_encode($config);
}else{
	echo "nan";
}




?>