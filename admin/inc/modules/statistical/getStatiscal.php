
<?php
include ('../../libs/db.class.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','On');
$db = NEW DB();
$typeRequest = $_REQUEST['type'];
$data = $_REQUEST['data'];
$data2 = $_REQUEST['data2'];
$date1 = $_REQUEST['date1'];
$date2 = $_REQUEST['date2'];


if(isset($typeRequest) && isset($data) ){
  $sql = '';
  if($typeRequest == 'mSm'){ //modules and subModules
    if($data2 == '0'){
      $sql = 'SELECT  module.name as modulename ,submodule.id as submoduleid, submodule.users as username ,submodule.module as module, submodule.name as submodulename,submodule.state as submodulestate,users.name as user
              FROM   public.zone,   public.module,   public.submodule , public.users
              WHERE  zone.id = module.zone AND submodule.module = module.id and users.id=submodule.users and zone.id = '.$data.' AND module.type != 1 order by module ASC,submodulename ASC';
    }else{
      $sql = 'SELECT  module.name as modulename ,submodule.id as submoduleid, submodule.users as username ,submodule.module as module, submodule.name as submodulename,submodule.state as submodulestate,users.name as user
              FROM   public.zone,   public.module,   public.submodule , public.users
              WHERE  zone.id = module.zone AND submodule.module = module.id and users.id=submodule.users and zone.id = '.$data.' AND module.type != 1  and module.id='.$data2.' order by module ASC,submodulename ASC';
    }
    
  }
  if($typeRequest == 'wtg'){ //Waiting
    if($data2 == '0'){
      $sql = "SELECT logs.datetime
              FROM public.logs, public.tickets
              WHERE tickets.logs = logs.id AND  (attention = 'waiting' or attention = 'derived')  and logs.zone=".$data." and logs.datetime between '$date1' AND '$date2'";
    }else{
      $sql = "SELECT logs.datetime
              FROM public.logs, public.tickets
              WHERE tickets.logs = logs.id AND  (attention = 'waiting' or attention = 'derived')  and logs.zone=".$data." and logs.datetime between '$date1' AND '$date2' and logs.module=$data2 ";  
    }
    
  }

  if($typeRequest == 'pd'){// productividad
    if($data2 == '0'){
        $sql = "SELECT datetime,description,users FROM logs WHERE logs.datetime BETWEEN '$date1' AND '$date2' AND description LIKE '%Usuario%' AND zone=$data ORDER BY users,datetime";
        //echo $sql;
    }else{
        $sql = "SELECT datetime,description,users FROM logs WHERE logs.datetime BETWEEN '$date1' AND '$date2' AND description LIKE '%Usuario%' AND zone=$data AND logs.module=$data2 ORDER BY users,datetime";
    }
  
  }


  if($typeRequest == 'os'){// on server
    $sql="SELECT tickets.attention,logs.module,logs.datetime
          FROM logs, tickets
          WHERE tickets.logs = logs.id and tickets.attention = 'on_serve' and logs.datetime between '$date1' and '$date2'";
  }


  if($typeRequest == 'tt'){//totem
    $sql = "SELECT module.name,logs.module as id ,logs.datetime
            FROM public.logs, public.module
            WHERE module.id = logs.module AND logs.datetime between '$date1' AND '$date2' AND logs.zone=".$data." AND logs.description LIKE '%Retiro%'  order by logs.datetime, logs.module ASC" ;
  }
  if($typeRequest == 'att'){
    if($data2== '0'){
      $sql = "SELECT tickets.attention
            FROM public.tickets, public.logs
            WHERE tickets.logs = logs.id and logs.zone=".$data." AND logs.datetime between '$date1' AND '$date2'" ;
    }else{
      $sql = "SELECT tickets.attention
            FROM public.tickets, public.logs
            WHERE tickets.logs = logs.id and logs.zone=".$data." AND logs.datetime between '$date1' AND '$date2' and logs.module = $data2" ;
    }
    
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
          if($typeRequest == 'mSm'){
              for ($i=0; $i < count($dataContent); $i++) { 
                $dataContent[$i]['others']= tableContent($dataContent[$i]['submoduleid'],$dataContent[$i]['username']);
              }
          }


          if($typeRequest == 'pd'){
              $ini = 0; //Variable indicará si es inicio o fin de tiempo
              $lastuser = '';
              $datetime = 0;

              for ($i=0; $i < count($dataContent); $i++) { 

                  if($lastuser!=$dataContent[$i]['users']) $datetime=0;
                  if($ini==0){//Se consulta si ya hay un tiempo de inicio
                      //Se indica inicio de tiempo y usuario actual
                      $ini = strtotime($dataContent[$i]['datetime']);
                      $lastuser = $dataContent[$i]['users'];

                      if($i+1==count($dataContent)){
                          $datetime = $datetime + strtotime(date("Y-m-d H:i:s"))-$ini;
                          $ini = 0;
                          $userTime[$lastuser] = getTimeString($datetime);
                      }
                  }else{
                      if($lastuser==$dataContent[$i]['users']){
                          //Cálculo de tiempo entre un log y otro. Se consulta si realmente es un log de fin/pausa de sesión, para evitar logs erróneos (doble inicio de sesión, por ejemplo)
                          if($dataContent[$i]['description']=='Cierre de Sesión Usuario: '.$dataContent[$i]['users'] || $dataContent[$i]['description']=='Pausa de Sesión Usuario: '.$dataContent[$i]['users']){
                              $datetime = $datetime + strtotime($dataContent[$i]['datetime'])-$ini;
                              $ini = 0;
                              $userTime[$lastuser] = getTimeString($datetime);
                          }
                      }else{
                          $datetime = $datetime + strtotime(date("Y-m-d H:i:s"))-$ini;
                          $ini = 0;
                          $userTime[$lastuser] = getTimeString($datetime);
                          $i--;
                          $datetime = 0;
                      }
                  }
              }
              echo json_encode($userTime);
          }else{
              echo json_encode($dataContent);
          }

      }else{
          echo 0;
      }
  }else{
     echo 0;
  }

}else{
    echo 0;  
}


  

