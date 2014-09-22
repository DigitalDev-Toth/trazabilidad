<?php
$filename  = 'data.txt';

// store new message in the file
$msg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : '';
if ($msg != '')
{
  	file_put_contents($filename,$msg);
  	die();
}

if(php_uname('s')=='Linux') {
	$lastmodif    = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
	$currentmodif = exec("ls --full-time 'data.txt'");
	$data = explode(' ',$currentmodif);
	$dataMin = explode(".", $data[6]);
	$min = $dataMin[0];
	$mili = $dataMin[1];
	$date = $data[5].' '.$min;
	$currentmodif = strtotime($date).$mili;

	while ($currentmodif <= $lastmodif) // check if the data file has been modified
	{
	  usleep(10000); // sleep 10ms to unload the CPU
	  clearstatcache();
	  $currentmodif = exec("ls --full-time 'data.txt'");
		$data = explode(' ',$currentmodif);
		$dataMin = explode(".", $data[6]);
		$min = $dataMin[0];
		$mili = $dataMin[1];
		$date = $data[5].' '.$min;
		$currentmodif = strtotime($date).$mili;
	} // infinite loop until the data file is not modified
} elseif(php_uname('s')=='Darwin') {
	$lastmodif    = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
	$currentmodif = filemtime($filename);
	while ($currentmodif <= $lastmodif) // check if the data file has been modified
	{
	  usleep(10000); // sleep 10ms to unload the CPU
	  clearstatcache();
	  $currentmodif = filemtime($filename);
	}
}

// return a json array
$response = array();
$response['msg']       = file_get_contents($filename);
$response['timestamp'] = $currentmodif;
echo json_encode($response);
flush();


/* filename: backend.php */
?>