<?php
include ('libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);

if(isset($_REQUEST['ip'])){
	//$ip = $_REQUEST['ip'];
	$ip='192.168.0.122';
	$db = NEW DB();
	$sql = "SELECT  module.id ,module.name 
			FROM module , submodule , module_type WHERE module.zone =(
			SELECT module.zone FROM module WHERE module.id=(
			SELECT submodule.module FROM submodule WHERE ip='$ip')) and submodule.module=module.id and module.type=module_type.id 
			and state='activo' and module_type.name!='Tothtem'  group by module.id ,module.name 
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

}


?>