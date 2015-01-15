<?php
include ('../admin/inc/libs/db.class.php');
header('Content-Type: text/html; charset=utf-8');


if($_REQUEST['type']==null) $type = '-'; //Inicio atención, Fin atención, Presupuesto
else $type = $_REQUEST['type']; //Inicio atención, Fin atención, Presupuesto
if($_REQUEST['id']==null) $rut = '-';
else $rut = $_REQUEST['id'];

$datetime = date('Y-m-d H:i:s');

if($type=='start'){
	if($_REQUEST['medicalid']==null) $medicalrut = '--';
	else $medicalrut = $_REQUEST['medicalid'];
	if($_REQUEST['medicalname']==null) $medicalname = '--';
	else $medicalname = $_REQUEST['medicalname'];

	$description = 'Inicio de atención, Médico '.$medicalname.', RUT '.$medicalrut;
	$action = 'in';
	echo $description.'<br/>';
}elseif($type=='end'){
	if($_REQUEST['medicalid']==null) $medicalrut = '--';
	else $medicalrut = $_REQUEST['medicalid'];
	if($_REQUEST['medicalname']==null) $medicalname = '--';
	else $medicalname = $_REQUEST['medicalname'];
	if($_REQUEST['origin']==null) $origin = '-';
	else $origin = $_REQUEST['origin'];

	if($_REQUEST['plan']==null) $plans = '';
	else $plans = $_REQUEST['plan']; //En caso de contener planes de tratamiento en un solo registro, indicar cada número con guiones de separación "120-500-423"
	$plan = explode('-', $plans);
	for ($i=0; $i < count($plan); $i++) { //Generalmente es 1 plan de tratamiento por paciente
		if($i==0)$plans = $plan[$i];
		else $plans.= "-".$plan[$i];
	}

	$description = 'Fin de atención, Médico '.$medicalname.', RUT '.$medicalrut.', Origen '.$origin.', plan(es) de tratamiento: '.$plans;
	$action = 'lb';
	echo $description.'<br/>';

}elseif($type=='budget'){
	if($_REQUEST['plan']==null) $plans = '';
	else $plans = $_REQUEST['plan']; //En caso de contener planes de tratamiento en un solo registro, indicar cada número con guiones de separación "120-500-423"
	
	if($_REQUEST['origin']==null) $origin = '-';
	else $origin = $_REQUEST['origin'];
	$plan = explode('-', $plans); 
	for ($i=0; $i < count($plan); $i++) { 
		if($i==0)$plans = $plan[$i];
		else $plans.= "-".$plan[$i];
	}

	$description = 'Presupuesto para plan de tratamiento: '.$plans.', Origen '.$origin;
	$action = 'in';
	echo $description.'<br/>';
}
	

//INSERCIÓN DE LOG
$db = NEW DB();
$sql = "INSERT INTO logs(rut,datetime,description,action) VALUES('$rut','$datetime','$description','$action')";
$db->doSql($sql);


//ID ÚLTIMO LOG
$sqlLog = "SELECT id FROM logs WHERE rut='$rut' ORDER BY id DESC LIMIT 1";
$log = $db->doSql($sqlLog);
$log = $log['id'];

if($plans!='' && ($type=='end' || $type=='budget')){
	$dbPlan = NEW DB();
	for ($j=0; $j < count($plan); $j++) {
		if($type=='end'){
			$sqlPlan = "INSERT INTO plan(plan_log,plan_number,origin) VALUES($log,".$plan[$j].",'".$origin."')";
			$dbPlan->doSql($sqlPlan);
		}elseif($type=='budget'){
			$sqlLogBudget = "SELECT COUNT(*) AS count FROM plan WHERE plan_number=".$plan[$j]." AND origin='".$origin."'";
			$logBudget = $db->doSql($sqlLogBudget);
			$logBudget = $logBudget['count'];
			if($logBudget==0){
				$sqlPlan = "INSERT INTO plan(plan_log,plan_number,budget_log,origin) VALUES($log,".$plan[$j].",$log,'".$origin."')";
				$dbPlan->doSql($sqlPlan);
			}else{
				$sqlPlan = "UPDATE plan SET budget_log=$log WHERE plan_number=".$plan[$j]." AND origin='".$origin."'";
				$dbPlan->doSql($sqlPlan);
			}
		}
	}
}




?>