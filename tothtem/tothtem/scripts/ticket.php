<?php
include ('libs/db.class.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);


if(isset($_REQUEST['moduleId']) && isset($_REQUEST['type'])){
	
	$moduleId=$_REQUEST['moduleId'];
	$type = $_REQUEST['type'];

	$db = NEW DB();

	if($type=='getLastTicket'){
		$sql="SELECT ticket from last_tickets where module='$moduleId' ";
		$data=$db->doSql($sql);
		$newticket=(int)$data["ticket"]+1; 
		if($newticket==1000){
		    $newticket=1;
		}


		echo $newticket;

	}


	if($type=='insertTicket'){

	}


	if($type=='updateTicket'){

	}
	

}


?>