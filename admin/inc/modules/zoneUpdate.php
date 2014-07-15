<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<?
if (isset($_GET['update']))
{
	echo '<div algin="center" id="showTitle">ACTUALIZAR ALTERNATIVAS</div>';
	$data = $_REQUEST;
	if (count($data) > 0)
	{
		echo '<br><br><div id="bar_nav"><a href="'.$_SERVER['HTTP_REFERER'].'"><div id="back"><img src="../../images/back.png" border="0" />Volver al menu ALTERNATIVAS</div></a></div>';
		foreach ($data['checkbox'] as $id)
		{
			echo '<iframe src="alternativeForm.php?update='.$id.'" width="100%" height="280" scrolling="auto" frameborder="0" transparency>
			      <p>Tu navegador no puede usar CMS!</p>
			      </iframe>';
		}
		echo '<br><br><div id="bar_nav"><a href="'.$_SERVER['HTTP_REFERER'].'"><div id="back"><img src="../../images/back.png" border="0" />Volver al menu ALTERNATIVAS</div></a></div>';
	}
}
?>
