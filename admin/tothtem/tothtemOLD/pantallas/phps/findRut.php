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
    $id=$row['id'];
/*
    $sql2 = "SELECT * FROM calendar WHERE id='$id'";
    $row2 = $db->doSql($sql2);

    
        session_start();
        $name = $row['name'];
        $lastname = $row['lastname'];

        $_SESSION['rut']=$rut;
        $_SESSION['name']=$name;
        $_SESSION['lastname']=$lastname;

        $_SESSION['tiempo']=time();
        */
        echo $id;
    

    
}
?>