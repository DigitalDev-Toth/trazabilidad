<?php
include ('../../../inc/libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);

$module = $_REQUEST['module'];

$db = NEW DB();
$sql = "SELECT * FROM module_special WHERE module=$module";

$data = $db->doSql($sql);
if($data){
    do{
        $config[] = array(
        	'id' => $data['id'],
            'name' => $data['name'],
            'alias' => $data['alias']
        );
    }while($data = pg_fetch_assoc($db->actualResults));
    echo json_encode($config);
}else{
	echo "nan";
}



?>