function getTimeString($timeSeconds){
  $timeSeconds = round($timeSeconds);
  $seconds = ($timeSeconds%3600)%60;
  $minutes = (($timeSeconds%3600)-$seconds)/60;
  $hours = ($timeSeconds-($timeSeconds%3600))/3600;
  
  if($seconds<=9) $seconds = '0'.$seconds;
  if($minutes<=9) $minutes = '0'.$minutes;
  if($hours<=9) $hours = '0'.$hours;

  return $hours.':'.$minutes.':'.$seconds;
}

function tableContent($submodule,$user){


$zone = $_REQUEST['data'];

$data2 = $_REQUEST['data2'];
$date1 = $_REQUEST['date1'];
$date2 = $_REQUEST['date2'];

//change date!!
$db = NEW DB();
if($data2 == 0){
  $sql = "SELECT * FROM logs 
    WHERE length(cast (rut as text))>1
    AND description IN ('Ticket Finalizado','Ticket Derivado','Ticket ha venido') 
    and datetime between '".$date1."' and '".$date2."' and zone = $zone and users = $user
    ORDER BY rut,id";
}else{
  $sql = "SELECT * FROM logs 
    WHERE length(cast (rut as text))>1
    AND description IN ('Ticket Finalizado','Ticket Derivado','Ticket ha venido') 
    and datetime between '".$date1."' and '".$date2."' and zone = $zone and users = $user and module = $data2
    ORDER BY rut,id";
}

$logs = $db->doSql($sql);

do{
    $data[] = array(
        "id" => $logs['id'],
        "rut" => $logs['rut'],
        "datetime" => $logs['datetime'],
        "description" => $logs['description'],
        "submodule" => $logs['sub_module']
    );
} while($logs=pg_fetch_assoc($db->actualResults));


$servedCount=0;
$servedTimeTotal=0;
$servedMaxTime=0;
$servedMinTime=0;

//Ticket Finalizado','Ticket Derivado','Ticket ha venido'
for($i=0;$i<count($data);$i++){
  if($data[$i]['description']=='Ticket ha venido' || $data[$i]['description']=='Ticket Derivado'  && $i+1<count($data)){
    if($data[$i]['rut']==$data[$i+1]['rut'] && ($data[$i+1]['description']=='Ticket Derivado' || $data[$i+1]['description']=='Ticket Finalizado')){
      $servedTime = strtotime($data[$i+1]['datetime']) - strtotime($data[$i]['datetime']);
      if($servedCount==0)$servedMinTime = $servedTime;
      $servedTimeTotal =  $servedTimeTotal + $servedTime;
      if($servedMaxTime<$servedTime) $servedMaxTime = $servedTime;
      if($servedMinTime>$servedTime) $servedMinTime = $servedTime;
      $servedCount++;
    }
  }

}



$maxtime = date('Y-m-d').' '.getTimeString($servedMaxTime);
$mintime = date('Y-m-d').' '.getTimeString($servedMinTime);
if($servedCount==0){
  $average=date('Y-m-d').' '.getTimeString($servedTimeTotal);
}else{
  $average=date('Y-m-d').' '.getTimeString($servedTimeTotal/$servedCount);
}

$returnData = array('served_tickets' => $servedCount, 'maxtime' => $maxtime,'mintime' => $mintime,'average' => $average);
return ($returnData);


}




?>