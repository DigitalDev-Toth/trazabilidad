<?php

include ('../../tothtem/scripts/libs/db.class.php');

if( isset( $_REQUEST['module'] ) ){

    $module = $_REQUEST['module'];
    $alias = $_REQUEST['alias'];
    $db = NEW DB();
    $sql = "SELECT *
			from module_special 
			where module = $module and alias = '$alias'";
    $name = $db->doSql($sql);
    echo $name['name'];
}

?>