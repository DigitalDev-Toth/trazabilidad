<?php

// ******* ********* ********* ********* ********* ********* ********* *********
include("../inc/libs/db.class.php");

$sop_uid = $_GET['sop_instance_uid'];
$study = new DB("study", "pk", "pacsdb");
$series = new DB("series", "pk", "pacsdb");
$query = $study->doSql("SELECT i.sop_iuid, se.series_iuid, st.study_iuid, se.study_fk, i.pk
FROM instance i, series se, study st
WHERE i.sop_iuid = '$sop_uid'
AND i.series_fk = se.pk
AND se.study_fk = st.pk");

$instance_pk = $query['pk'];
$study_pk = $query['study_fk'];
//echo "hoola".$sop_uid."<br>";
//var_dump($study_pk);

$query_serie = $study->doSql("SELECT pk FROM series WHERE study_fk = '$study_pk' ");
do{
//	echo "<br>";
	$serie_pk = $query_serie['pk'];
//	var_dump($serie_pk);
	$query_sop = $series->doSql("SELECT pk, sop_iuid FROM instance WHERE series_fk = '$serie_pk' ");
	$sop_pk[] = $query_sop['pk'];
	$sop_instance[] = $query_sop['sop_iuid'];
//	var_dump($instance);
}while($query_serie=pg_fetch_assoc($study->actualResults));
//var_dump($sop_instance[2]);
//echo "largo".count($sop_instance);
for($i=(count($sop_instance)-1);$i>=0;$i--)
{
	if($sop_uid!=$sop_instance[$i] && $sop_pk[$i]==$instance_pk+2)
	{
		$instance = $sop_instance[$i];
		//echo "<br>largo".$sop_instance[$i];
		$i=-1;
	}

}
mostrarVisor($_GET['sop_instance_uid'], $_GET['urlGateway'], $instance);

// ******* ********* ********* ********* ********* ********* ********* *********
//echo 'entre a (ajVisor.php) <BR>';mio
//echo '1º: '.$_GET['sop_instance_uid'].'<br> 2º: '.$_GET['urlGateway'].'<br>';
//echo "<script>document.location.href='http://www.google.cl'</script>";
function mostrarVisor($sop_instance_uid, $urlGateway, $instance_uid) {
  //echo 'entre a mostrarVisor (ajVisor.php) <BR>';mio
  $script = 'visorStratos_002.swf';
  $nombre = 'visorStratos';
  $ancho = '100%';
  $alto = '100%';
  $encUrlGateway = urlencode($urlGateway);
  //echo $encUrlGateway;mio
  echo "
<object class='visor'
  classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000'
  codebase='http://10.36.11.16/pacs/flash/flashplayer/swflash.cab#version=8,0,0,0'
  id='$nombre'
  align='middle'>
  	<table  width='100%' height='100%'>
  		<td height='500px'>
		  <param name='allowScriptAccess' value='always' />
		  <param name='movie' value='$script' />
		  <param name='quality' value='high' />
		  <param name='scale' value='noscale' /> 
		  <param name='bgcolor' value='#000000' />
		  <param name='FlashVars' value='sop_instance_uid=$sop_instance_uid&hostGateway=$encUrlGateway'>
		    <embed class='visor'
		      src='$script'
		      quality='high'
			    scale='noscale'
		      bgcolor='#000000'
		      name='$nombre'
		      align='middle'
		      allowScriptAccess='always'
		      type='application/x-shockwave-flash'
		      pluginspage='http://www.macromedia.com/go/getflashplayer'
		      FlashVars='sop_instance_uid=$sop_instance_uid&hostGateway=$encUrlGateway'
		    />
		</td>
		<td height='500px'>
		  <param name='allowScriptAccess' value='always' />
		  <param name='movie' value='$script' />
		  <param name='quality' value='high' />
		  <param name='scale' value='noscale' /> 
		  <param name='bgcolor' value='#000000' />
		  <param name='FlashVars' value='sop_instance_uid=$instance_uid&hostGateway=$encUrlGateway'>
		    <embed class='visor'
		      src='$script'
		      quality='high'
			    scale='noscale'
		      bgcolor='#000000'
		      name='$nombre'
		      align='middle'
		      allowScriptAccess='always'
		      type='application/x-shockwave-flash'
		      pluginspage='http://www.macromedia.com/go/getflashplayer'
		      FlashVars='sop_instance_uid=$instance_uid&hostGateway=$encUrlGateway'
		    />
		</td>
	</table>
</object>
  ";
}

// ******* ********* ********* ********* ********* ********* ********* *********

?>
