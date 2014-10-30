<?php
include ('libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);

if(isset($_REQUEST['ip'])){
	//$ip = $_REQUEST['ip'];
	$ip = $_REQUEST['ip'];
    /*
        1 Hall Central
        2 Consultas Medicas
        3 Carpa
        4 Plataforma Central
        5 Plataforma Oriente
    */
	if($ip==1){
		$ip='192.168.0.197';
	}else if($ip==3){
		$ip='192.168.0.123';
	}else if($ip==4){
		$ip='192.168.0.124';
	}else if($ip==5){
		$ip='192.168.0.125';
	}
	$db = NEW DB();
	$sql = "SELECT  module.id ,module.name AS name,  module_type.name AS type_name
			FROM module, submodule , module_type WHERE module.zone =(
			SELECT module.zone FROM module WHERE module.id=(
			SELECT submodule.module FROM submodule WHERE ip='$ip')) AND submodule.module=module.id AND module.type=module_type.id 
			AND state='activo' AND NOT module_type.name IN('Tothtem') GROUP BY module.id ,module.name,module_type.name 
			";

	//echo $sql;
	$data = $db->doSql($sql);
	if($data){
	    do{
	        $moduleName = $data['name'];
	        $module_type = $data['type_name'];
	        $id = $data['id'];
	        $config[] = array(
	        	'id' => $id,
	            'moduleName' => $moduleName,
	            'moduleType' => $module_type
	        );
	    }while($data = pg_fetch_assoc($db->actualResults));
	    echo json_encode($config);
	}else{
		echo "nan";
	}

}


?>