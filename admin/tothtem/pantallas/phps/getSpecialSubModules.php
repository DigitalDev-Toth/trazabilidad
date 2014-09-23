<?php

include ('../../tothtem/scripts/libs/db.class.php');

if( isset( $_REQUEST['module'] ) ){

    $module = $_REQUEST['module'];
    $db = NEW DB();
    $sql = "SELECT name 
			from module_type 
			where id= (select type from module where id = $module)";
    $type = $db->doSql($sql);
    if($type['name'] == 'Especial'){
	    $ticket = $_REQUEST['ticket'];
	    $db = NEW DB();
	    $sql = "SELECT id 
	    		from module_special 
	    		where module = $module and alias = '$ticket'";
	    $idSpecialModule = $db->doSql($sql);
	    echo $idSpecialModule['id'];

    }else{
    	echo 0;
    }


}

?>