<?
session_start();

include("libs/menu.class.php");
include("libs/db.class.php");

$menu = new MENU("jdMenu");

$menu->add("Administrar","#","images/admin.png");
$menu->add("Zonas","inc/main.php?modulo=zone","", "Administrar");
$menu->add("Modulos","inc/main.php?modulo=users","", "Administrar");
$menu->add("Submodulos", "inc/main.php?modulo=exams_tree", "", "Administrar");

$menu->add("Personas","#","images/admin.png");
$menu->add("Cambiar Password","inc/main.php?modulo=zone","", "Personas");
$menu->add("Roles","inc/main.php?modulo=users","", "Personas");
$menu->add("Usuarios", "inc/main.php?modulo=exams_tree", "", "Personas");

$db = new DB();
$sql = "SELECT id, name FROM zone";
$rows = $db->doSql($sql);
$menu->add("Visor","#","images/admin.png");
do {
	$menu->add($rows['name'], "inc/main.php?modulo=zone&zone=".$rows['id'], "", "Visor");
} while ($rows = pg_fetch_assoc($db->actualResults));
	
$menu->show();
?>
