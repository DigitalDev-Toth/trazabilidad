<?php

// ******* ********* ********* ********* ********* ********* ********* *********

include_once('comun.php');

// ******* ********* ********* ********* ********* ********* ********* *********
if (isset($_GET['xml'])) {
  //echo 'entre al 1º if de (pacsGateway.php) <br>';
  header("Content-type: text/xml");
  $sop_iuid = $_GET['sop_instance_uid'];
  $sop_path = pathSOP($sop_iuid);
  if ($sop_path) {
    //echo 'entre al 2º if de (pacsGateway.php) <br>';
    $sop_xml = generarXML_v2($sop_path);
    header('Content-type: text/xml');
    echo $sop_xml;
    //echo "<script>document.location='http://www.google.cl'</script>";
  }  
}
else if (isset($_GET['patId'])) {
  //echo 'entre al 1º else if de (pacsGateway.php) <br>';
  $patId = isset($_GET['patId']) ? $_GET['patId'] : 0;
  $patIdIssuer = isset($_GET['patIdIssuer']) ? $_GET['patIdIssuer'] : '';
  obtenerXMLPaciente($patId, $patIdIssuer);
}
else {
  //echo 'entre al 1º else de (pacsGateway.php) <br>';
  $thumb = isset($_GET['thumb']);
  //header("Content-type: image/jpeg");
  $sop_iuid = $_GET['sop_instance_uid'];
  obtenerImagen($sop_iuid, $thumb);
}


// ******* ********* ********* ********* ********* ********* ********* *********
// ******* ********* ********* ********* ********* ********* ********* *********

function obtenerXMLPaciente($patId, $patIdIssuer) {
  //echo 'entre a obtenerXMLPaciente (pacsGateway.php) <br>';mio
  if ($patId == 'DEMO') {
    //echo 'entre al 1º if de obtenerXMLPaciente (pacsGateway.php) <br>';mio
    Header("Content-type: text/xml");
    $filename = './DEMO/DEMO_Patient.xml';
    $fx = fopen($filename, 'r');
    Header("Content-Length: ".filesize($filename));
    fpassthru($fx);
  }
  else {
    //echo 'entre al 1º else de obtenerXMLPaciente (pacsGateway.php) <br>';mio
    $linkDCM4 = connectDCM4();
    //echo 'entre al 1º else y antes de datosPaciente de obtenerXMLPaciente (pacsGateway.php) <br>';mio
    $infoPaciente = datosPaciente($patId, $patIdIssuer, $linkDCM4);
    // print_r($infoPaciente);
    setlocale (LC_TIME, "es_ES"); // Necesario ???
    //echo 'entre al 1º else y antes de estudiosPaciente2 de obtenerXMLPaciente (pacsGateway.php) <br>';mio
    $estudios = estudiosPaciente2($patId, $linkDCM4);
    $info = array('datos' => $infoPaciente, 'estudios' => $estudios);
    //mysql_close($linkDCM4);
    pg_close($linkDCM4);
    //echo 'entre al 1º else y antes de array2xml de obtenerXMLPaciente (pacsGateway.php) <br>';mio
    array2xml($info);
  }
}

// ******* ********* ********* ********* ********* ********* ********* *********
function datosPaciente($patId, $patIdIssuer, $linkDCM4)//puedo cambiar pat_id por pk para darle el id del paciente
{
  //echo 'entre a datosPaciente (pacsGateway.php) <br>';mio error
  $whereCond = "pk = '$patId'";
  if ($patIdIssuer != '') {
    //echo 'entre al 1º if de datosPaciente (pacsGateway.php) <br>';mio
    $whereCond .= " AND pat_id_issuer = '$patIdIssuer'";
  }
  $query = "SELECT * FROM patient WHERE pk=(select patient_fk from study where pk=$patId)";
  //$res = mysql_query($query, $linkDCM4);
  $res = pg_query($linkDCM4, $query);
  // echo mysql_error();

  //$fila = mysql_fetch_assoc($res);
  $fila = pg_fetch_assoc($res);
//  $fila['fechanac'] = cnvFecha($fila['fechanac']);
  return $fila;
}

// ******* ********* ********* ********* ********* ********* ********* *********

