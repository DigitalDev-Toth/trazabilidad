<?php
include ('../scripts/libs/db.class.php');
$idPatient = $_REQUEST['idPatient'];
$rutPatient = $_REQUEST['rutPatient'];

$db = NEW DB();
$sql = "SELECT id,rut FROM patient WHERE rut='$rutPatient'";
$row = $db->doSql($sql);

if(!$row){
    echo 0;
}else{
    $getMd5=md5($row["id"]);
    if($idPatient==$getMd5){
    	$id= $row["id"];
    	$db1 = NEW DB();
		$sql1 = "SELECT id FROM calendar WHERE patient=$id and exam_state='validado'";
		$row1 = $db1->doSql($sql1);
		if($row1){
		    do{
		        $id = $row1['id'];
		        $ids[] = array(
		            'id' => $id,
		        );
		    }while($row1 = pg_fetch_assoc($db1->actualResults));
		    echo json_encode($ids);
		}
       
    }else{
        echo 0;
    }
}
?>

