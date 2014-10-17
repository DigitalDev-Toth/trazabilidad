
<?php
include ('../libs/db.class.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','On');
$db = NEW DB();

$typeRequest = $_REQUEST['type'];
$data = $_REQUEST['data'];

if(isset($typeRequest) && isset($data) ){
  $sql = '';
  if($typeRequest == 'mSm'){ //modules and subModules

    $sql = 'SELECT  module.name as modulename ,submodule.id as submoduleid, submodule.users as username ,submodule.module as module, submodule.name as submodulename,submodule.state as submodulestate,users.name as user
    FROM   public.zone,   public.module,   public.submodule , public.users
    WHERE  zone.id = module.zone AND   submodule.module = module.id and users.id=submodule.users and zone.id = '.$data.' AND module.type != 1 order by module ASC,submodulename ASC';
  }
  if($typeRequest == 'wtg'){ //Waiting
    

    $date1 = date("Y-m-d"); 
    //$date1 = date("2014-10-10");
    $date2 = date("Y-m-d");

    $date2 = date('Y-m-d', strtotime($date1 . ' + 1 day'));// dejar en '+ 1 day'

    $sql = "SELECT logs.datetime
            FROM public.logs, public.tickets
            WHERE tickets.logs = logs.id AND  (attention = 'waiting' or attention = 'derived')  and logs.zone=".$data." and logs.datetime between '$date1' AND '$date2'";
  }

  if($typeRequest == 'pd'){
    $date1 = date("Y-m-d"); 
    $date2 = date('Y-m-d', strtotime($date1 . ' + 1 day'));
    $sql = "SELECT datetime,description from logs where logs.datetime between '$date1' AND '$date2' AND description like '%Usuario%' and module=$data order by datetime";
  }

  if($typeRequest == 'tt'){//totem
  
    
    $date1 = date("Y-m-d"); 
    //$date1 = date("2014-10-09");
    $date2 = date("Y-m-d");
    $date2 = date('Y-m-d', strtotime($date1 . ' + 1 day'));// dejar en '+ 1 day'
    $sql = "SELECT module.name,logs.module as id ,logs.datetime
            FROM public.logs, public.module
            WHERE module.id = logs.module AND logs.datetime between '$date1' AND '$date2' AND logs.zone=".$data." AND logs.description LIKE '%Retiro%'  order by logs.datetime ASC" ;
    
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
        
        //

        echo json_encode($dataContent);
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



//$submodule = $_REQUEST['submodule'];
//$user = $_REQUEST['user'];
$date = date('Y-m-d');

//change date!!
$db = NEW DB();
$sql = "SELECT * FROM logs 
    WHERE sub_module=$submodule AND datetime >= '".$date."' 
    AND length(cast (rut as text))>10
    AND users=$user
    AND description IN ('Ticket Finalizado','Ticket Derivado','Ticket ha venido') 
    AND datetime < ('".$date."'::date + '1 day'::interval) ORDER BY rut,id";

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


for($i=0;$i<count($data);$i++){
  if($data[$i]['description']=='Ticket ha venido' || $data[$i]['description']=='Ticket Derivado' && $i+1<count($data)){
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