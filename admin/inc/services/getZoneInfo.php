<?php
include ('../libs/db.class.php');
$db = NEW DB();
$sql = "SELECT id,name from zone order by id";
$row = $db->doSql($sql);

if($row){



do{
    $data[] = array(
        "id" => $row['id'],
        "name" => $row['name'],

    );
} while($row=pg_fetch_assoc($db->actualResults));

echo json_encode($data);


}else{
	echo 0;
}

?>