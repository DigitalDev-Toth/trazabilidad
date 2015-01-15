
<?php
include ('../../libs/db.class.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','On');
$db = NEW DB();
$zone = $_REQUEST['zone'];
$module = $_REQUEST['module'];

$date1 = $_REQUEST['date1'];
$date2 = $_REQUEST['date2'];


if(isset($zone) ){

  $sql = "SELECT logs.datetime,logs.module from logs
          left join tickets on tickets.logs = logs.id
          where zone = $zone and tickets.attention = 'served' and logs.datetime between '$date1' AND '$date2' and logs.module=$module order by logs.module ASC";  


  
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
        fix($dataContent);
        //echo json_encode($dataContent);
    }else{
      echo 0;
    }
  }else{
    echo 0;
  }

}else{
  echo 0;  
}


function fix($data){
  $fixed = array(0,0,0,0,0,0,0,0,0,0,0,0);
  for ($i=0; $i < count($data) ; $i++) { 
    $hour = explode(' ', $data[$i]['datetime']);
    //echo $hour[1]." ".$data[$i]['module']."<br>";
    if($hour[1] >= '08:00' && $hour[1] <= '08:59')$fixed[0]=$fixed[0]+1;
    if($hour[1] >= '09:00' && $hour[1] <= '09:59')$fixed[1]=$fixed[1]+1;
    if($hour[1] >= '10:00' && $hour[1] <= '10:59')$fixed[2]=$fixed[2]+1;
    if($hour[1] >= '11:00' && $hour[1] <= '11:59')$fixed[3]=$fixed[3]+1;
    if($hour[1] >= '12:00' && $hour[1] <= '12:59')$fixed[4]=$fixed[4]+1;
    if($hour[1] >= '13:00' && $hour[1] <= '13:59')$fixed[5]=$fixed[5]+1;
    if($hour[1] >= '14:00' && $hour[1] <= '14:59')$fixed[6]=$fixed[6]+1;
    if($hour[1] >= '15:00' && $hour[1] <= '15:59')$fixed[7]=$fixed[7]+1;
    if($hour[1] >= '16:00' && $hour[1] <= '16:59')$fixed[8]=$fixed[8]+1;
    if($hour[1] >= '17:00' && $hour[1] <= '17:59')$fixed[9]=$fixed[9]+1;
    if($hour[1] >= '18:00' && $hour[1] <= '18:59')$fixed[10]=$fixed[10]+1;
    if($hour[1] >= '19:00')$fixed[11]=$fixed[11]+1;
  }
  for ($i=0; $i < count($fixed) ; $i++) { 
    if($fixed[$i] == 0){
      $fixed[$i]=null;
    }
  }
  echo json_encode($fixed);


}

?>