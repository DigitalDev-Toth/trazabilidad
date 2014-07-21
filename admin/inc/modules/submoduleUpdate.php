<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>
<link href="../../style/styleAll.css" rel="stylesheet" type="text/css" />
<?
if (isset($_GET['update']))
{
	echo '<div algin="center" id="showTitle">ACTUALIZAR SUB-MODULO</div>';
	$data = $_REQUEST;
	if (count($data) > 0)
	{
		foreach ($data['checkbox'] as $id)
		{
			echo '<iframe src="submoduleForm.php?update='.$id.'" width="100%" height="320" scrolling="auto" frameborder="0" transparency>
			      <p>Tu navegador no puede usar CMS!</p>
			      </iframe>';
		}
		echo '<br><br><div id="back"><a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/back.png"/>Volver al menu Sub-Modulo</a></div>';
	}
}
?>
