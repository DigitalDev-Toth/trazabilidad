<?php

// ******* ********* ********* ********* ********* ********* ********* *********

include_once('comun.php');

define('PATH_TMP', '/tmp/');
define('PATH_WGET', '/usr/bin/wget');
define('MAX_SIZE_FLASH', '2880');
// define('TAM_MAX_THUMB', 120);
//echo 'entre a (pacsGatewayDummy.php) <BR>';mio

if (isset($_GET['xml'])) {
  //echo 'entre al 1º if de (pacsGatewayDummy.php) <BR>';mio
  header("Content-type: text/xml");
  $sop_iuid = get_sop_iuid();
  $fileName = './DEMO/'.$sop_iuid.'.xml';
  readfile($fileName);
}
else if (isset($_GET['patId'])) {
  //echo 'entre al 1º else if de (pacsGatewayDummy.php) <BR>';mio
  $patId = isset($_GET['patId']) ? $_GET['patId'] : 0;
  if ($patId == 'DEMO') {
    //echo 'entre al 2º if (pacsGatewayDummy.php) <BR>';mio
    Header("Content-type: text/xml");
    $filename = './DEMO/DEMO_Patient.xml';
    $fx = fopen($filename, 'r');
    Header("Content-Length: ".filesize($filename));
    fpassthru($fx);
  }  
}

else {
  //echo 'entre al 1º else (pacsGatewayDummy.php) <BR>';mio
  $sop_iuid = get_sop_iuid();
  $fileName = './DEMO/'.$sop_iuid;
  if (isset($_GET['thumb'])) {
    //echo 'entre al 3º if de (pacsGatewayDummy.php) <BR>';mio
    $fileName .= '_th';
  }
  $fileName .= '.jpeg';
  header("Cache-Control: public");
  header('Expires: '.gmdate('D, d M Y H:i:s', strtotime('+1 day')).' GMT');
  header("Content-Type: image/jpeg");
  header("Content-Length: " . filesize($fileName));
  readfile($fileName);
}

// ******* ********* ********* ********* ********* ********* ********* *********

function get_sop_iuid() {
  //echo 'entre a get_sop_iuid (pacsGatewayDummy.php) <BR>';mio
  $sop_iuid = $_GET['sop_instance_uid'];
  if (substr($sop_iuid, 0, 4) != 'DEMO' || strlen($sop_iuid) > 8) {
    //echo 'entre al 1º if de get_sop_iuid (pacsGatewayDummy.php) <BR>';mio
    echo "ERROR!";
    die;
  }
  return $sop_iuid;
}

// ******* ********* ********* ********* ********* ********* ********* *********

?>
