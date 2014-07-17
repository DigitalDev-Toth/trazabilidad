<?php

include_once('comun.php');

// ******* ********* ********* ********* ********* ********* ********* *********
//echo 'entre a (visorStratos.php) <BR>';mio
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="visorStratos.css" type="text/css">
<title>Visor de im&aacute;genes Radiol&oacute;gicas HSAP</title>
';

$onLoad = "javascript:maximizarIE();setSize();";
$onResize = "javascript:setSize();";
$extraBody = "onload=\"$onLoad\" onresize=\"$onResize\"";

echo '<script type="text/javascript" src="ajaxCallback.js"></script>'."\n";
echo '<script type="text/javascript" src="visorStratos.js"></script>'."\n";

echo "</head>\n";
echo "<body $extraBody>\n";

echo "<table border='0' cellspacing='0' cellpadding='0'>\n";
echo "<tr valign='top'>\n<td id='col_izq_'>\n";
echo "<div id='col_izq'>\n";

$patId = isset($_GET['patId']) ? $_GET['patId'] : 0;
$patIdIssuer = isset($_GET['patIdIssuer']) ? $_GET['patId'] : '';

//echo 'entre antes de htmlMiniaturasPaciente (visorStratos.php) <BR>';mio
htmlMiniaturasPaciente($patId, $patIdIssuer);
// htmlMiniaturasPaciente($_GET['numHC']);

echo "</div>";
echo "</td>";
echo "<td>".bloqueSolapa()."</td>\n";
echo "<td id='col_der_'>";
echo "<div id='col_der'></div>\n";
echo "</td></tr></table>\n";


echo "</body>\n";
echo "</html>\n";
//echo 'entre antes de la funcion bloqueSolapa (visorStratos.php) <BR>';mio
function bloqueSolapa() {
  //echo 'entre a bloqueSolapa (visorStratos.php) <BR>';mio
  $solapa = "<div id='col_ctr' class='col_solapa' onclick='javascript:mostrarOcultarMiniaturas();'><img src='solapa-azul.png' style='margin-top:140px;'></div>";
  return $solapa;
}

// ******* ********* ********* ********* ********* ********* ********* *********

?>