// fechas: ISO 8601
function array2xml($info) {
  //echo 'entre a array2xml (pacsGateway.php) <br>';mio
  Header("Content-type: text/xml");
  echo "<?xml version='1.0' encoding='".XML_ENCODING."'?>\n";
  $fechaAhora = strftime("%Y%m%d%H%M%S");
//  echo "<paciente numHC='".$numHC."' fechaListado='$fechaAhora'>\n";
  echo "<patient date=\"$fechaAhora\">\n";
  
  $data = $info['datos'];

  echo "<data patId=\"{$data['pat_id']}\" patIdIssuer=\"{$data['pat_id_issuer']}\" birthdate=\"{$data['pat_birthdate']}\" sex=\"{$data['pat_sex']}\" fullname=\"{$data['pat_name']}\">\n";
  echo "</data>\n";

  echo "<dicom>\n";
  foreach($info['estudios'] as $estudio) {
    //echo 'entre al 1º foreach de array2xml (pacsGateway.php) <br>';mio
    $study_iuid = $estudio['study_instance_uid'];
    // $study_datetime = strftime("%d-%b-%Y", strtotime($estudio['study_datetime']));
    $study_datetime = $estudio['study_datetime'];
    echo "<study studyUID=\"$study_iuid\" date=\"$study_datetime\" modalities=\"{$estudio['modalidades']}\" description=\"{$estudio['descripcion']}\" numseries=\"{$estudio['num_series']}\">\n";
    foreach($estudio['series'] as $serie) {
      //echo 'entre al 2º foreach de array2xml (pacsGateway.php) <br>';mio
      $series_iuid = $serie['serie_instance_uid'];
      // $series_datetime = strftime("%d-%b-%Y", strtotime($episodio['series_datetime']));
      echo "<series seriesUID=\"$series_iuid\" series_num=\"{$serie['serie_num']}\" modality=\"{$serie['modalidad']}\" numinstances=\"{$serie['num_instancias']}\">\n";
      foreach($serie['instancias'] as $instancia) {
        //echo 'entre al 3º foreach de array2xml (pacsGateway.php) <br>';mio
        echo "<instance objectUID=\"{$instancia['sop_instance_uid']}\" instance_num=\"{$instancia['inst_num']}\" />\n";
      }
      echo "</series>\n";
    }
    echo "</study>\n";
  }
  echo "</dicom>\n";
  echo "</patient>\n";
}

// ******* ********* ********* ********* ********* ********* ********* *********
// ******* ********* ********* ********* ********* ********* ********* *********
// Modificacion 20071022 para que el visor se conecte directamente a un PACS DCM4CHEE

function obtenerImagen($sop_iuid, $thumb) {
  //echo 'entre a obtenerImagen (pacsGateway.php) <br>';
  $reduced = isset($_GET['reduced']);
  if ($infoSOP = infoSOP($sop_iuid)) {
    //echo 'entre al 1º if de obtenerImagen (pacsGateway.php) <br>';mio
    // print_r($infoSOP);
    getImgWADO($infoSOP, $thumb, $reduced);
  }
}

// ******* ********* ********* ********* ********* ********* ********* *********

/*
 * Obtiene los datos de episodio y serie para una instancia dada
 */
function infoSOP($sop_iuid) {
  //echo 'entre a infoSOP (pacsGateway.php) <br>';mio
  $linkDCM4 = connectDCM4();  
  $query = "SELECT i.sop_iuid, se.series_iuid, st.study_iuid
    FROM instance i, series se, study st
    WHERE i.sop_iuid = '$sop_iuid'
      AND i.series_fk = se.pk
      AND se.study_fk = st.pk
  ";
  // echo $query."<br>";
  //$resPre = mysql_query($query, $linkDCM4);
  $resPre = pg_query($linkDCM4, $query);
  //$fila = mysql_fetch_assoc($resPre);
  $fila = pg_fetch_assoc($resPre);
  //mysql_close($linkDCM4);
  pg_close($linkDCM4);
  return $fila;
}

// ******* ********* ********* ********* ********* ********* ********* *********

/*
 * reduced = true => Se solicita un tamanio maximo de imagen no superior
 * al maximo permitido por flash (MAX_SIZE_FLASH x MAX_SIZE_FLASH)
 */
function getImgWADO($infoSOP, $thumb = false, $reduced = false) {
  //echo 'entre a getImgWADO (pacsGateway.php) <br>';
  $urlWADO = WADO_SERVER."/wado?requestType=WADO";
  $urlWADO .= "&studyUID={$infoSOP['study_iuid']}&seriesUID={$infoSOP['series_iuid']}&objectUID={$infoSOP['sop_iuid']}";
  if ($reduced) {
    //echo 'entre al 1º if de getImgWADO (pacsGateway.php) <br>';
    $urlWADO .= "&rows=".MAX_SIZE_FLASH."&columns=".MAX_SIZE_FLASH;
  }
  if ($thumb) {
    //echo 'entre al 2º if de getImgWADO (pacsGateway.php) <br>';
    $urlWADO .= "&rows=".TAM_MAX_THUMB."&columns=".TAM_MAX_THUMB;
  }
  //echo 'wadoo:'.$urlWADO;//mio

/*  
echo "+++<br>";
echo $urlWADO."<br>";
echo "---<br>";
die;
*/ 

  // El comando filesize no funciona con el wrapper (sobre fichero remoto). Se copia a local.
  $nomTmp = tempnam(PATH_TMP, 'imgWADO_'); 
  //$urlWADO = "http://192.168.1.200/biopacs/pacs/pngs/2009/2/9/21/ABE9A430/111D40FD/175CDAC9_s.png";
  copy($urlWADO, $nomTmp);

  header("Cache-Control: public");
  header('Expires: '.gmdate('D, d M Y H:i:s', strtotime('+1 day')).' GMT');
  header("Content-Type: image/jpeg");
  header("Content-Length: " . filesize($nomTmp));
  readfile($nomTmp);
  unlink($nomTmp);
}

