
<?php
include ('../../libs/db.class.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','On');
$db = NEW DB();

$id = $_REQUEST['id'];
$action = $_REQUEST['action'];
$state = $_REQUEST['state'];

if( $action == 'function' ){
	echo $state;
	if($state== 'true'){
		$start = $_REQUEST['start'].':00';
		$end = $_REQUEST['end'].':00';
		$sql = "UPDATE schedules set type = $state, hstart='$start', hend = '$end' where tothtem = $id";
	}else{
		$sql = "UPDATE schedules set type = false where tothtem = $id";
	}
	
}
if( $action == 'state' ){
	$sql = "UPDATE schedules set state = $state where tothtem = $id";
}
$db->doSql($sql);
echo "ok";








?>