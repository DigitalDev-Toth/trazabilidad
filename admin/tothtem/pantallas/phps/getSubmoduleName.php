<?php

include ('../../tothtem/scripts/libs/db.class.php');

if( isset( $_REQUEST['sub_module'] ) ){

    $sub_module = $_REQUEST['sub_module'];
    $db = NEW DB();
    $sql = "SELECT name FROM submodule WHERE id = $sub_module ";
    $name = $db->doSql($sql);
    echo $name['name'];
}else{
    echo 'NoOK';
}

?>