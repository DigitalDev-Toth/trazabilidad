<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
include("../libs/db.class.php");
$alternative = new DB("alternative", "id");
$alternative->exceptions(array("id"));
//$alternative->relation("question", "question", "id", "description");
//var_dump($_SESSION);

$question = $_GET['question'];

$alternative->changeFormObject("alternative.question", "hidden");
$alternative->changeFormObject("alternative.description", "basicEditor");
$alternative->changeFormObject("alternative.verify", "menu", NULL, array("Correcta"=>"true", "Incorrecta"=>"false"));

if (isset($_GET['update']))
{
	$alternative->updateData($_GET['update'], FALSE);
}
else
{
	echo '<div algin="center" id="showTitle">INSERTAR ALTERNATIVAS</div>';
	if($alternative->insertData(FALSE))
	{
		echo '<br><div id="bar_nav">';
		echo '<a href="../main.php?module=alternative&question='.$question.'"><div id="back"><img src="../../images/back.png" border="0" />Volver al menu de ALTERNATIVAS</div></a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png" border="0" />Agregar ALTERNATIVAS</a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="bar_nav"><a href="'.$_SERVER['HTTP_REFERER'].'"><div id="back"><img src="../../images/back.png" border="0" />Volver al menu de ALTERNATIVAS</div></a></div><br>';
}

?>
