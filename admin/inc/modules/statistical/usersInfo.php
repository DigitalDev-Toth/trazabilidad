
<?php
include ('../../libs/db.class.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','On');
$db = NEW DB();
$data = $_REQUEST['data'];
$data2 = $_REQUEST['data2'];
$date1 = $_REQUEST['date1'];
$date2 = $_REQUEST['date2'];


if(isset($data) ){

  if($data2 == '0'){
    $sql = "SELECT users.id , users.name from logs
            left join users on users.id=logs.users
            where users is not null and zone = $data
            group by users.id ,users.name";  
  }else{
    $sql = "SELECT users.id , users.name from logs
            left join users on users.id=logs.users
            where users is not null and zone = $data and module=$data2
            group by users.id ,users.name";  
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

        for ($i=0; $i < count($dataContent); $i++) { 
            $dataContent[$i]['others']= tableContent($dataContent[$i]['id'],$data);
        }

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

function tableContent($user,$zone){

//$user = $_REQUEST['user'];
//$user = $_REQUEST['user'];
$date1 = $_REQUEST['date1'];
$date2 = $_REQUEST['date2'];
$data2 = $_REQUEST['data2'];
//change date!!
$db = NEW DB();

if($data == 0){
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
        "user" => $logs['sub_module']
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



$maxtime = getTimeString($servedMaxTime);
$mintime = getTimeString($servedMinTime);
if($servedCount==0){
  $average=getTimeString($servedTimeTotal);
}else{
  $average=getTimeString($servedTimeTotal/$servedCount);
}

$returnData = array('served_tickets' => $servedCount, 'maxtime' => $maxtime,'mintime' => $mintime,'average' => $average);
return ($returnData);


}
?>