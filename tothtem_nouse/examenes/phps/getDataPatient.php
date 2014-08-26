<?php
include ('../scripts/libs/db.class.php');
$rut = $_REQUEST['rut'];
$db = NEW DB();
$sql = "SELECT * FROM patient WHERE rut='$rut'";
$data = $db->doSql($sql);
if($data){
    do{
        $rut = $data['rut'];
        $name = $data['name'];
        $lastname=$data['lastname'];

        $allData[] = array(
            'rut' => $rut,
            'name' => $name,
            'lastname' => $lastname
        );
    }while($data = pg_fetch_assoc($db->actualResults));
    echo json_encode($allData);
}
    

?>