<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>

<?
include("libs/db.class.php");
include("controls.php");

$employee = new DB("employee", "id");

makeControls($employee, "modules/employeeForm.php", "modules/employeeDelete.php", "modules/employeeUpdate.php", $_SERVER['HTTP_REFERER']);
/*
$employee->relation("branch", "branch", "id");
$employee->additions("branch", array("name"=>"branch_name"));
$employee->relation("commune", "commune", "id");
$employee->additions("commune", array("name"=>"commune_name"));
$employee->relation("prevision", "prevision", "id");
$employee->additions("prevision", array("name"=>"prevision_name"));
$employee->relation("isapre", "isapre", "id");
$employee->additions("isapre", array("name"=>"isapre_name"));
$employee->relation("afp", "afp", "id");
$employee->additions("afp", array("name"=>"afp_name"));
$employee->exceptions(array("commune","branch","afp","isapre","prevision"));
*/
$employee->showControls();
echo '<div algin="center" id="showTitle">EMPLEADOS</div>';

$rows = $employee->select();
echo $employee->showData($rows, TRUE);
?>
