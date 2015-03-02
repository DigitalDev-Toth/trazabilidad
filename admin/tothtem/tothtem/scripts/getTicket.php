<?php
include ('libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);
//get data
$module = $_REQUEST['module'];
$rut = $_REQUEST['rut'];
$moduleSpecial = $_REQUEST['moduleSpecial'];
$cometType = 'module';
$sbIp=$_REQUEST['ip'];
/*
        1 Hall Central
        2 Consultas Medicas
        3 Carpa
        4 Plataforma Central
        5 Plataforma Oriente
    */
	if($sbIp==1){
		$sbIp='192.168.0.122';
	}else if($sbIp==3){
		$sbIp='192.168.0.123';
	}else if($sbIp==4){
		$sbIp='192.168.0.124';
	}else if($sbIp==5){
		$sbIp='192.168.0.125';
	}

if($moduleSpecial=='0'){
	//$sbIp='192.168.0.122';
	//get last ticket
	$db = NEW DB();
	$sql = "SELECT ticket FROM last_tickets WHERE module='$module'";
	$lastTicket = $db->doSql($sql);
	$newticket=(int)$lastTicket["ticket"]+1; 
	if($newticket==1000){
	    $newticket=1;
	}
	//set last ticket
	$db2 = NEW DB();
	$db2->doSql("UPDATE last_tickets SET ticket='$newticket' WHERE module='$module'");

}else{
	//$sbIp='192.168.0.123';
	//get last ticket
	$db = NEW DB();
	$sql = "SELECT last_ticket FROM module_special WHERE id='$moduleSpecial'";
	$lastTicket = $db->doSql($sql);
	$newticket=(int)$lastTicket["last_ticket"]+1; 
	if($newticket==1000){
	    $newticket=1;
	}
	//set last ticket
	$db2 = NEW DB();
	$db2->doSql("UPDATE module_special SET last_ticket=$newticket WHERE id='$moduleSpecial'");
}
//INSERCIÓN DE LOGS
//$sbIp='192.168.0.123';


$sqlZone="SELECT zone.id AS zone , submodule.id AS submodule 
          FROM zone 
          LEFT JOIN module ON module.zone = zone.id 
          LEFT JOIN submodule ON submodule.module=module.id 
          WHERE submodule.ip='$sbIp'";

$db3=NEW DB();
$results=$db3->doSql($sqlZone);
$zone=$results['zone'];
$subModule=$results['submodule'];

$datetime = date('Y-m-d H:i:s');
$description = "Retiro de ticket Nº $newticket , Módulo $module";
$dbLog = NEW DB();
$sql = "INSERT INTO logs(rut,datetime,description,zone,action,sub_module,module) VALUES('$rut','$datetime','$description',$zone,'to',$subModule,$module)";
$dbLog->doSql($sql);

$sql = "SELECT id FROM logs ORDER BY id DESC LIMIT 1";
$idLog = $dbLog->doSql($sql);


if($moduleSpecial=='0'){
	//Return module alias
	$dbAlias = NEW DB();
	$sql = "SELECT alias FROM module WHERE id=$module";
	$alias = $dbAlias->doSql($sql);
}else{
	//Return module alias
	$dbAlias = NEW DB();
	$sql = "SELECT alias FROM module_special WHERE id=$moduleSpecial";
	$alias = $dbAlias->doSql($sql);
}

//insert new ticket patient


$newticket=$newticket.$alias['alias'];
$db4 = NEW DB();
$db4->doSql("INSERT INTO tickets(logs,ticket,attention) VALUES (".$idLog['id'].",'$newticket','waiting')");

//$ticketData = array('newticket' => $newticket, 'modality' => $module, 'rut' => $rut);
//$ticketData[0] = array('newticket' => $newticket, 'modality' => $module, 'date_t' => date("Y-m-d"), 'hour_start' => date("H:i:s"), 'hour_end' => 'NaN', 'rut' => $rut);

//////////////////WEBSERVICE//////////////////////////////
include('../../../../services/WebservicePatientName.php');
$patientData = getPatientName($rut);

		/*if(substr($rut,-2,-1)=='-'){
		    $type=1;
		}else{
		    $type=3;
		    $rutA=$rut;
		}

		if($type==1){
		    $rutA = str_replace(".","", $rut);
		    $rutA = str_replace("-","", $rutA);
		    $rutA = substr_replace($rutA ,"",-1);
		}

		exec('curl --data "intTipoDoc='.$type.'&strNroDoc='.$rutA.'" http://201.238.201.37:84/Service.asmx/traeDatosPaciente', $out, $err);

		/*
		27 - Nombres
		28 - Apellido Paterno
		29 - Apellido Materno
		*/
		/*if($out[25]=='<NewDataSet xmlns="">'){
		    $patientName = $out[27].' '.$out[28].' '.$out[29];
		}else{
			$patientName = 'Paciente Nuevo';
		}*/
		if($patientData[0]['name']!=null){
		    $patientName = $patientData[0]['name'].' '.$patientData[0]['lastname'];
		}else{
			$patientName = 'Paciente Nuevo';
		}
//////////////////////////////////////////////////////////


//COMET para visualización
//$ticketData[1] = array('comet' => $cometType,'rut' => $rut, 'datetime' => $datetime, 'description' => $description, 'zone' => $zone, 'action' => 'to', 'submodule' => $subModule, 'module' => $module);
$ticketData = array('comet' => $cometType,'rut' => $rut, 'datetime' => $datetime, 'description' => $description, 'zone' => $zone, 'action' => 'to', 'submodule' => $subModule, 'module' => $module,'newticket' => "$newticket", 'name' => $patientName);

echo json_encode($ticketData);
?>

