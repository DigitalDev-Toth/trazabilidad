
<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','On');
session_start();
include("../libs/db.class.php");

$timeType= $_REQUEST['type'];

$submodule = $_REQUEST['submodule'];

$db = NEW DB();

$sql = "SELECT l.datetime AS tiempo, u.username AS user
        FROM logs l
        LEFT JOIN submodule s ON s.id=l.sub_module
        LEFT JOIN users u ON u.id=l.rut::bigint
        WHERE s.state='activo' AND l.sub_module=$submodule AND l.description LIKE '%Inicio de Sesión%' ORDER BY l.id DESC LIMIT 1";
$row = $db->doSql($sql);

$datetime = $row['tiempo'];

if(isset($datetime)){
    if($timeType=='ini'){
        $time=explode(" ", $datetime);
        echo '<div style="text-align:center">'.$time[1].'</div>';

    }elseif($timeType=='total'){
        $segundos=strtotime('now') - strtotime($datetime);
        $date = date("H:s", $segundos);
        $hour = $segundos/60/60;
        $minutes = ($segundos/60);
        $minutes = $minutes%60;
        if($hour<10) $hour="0".(int)$hour;
        else $hour=(int)$hour;
        if($minutes<10) $minutes="0".(int)$minutes;
        else $minutes=(int)$minutes;

        //echo '<div style="text-align:center">'.$hour.':'.$minutes.' hrs</div>';
        echo '<div style="text-align:center">'.$date.' hrs</div>';
    }else{
        echo $row['user'];
    }
}else{
    echo '';
}    



?>