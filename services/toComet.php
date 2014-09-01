<?php
session_start();
if (!isset($_SESSION['Username'])) { 
//    header("location: ../admin/login.php"); 
    echo 'error_session';
} else {
    $data = $_GET;
    if($data) {
        foreach ($data as $key => $value) {
            $array[$key] = $value;
        }
        $msg = json_encode($array);
        $filename = "../visor/comet/data.txt";
        if ($msg)
        {
          file_put_contents($filename,$msg);
          die();
        }

        // infinite loop until the data file is not modified
        $lastmodif    = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
        $currentmodif = filemtime($filename);
        while ($currentmodif <= $lastmodif) // check if the data file has been modified
        {
          usleep(10000); // sleep 10ms to unload the CPU
          clearstatcache();
          $currentmodif = filemtime($filename);
        }
    }
}