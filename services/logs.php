<?php 
$rut = $_GET['rut'];
$action = $_GET['action'];
$plan = $_GET['plan'];
$date = $_GET['date'];
$doctor = $_GET['doctor'];
$room = $_GET['room'];

$filename  = 'log.txt';

$log = "Rut: $rut -------- Action: $action ------ Plan: $plan ----- Date: $date -------- Doctor: $doctor ------- Sala: $room ";
  file_put_contents($filename,$log);
?>