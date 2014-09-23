<?php
$filename  = 'data.txt';

function getFileModMicrotime($filename)
{ 
    $stat = `stat --format=%y $filename`;
    $patt = '/^(\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d)\.(\d+) (.*)$/';
    if (preg_match($patt, $stat, $matches)) {
        $mtimeSeconds = strtotime("{$matches[1]} {$matches[3]}");
        $mtimeMillis = $matches[2];
        return "$mtimeSeconds$mtimeMillis";
    }

}

$msg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : '';
if ($msg != '')
{
    file_put_contents($filename,$msg);
    die();
}

if(php_uname('s')=='Linux') {
  $lastmodif    = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
  $currentmodif = getFileModMicrotime($filename);

  while ($currentmodif <= $lastmodif) // check if the data file has been modified
  {
    usleep(1000); // sleep 10ms to unload the CPU
    clearstatcache();
    $currentmodif = getFileModMicrotime($filename);
  } // infinite loop until the data file is not modified
} 

// return a json array
$response = array();
$response['msg']       = file_get_contents($filename);
$response['timestamp'] = $currentmodif;
echo json_encode($response);
flush();






/* filename: backend.php */
?>