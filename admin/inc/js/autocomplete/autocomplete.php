<?
include("../../libs/db.class.php");
$q = strtoupper($_GET['q']);
$t = $_GET['table'];
$exps = explode(",", $_GET['values']);
foreach($exps as $exp)
{
	
	$obj = explode("->", $exp);
	$val[] = $obj[1];
}
$dbclass = new DB($t);
$rows = $dbclass->select(array($val[0]=>"'%$q%'"));
do
{
	foreach($val as $v)
	{
		echo htmlentities($rows[$v])."|";
	}
	echo "\n";
}while($rows = pg_fetch_assoc($dbclass->actualResults));
//echo $dbclass->actualSql;
?>
