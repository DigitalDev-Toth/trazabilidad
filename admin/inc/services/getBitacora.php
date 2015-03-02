<?php
include("../libs/db.class.php");

$data = $_REQUEST['data'];
$type = $_REQUEST['type'];
$data = explode(" ", $data);

if($type == 2){

    for($i=0;$i<count($data);$i++){//Se agregan los datos condicionales
        if($i==0){
            $whereName='(';
            $whereLastName='(';
        }
        $whereName.="LOWER(name) LIKE LOWER('%$data[$i]%')";
        $whereLastName.="LOWER(lastname) LIKE LOWER('%$data[$i]%')";
        if($i<count($data)-1){
            $whereName.=' OR ';
            $whereLastName.=' OR ';
        }else{
            $whereName.=')';
            $whereLastName.=')';
        }
    }

    $sql="SELECT * FROM patients 
    WHERE $whereName OR $whereLastName"; 

    $db = NEW DB();
    $patient=$db->doSql($sql);
    if($patient){
        do{
            $patientData[] = array(
                'id' =>  $patient['id'],
                'rut' =>  $patient['rut'],
                'name' =>  $patient['name'],
                'lastname' =>  $patient['lastname'],
                'birthdate' =>  $patient['birthdate'],
                'gender' =>  $patient['gender'],
                'address' =>  $patient['address']
            );
        }while($patient=pg_fetch_assoc($db->actualResults));
            
        echo json_encode($patientData);
    }else{
        echo "0";
    }

}else{
    include ('../../../services/WebservicePatientName.php');
    $rut = $data[0];
    echo json_encode(getPatientName($rut));

}
    
?>