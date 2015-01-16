<?php
include("../libs/db.class.php");

$rut = $_REQUEST['rut'];


//PLANES DE TRATAMIENTO GENERADOS
$sql = "SELECT COUNT(*) AS count FROM logs l, plan p WHERE p.plan_log=l.id AND description LIKE '%Fin de atención%' AND rut='$rut'";   

$db = new DB();

$result = $db->doSql($sql);

/*do{
    echo $result
} while($result=pg_fetch_assoc($db->actualResults));*/


//PRESUPUESTOS GENERADOS
$sql = "SELECT COUNT(*) AS count FROM logs l, plan p WHERE p.budget_log=l.id AND description LIKE '%Presupuesto%' AND rut='$rut'";   

$db = new DB();

$result2 = $db->doSql($sql);

$data=array(
    "planes" => $result['count'],
    "pres" => $result2['count']
);

echo json_encode($data);

?>