// ******* ********* ********* ********* ********* ********* ********* *********
// XML of the instances (Using DCMTK)
// ******* ********* ********* ********* ********* ********* ********* *********

/*
 * Obtiene la ubicacion en el sistema de almacenamiento de una instancia (imagen)
 */
function pathSOP($sop_iuid) {
  //echo 'entre a pathSOP (pacsGateway.php) <br>';mio
  $linkDCM4 = connectDCM4();  
  $query = "
    SELECT fs.dirpath, f.filepath
    FROM filesystem fs, files f, instance i
    WHERE i.sop_iuid = '$sop_iuid'
      AND f.instance_fk = i.pk
      AND f.filesystem_fk = fs.pk
  ";
  // echo $query."<br>";
  //$resPre = mysql_query($query, $linkDCM4);
  $resPre = pg_query($linkDCM4, $query);
  //if ($fila = mysql_fetch_assoc($resPre)) {
  if ($fila = pg_fetch_assoc($resPre)) {
    //echo 'entre al 1º if de pathSOP (pacsGateway.php) <br>';mio
    //print_r($fila);
    $sop_path = PATH_INSTALL_DCM4CHEE.'/'.PATH_SERVER_HSAP.'/'.$fila['dirpath'].'/'.$fila['filepath'];
  }
  else {
    //echo 'entre al 1º else de pathSOP (pacsGateway.php) <br>';mio
    $sop_path = false;
  }
  //mysql_close($linkDCM4);
  pg_close($linkDCM4);
  return $sop_path;
}

// ******* ********* ********* ********* ********* ********* ********* *********

function generarXML_v2($sop_path)
{
  //echo 'entre a generarXML_v2 (pacsGateway.php) <br>';mio
  $comando = PATH_INSTALL_DCMTK."/dcmdump $sop_path";
  $salida = array();
  exec($comando, $salida);
  $stringXML = dcm2xml_v2($salida);
  // echo htmlspecialchars($stringXML);
  //echo 'comando:'.$sop_path.'<br>';
  //var_dump($stringXML);
  return $stringXML;
}

// ******* ********* ********* ********* ********* ********* ********* *********

function dcm2xml_v2($volcadoDcm)//entra en esta funcion al ser click
{
  //echo 'entre a dcm2xml_v2 (pacsGateway.php) <br>';
  $grupoAnt = "ZZZZ";
  $cerrarGrupo = false;
  $stringXML = "<?xml version='1.0' encoding='".XML_ENCODING."'?>\n<cabecera>\n";
  foreach($volcadoDcm as $filaDcm) {
    //echo 'entre al 1º foreach de dcm2xml_v2 (pacsGateway.php) <br>';mio
    // Eliminacion de elementos anidados (p.ej. tablas LUT) que dan lugar a duplicidad de grupos y/o elementos
    if (substr($filaDcm, 0, 1) == ' ') {
      //echo 'entre al 1º if de dcm2xml_v2 (pacsGateway.php) <br>';mio
      continue;
    }

    // ejemplo de campoDcm:
    //  (0008,0020) DA [20040910]                               #   8, 1 StudyDate

    $token = explode("#", $filaDcm);
    if (strlen($campo = trim($token[0])) == 0) {
      //echo 'entre al 2º if de dcm2xml_v2 (pacsGateway.php) <br>';mio
      continue;
    }
    $numGrupo = substr($campo, 1, 4);
    $numElemento = substr($campo, 6, 4);
    $tipoElemento = substr($campo, 12, 2);
    $valorElemento = substr($campo, 15);

    // Eliminacion de elementos 'SequenceDelimitationItem', que pueden aparecer en multiples ocasiones (ej. LUT)
    if ($numGrupo == 'fffe' && $numElemento == 'e0dd') {
      //echo 'entre al 3º if de dcm2xml_v2 (pacsGateway.php) <br>';mio
      continue;
    }

    if ($numGrupo != $grupoAnt) {
      //echo 'entre al 4º if de dcm2xml_v2 (pacsGateway.php) <br>';mio
      if ($cerrarGrupo) {
        //echo 'entre al 5º if de dcm2xml_v2 (pacsGateway.php) <br>';mio
        $stringXML .= "\t</grupo>\n";
      }
      $stringXML .= "\t<grupo id='$numGrupo'>\n";
    }

    // Cada elemento del grupo
    $stringXML .= "\t\t<elemento id='$numElemento' value='$valorElemento' />\n";
    
    if ($numGrupo != $grupoAnt) {
      //echo 'entre al 6º if de dcm2xml_v2 (pacsGateway.php) <br>';mio
      $grupoAnt = $numGrupo;
      $cerrarGrupo = true;
    }
  }
  if ($cerrarGrupo) {
    //echo 'entre al 7º if de dcm2xml_v2 (pacsGateway.php) <br>';mio
    $stringXML .= "\t</grupo>\n";
  }
  $stringXML .= "</cabecera>\n";
  //echo $stringXML;mio
  return $stringXML;
}

// ******* ********* ********* ********* ********* ********* ********* *********

?>
