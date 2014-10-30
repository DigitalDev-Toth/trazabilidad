<?php
include ('libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);

if(isset($_REQUEST['ip'])){
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
	$sql = "SELECT module.id as id_module , module.name as module_name , submodule.name as submodule_name , module_type.name as type, submodule.state as state   
			FROM module , submodule , module_type WHERE module.zone =(
			SELECT module.zone FROM module WHERE module.id=(
			SELECT submodule.module FROM submodule WHERE ip='$ip')) and submodule.module=module.id and module.type=module_type.id and state='activo'
			order by module_type asc";

	//echo $sql;
	$data = $db->doSql($sql);
	if($data){
	    do{
	        $id = $data['id_module'];
	        $moduleName = $data['module_name'];
	        $submoduleName = $data['submodule_name'];
	        $type=$data['type'];
	        $state=$data['state'];

	        $config[] = array(
	            'id' => $id,
	            'moduleName' => $moduleName,
	            'submoduleName' => $submoduleName,
	            'type' => $type,
	            'state' => $state
	        );
	    }while($data = pg_fetch_assoc($db->actualResults));
	    echo json_encode($config);
	}else{
		echo "nan";
	}

}


?>