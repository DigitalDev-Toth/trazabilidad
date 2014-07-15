<link href="../../style/style.css" rel="stylesheet" type="text/css" /><?
include("../libs/db.class.php");
$alternative = new DB("alternative", "id");
$data = $_REQUEST;

if($alternative->deleteData($data)) 
{ 
	echo '<br><br><p id="d_true"><a>Items borrados con exito!</a></p>'; 
}
else 
{ 
	echo 'error al borrar algunos de los items'; 
}
echo '<br><div id="bar_nav"><a href="'.$_SERVER['HTTP_REFERER'].'"><div id="back"><img src="../../images/back.png" border="0" />Volver al menu de ALTERNATIVAS</div></a></div><br>';
?>
