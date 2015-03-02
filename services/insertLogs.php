<?php
include ('../../../inc/libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

$rut = $_REQUEST['rut'];
$description =$_REQUEST['description'];
$action=$_REQUEST['action'];
$cometType=$_REQUEST['cometType'];
$subModule=$_REQUEST['subModule'];
$datetime = date("Y-m-d H:i:s");
$attentionNew = $_REQUEST['attentionNew'];
$idTicket = $_REQUEST['ticketId'];
$users = $_SESSION['UserId'];

$goComet = true; //Para los casos en que no queden tickets al llamar de manera simultánea

if(!isset($_REQUEST['module'])){
	$sqlZone="SELECT zone.id AS zone, submodule.module AS module
			  FROM zone 
			  LEFT JOIN module ON module.zone = zone.id 
			  LEFT JOIN submodule ON submodule.module=module.id 
			  WHERE submodule.id='$subModule'";
	$db2=NEW DB();
	$results=$db2->doSql($sqlZone);
	$zone=$results['zone'];
	$module=$results['module'];

}else{
	//PARA EL CASO DE QUE SEA DERIVADO
	$module=$_REQUEST['module'];

	$sqlZone="SELECT zone
			  FROM module 
			  WHERE id=$module";
	$db2=NEW DB();
	$results=$db2->doSql($sqlZone);
	$zone=$results['zone'];
}

//CORROBORACION DE LLAMADO DE TICKETS SIMULTANEAMENTE
if($attentionNew=='on_serve'){
	$sqlNoRepeatTicket="SELECT COUNT(*)
			  FROM tickets
			  WHERE id=$idTicket AND attention='on_serve'";

			  /*$sqlNoRepeatTicket="SELECT COUNT(*)
			  FROM logs l
			  LEFT JOIN tickets t ON t.logs=l.id
			  WHERE t.id=$idTicket AND l.action='in'";*/
	$dbNoRepeat=NEW DB();
	$resultsTicket=$dbNoRepeat->doSql($sqlNoRepeatTicket);
	$ticketState=$resultsTicket['count'];

	if($ticketState>0){//SI EL TICKET YA FUE UTILIZADO POR OTRO SUBMÓDULO - ESTO OCURRE CUANDO 2 O MÁS SUBMÓDULOS HERMANOS LLAMAN UN TICKET A LA VEZ
		//get module_type
		$dbModule = NEW DB();
		$sql = "SELECT mt.name
				FROM module_type mt
				LEFT JOIN module m ON m.type=mt.id
				LEFT JOIN submodule s ON s.module=m.id
				WHERE s.id=$subModule";
		$moduleName = $dbModule->doSql($sql);

		$module_type = $moduleName['name'];

		//get last ticket
		$dbNextTicket = NEW DB();
		if($module_type!='Especial'){
			$sql = "SELECT l.rut AS rut, t.id AS ticketid 
					FROM tickets t
					LEFT JOIN logs l ON l.id=t.logs
					LEFT JOIN submodule s ON s.module=l.module
					WHERE s.id=$subModule AND t.attention IN ('waiting','derived') AND l.datetime>'".date('Y-m-d')."' ORDER BY l.datetime ASC LIMIT 1";
		}else{
			$sql = "SELECT l.rut AS rut,t.id AS ticketid 
					FROM tickets t
					LEFT JOIN logs l ON l.id=t.logs
					LEFT JOIN submodule s ON s.module=l.module
					WHERE s.id=$subModule AND t.attention IN ('waiting','derived') AND l.datetime>'".date('Y-m-d')."' ORDER BY SUBSTR (ticket, Length (ticket)), l.datetime ASC LIMIT 1";
		}
		$lastRecord = $dbNextTicket->doSql($sql);
		if($lastRecord){
			$rut = $lastRecord['rut'];
			$idTicket = $lastRecord['ticketid'];
		}else{
			$goComet = false;
		}
	}
}

if($goComet == true){
	$db = NEW DB();
	$sql = "INSERT INTO logs(rut,datetime,description,zone,action,sub_module,module,users) VALUES('$rut','$datetime','$description',$zone,'$action',$subModule,$module,$users)";

	$db->doSql($sql);


	//Obtener LOG
	$dbLogNew = NEW DB();
	$sqlLogNew = "SELECT id FROM logs ORDER BY id DESC LIMIT 1";
	$resultLogNew = $dbLogNew->doSql($sqlLogNew);
	$log=$resultLogNew['id'];

	$db3 = NEW DB();

	$sqlTicket = "UPDATE tickets SET attention='$attentionNew', logs=$log WHERE id=$idTicket";
	$db3->doSql($sqlTicket);


	//Obtener NUMERO TICKET
	$dbTicket = NEW DB();
	$sqlTicket2 = "SELECT ticket FROM tickets WHERE id=$idTicket";
	$resultTicket = $dbTicket->doSql($sqlTicket2);
	$newticket=$resultTicket['ticket'];


//////////////////WEBSERVICE//////////////////////////////
	/*$con = pg_connect("host=biopacs.com port=5432 dbname=es14b_hrt2 user=postgres password=justgoon") or die('NO HAY CONEXION: ' . pg_last_error());

	$sql="SELECT * FROM patient WHERE rut='$rut'";
	$resultado = pg_query($con, $sql);
	$row = pg_numrows($resultado);
	if($row){
	    $patientName = pg_result($resultado,0,2).' '.pg_result($resultado,0,3);
	}else{
		$patientName = 'Paciente Nuevo';
	}*/
	if(substr($rut,-2,-1)=='-'){
	    $type=1;
	}else{
	    $type=3;
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
	if($out[25]=='<NewDataSet xmlns="">'){
	    $patientName = $out[27].' '.$out[28].' '.$out[29];
	}else{
		$patientName = 'Paciente Nuevo';
	}
//////////////////////////////////////////////////////////

	//Comet es el encabezado que corresponde al tipo de comet a entregar: ejemplo : comet tipo tothtem , comet tipo gestion

	$returnComet = array('comet' => $cometType,'rut' => $rut, 'datetime' => $datetime, 'description' => $description, 'zone' => $zone, 'action' => $action, 'submodule' => $subModule, 'module' => $module, 'newticket' => $newticket, 'idticket' => $idTicket, 'name' => $patientName);
	//$returnComet = array('comet' => $cometType,'rut' => $rut, 'datetime' => $datetime, 'description' => $description, 'zone' => $zone, 'action' => $action, 'submodule' => $subModule, 'module' => $module);
	echo json_encode($returnComet);
}else{
	echo 0;
}
?>