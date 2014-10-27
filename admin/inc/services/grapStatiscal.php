
<?php
include ('../libs/db.class.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','On');
$db = NEW DB();

$typeRequest = $_REQUEST['type'];
$data = $_REQUEST['data'];
$interval = $_REQUEST['interval'];
$date1 = $_REQUEST['date1'];
$date2 = $_REQUEST['date2'];

if(isset($typeRequest) && isset($data) ){
  $sql = '';


  if($typeRequest == 'zn'){
    $sql = "SELECT count(id) as cantidad, date_trunc('$interval',datetime) as hora 
            FROM logs
            where description like '%ticket%' and datetime between '$date1' and '$date2' and zone = $data
            GROUP BY hora
            ORDER BY hora";
  }
  if($typeRequest == 'mSm'){// productividad
     $sql = "SELECT module , count(module) as cantidad ,  date_trunc('hour',datetime) as hora
              FROM logs, tickets
              WHERE tickets.logs = logs.id and logs.zone=$data and logs.description IN ('Ticket Finalizado','Ticket Derivado','Ticket ha venido')  and logs.datetime between '$date1' AND '$date2'
              group by module , hora";
  }
   if($typeRequest == 'tt'){
    $module = $_REQUEST['module'];
    $sql = "SELECT module.name,logs.module as id , count(logs.module) as cantidad , date_trunc('$interval',datetime) as hora
            FROM public.logs, public.module
            WHERE module.id = logs.module AND logs.datetime between '$date1' AND '$date2' AND logs.zone=$data and logs.module = $module
            AND logs.description LIKE '%Retiro%'  
            group by hora,module.name , logs.module
            order by logs.module,hora ASC";
  
  /*$sql = "SELECT module.name,logs.module as id ,logs.datetime
            FROM public.logs, public.module
            WHERE module.id = logs.module AND logs.datetime between '$date1' AND '$date2' AND logs.zone=".$data." AND logs.description LIKE '%Retiro%'  order by logs.datetime ASC" ;*/
  }




  if($sql != ''){
    $row = $db->doSql($sql);
    if($row){
        $i=0;
        do {
          foreach ($row as $field=>$value) {
            $dataContent[$i][$field] = $value;
          }
          $i++;
        } while($row=pg_fetch_assoc($db->actualResults));
        echo json_encode($dataContent);
    }else{
      echo 0;
    }
  }else{
    echo 0;
  }

}








?>