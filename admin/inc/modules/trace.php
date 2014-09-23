<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }

include("libs/db.class.php");
include("controls.php");

$trace = new DB("tickets", "id");

$trace->relation("logs", "logs", "id");
$trace->additions("logs", array("datetime"=>"Fecha","rut"=>"Rut"));
$trace->exceptions(array("logs", "attention"));


//$trace->exceptions(array("zone", "type"));
/*
$trace->relation("zone", "zone", "id", "name");
$trace->additions("zone", array("name"=>"Zona"));
$trace->exceptions(array("action", "sub_module",'zone','module'));

$trace->relation("module", "module", "id", "module");
$trace->additions("module", array("name"=>"Modulo"));

$trace->relation("tickets","id" ,"logs");

$trace->additions("module_type", array("name"=>"modulename"));
*/
$ZoneCombo['Todos'] = "";
$db = new DB("zone","id");
$sql = $db->doSql("SELECT id,name from zone order by id");
do{
	$nameZ = $sql['name'];
	$idZ = $sql['id'];
	if($idZ!='')
	{
		$ZoneCombo[$nameZ] = $idZ;
	}
}while($sql = pg_fetch_assoc($db->actualResults));


$db = new DB("module","id");
$sql = $db->doSql("SELECT id,name from module order by id");
if(isset($_REQUEST['zone'])){
	$idZone = $_REQUEST['zone'];
	if($idZone != ''){
		$sql = $db->doSql("SELECT id,name from module where zone=$idZone order by id");
	}
	
}

$ModuleCombo['Todos'] = "";
do{
	$nameM = $sql['name'];
	$idM = $sql['id'];
	if($idM!='')
	{
		$ModuleCombo[$nameM] = $idM;
	}
}while($sql = pg_fetch_assoc($db->actualResults));



$state = array("Todos" => '',"No Atendidos" => 'no_served', "Atendidos" => 'served',"En Espera" => "waiting" ,"Derivados" => "derived" ,"Con plan de tratamiento" => "limb_pt" );






if(isset($_REQUEST['idate'])) {
	$idate = $_REQUEST['idate'];
	$_SESSION['idate'] = $idate;
}
elseif(isset($_SESSION['idate'])) {
	$idate = $_SESSION['idate'];
}
else {
	$idate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	$_SESSION['idate'] = $idate;
}
if(isset($_REQUEST['fdate'])) {
	$fdate = $_REQUEST['fdate'];
	$_SESSION['fdate'] = $fdate;
}
elseif(isset($_SESSION['fdate'])) {
	$fdate = $_SESSION['fdate'];
}
else {
	$fdate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	$_SESSION['fdate'] = $fdate;
}

$search="datetime BETWEEN '$idate' AND '$fdate'";

if(isset($_REQUEST['zone'])){
	$idZone = $_REQUEST['zone'];
	if($idZone != ''){
		$search .="and zone=$idZone";
	}
	
}

if(isset($_REQUEST['attention'])){
	$atention = $_REQUEST['attention'];
	if($atention != ''){
		$search .="and attention='$atention'";
	}
	
}
if(isset($_REQUEST['module'])){
	$module = $_REQUEST['module'];
	if($module != ''){
		$search .="and module='$module'";
	}
	
}

$where = array(''=>$search);

makeControls($trace, NULL, NULL, NULL, $_SERVER['HTTP_REFERER']);

$trace->showControls();
echo '<div algin="center" id="showTitle">Tickets</div>';
echo '<script type="text/javascript" src="js/calendar/date.js"></script>';
echo '<script type="text/javascript" src="js/calendar/jquery.datePicker.js"></script>';
echo '<link href="js/calendar/datePicker.css" rel="stylesheet" type="text/css" title="default" media="screen" />';
echo '<script src="js/calendar/popcalendar.js" type="text/javascript"></script>'; //Para que funcione el popcalendar- juan
echo '<link href="../style/styleAll.css" rel="stylesheet" type="text/css" title="default" media="screen" />';
$trace->insertJavaForm(); 
echo '<table  id="tableForm" align="center"><tr><td>';
	echo '<div>';
		echo '<form name="between" method="POST">';
			echo '<table>';
				echo '<tr>';
					echo '<td>Zona</td><td>'.$trace->fillCombo($ZoneCombo,"zone","zone",'id="zone" onclick="submit();"').'</td>';
					echo '<td>Modulo</td><td>'.$trace->fillCombo($ModuleCombo,"module","module",'id="module" onclick="submit();"').'</td>';
					echo '<td>Estado:</td><td>'.$trace->fillCombo($state,"attention","attention",'id="attention" onclick="submit();"').'</td>';
					echo '<td>Fecha Inicial</td>';
					echo $trace->makeObjectForm("idate", array('type'=>'date', 'isNull'=>'YES'), $idate);
					echo '<td>Fecha Final</td>';
					echo $trace->makeObjectForm("fdate", array('type'=>'date', 'isNull'=>'YES'), $fdate);
					echo '<td><input id="button" name="beetween" type="submit" value="Buscar" onclick="submit();"></td>';
				echo '</tr>';
			echo '</table>';
		echo '</form>';
	echo '</div>';
echo '</td></tr></table>';




$rows = $trace->select($where);
echo $trace->showData($rows, TRUE);



?>
<script>
$(document).ready(function() {
	var zone = "<?php echo $_REQUEST['zone'] ?>";
	if(zone != ''){
		$("#zone").val(zone);

	}else{
		$("#module").val(0);
		$("#zone").val(0);
	}
	var module = "<?php echo $_REQUEST['module'] ?>";
	if(module != ''){
		$("#module").val(module);
	}
	var attention = "<?php echo $_REQUEST['attention'] ?>";
	if(attention != ''){
		$("#attention").val(attention);
	}
});

</script>