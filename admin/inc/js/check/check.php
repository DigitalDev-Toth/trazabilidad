<?
include("../../libs/db.class.php");
$q = $_POST['value'];
$t = $_POST['table'];
$f = $_POST['field'];

$dbclass = new DB($t);
if($dbclass->tableSchema[$f]['type'] == "numeric")
{
	$a = $q;
}
else
{
	$a = "'$q'";
}
if($_POST['rest']!="")
{
	$exps = explode(",", $_POST['rest']);
	foreach($exps as $exp)
	{
	
		$obj = explode("->", $exp);
		$where[$obj[0]] = $obj[1];
	}
	$all = array_merge(array($f=>$a), $where);
}
else
{
	$all = array($f=>$a);
}
$rows = $dbclass->select($all);
if($rows==NULL) { echo "1"; }
else { echo "0"; }
?>
