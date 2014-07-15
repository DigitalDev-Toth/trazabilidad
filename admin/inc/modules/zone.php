<link href="../style/style.css" rel="stylesheet" type="text/css" />
<?
//var_dump($_SESSION);
include("libs/db.class.php");
include("controls.php");
$question = $_GET['question'];
$alternative = new DB("alternative", "id", NULL, $_GET['temp']);
//$alternative->relation("question", "question", "id");
//$alternative->additions("question", array("number"=>"questionnumber"));
$alternative->exceptions(array("question"));

$alternative->changeItemInShowIf("verify", "==", "t", "replaceWithImage", "../images/true.png");
$alternative->changeItemInShowIf("verify", "==", "f", "replaceWithImage", "../images/false.png");

if(isset($_GET['question']))
{
	$db = new DB();
	$row = $db->doSql("SELECT type FROM question WHERE id=$question");
	if($row['type']=='Desarrollo') die('<div class="divAttention"><img align="absmiddle" src="../images/attention2.png"/>La pregunta esta marcada como "Desarrollo"!<br>Modifique la pregunta y vuelva a intentarlo...</div>');
	$where = array("question"=>"$question");
	$toForm = "?question=$question";
}

if(isset($_GET['update']))
{
	$question = $_GET['update'];
	$where = array("question"=>"$question");
	$toForm = "?question=$question";
}
else
{
	if(isset($_GET['temp'])) {
		$toForm = '?temp=1';
	}
}
makeControls($alternative, "modules/alternativeForm.php$toForm", "modules/alternativeDelete.php", "modules/alternativeUpdate.php$toForm", $_SERVER['HTTP_REFERER']);
$alternative->showControls();
echo '<div algin="center" id="showTitle">ALTERNATIVAS</div>';

$rows = $alternative->select($where);
echo $alternative->showData($rows, TRUE);
?>
