<?php

// ******* ********* ********* ********* ********* ********* ********* *********

mostrarVisor($_GET['sop_instance_uid'], $_GET['urlGateway']);

// ******* ********* ********* ********* ********* ********* ********* *********
//echo 'entre a (ajVisor.php) <BR>';mio
//echo '1º: '.$_GET['sop_instance_uid'].'<br> 2º: '.$_GET['urlGateway'].'<br>';
//echo "<script>document.location.href='http://www.google.cl'</script>";
function mostrarVisor($sop_instance_uid, $urlGateway) {
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
</object>
  ";
}

// ******* ********* ********* ********* ********* ********* ********* *********

?>
