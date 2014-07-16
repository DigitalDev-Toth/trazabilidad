<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }

include("libs/db.class.php");
include("controls.php");

$zone = new DB("zone", "id");
//$zone->exceptions(array("zone", "password"));

makeControls($zone, "modules/zoneForm.php", "modules/zoneDelete.php", "modules/zoneUpdate.php", $_SERVER['HTTP_REFERER']);

$zone->showControls();
echo '<div algin="center" id="showTitle">Zonas</div>';

//$where = array();
$rows = $zone->select();
echo $zone->showData($rows, TRUE);
?>
