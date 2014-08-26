<?php
include ('../scripts/libs/db.class.php');
$rut = $_REQUEST['rut'];
$db = NEW DB();
$sql = "SELECT * FROM patient WHERE rut='$rut'";
$row = $db->doSql($sql);

if(!$row){
    //agregar nuevo
    echo 0;
}else{
    $sql = "SELECT * FROM patient WHERE rut='$rut'";
    $row = $db->doSql($sql);
    if($row){
        session_start();
        $_SESSION['usuario']=$rut;
        $_SESSION['tiempo']=time();
        echo 1;
    }
    else{
        echo 2;
    }

    
}
?>