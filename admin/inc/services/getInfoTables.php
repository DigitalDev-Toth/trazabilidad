
<?php
include ('../libs/db.class.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','On');
$db = NEW DB();

$typeRequest = $_REQUEST['type'];
$data = $_REQUEST['data'];

if(isset($typeRequest) && isset($data) ){

  $sql='';
  if($typeRequest == 'mSm'){ //modules and subModules

    $sql = 'SELECT  module.name as modulename , submodule.module as module, submodule.name as submodulename,submodule.state as submodulestate
    FROM   public.zone,   public.module,   public.submodule
    WHERE  zone.id = module.zone AND   submodule.module = module.id  and zone.id = '.$data.' and module.type != 1 order by module ASC,submodulename ASC';

    



  }
  if($typeRequest == 'un'){//users

  }
  if($typeRequest == 'tt'){//totem
  

    //$date1 = date("Y-m-d");
    $date1 = date("2014-10-06");
    $date2 = date("Y-m-d");
    $date2 = date('Y-m-d', strtotime($date1 . ' + 2 day'));
    
    $sql = "SELECT module.name, logs.module as id , logs.datetime
            from  public.logs,public.module
            where logs.module = module.id 
            AND datetime between '$date1' and '$date2' and description like '%Retiro%' order by datetime ASC ";
    
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

}else{
  echo 0;  
}


  








?>