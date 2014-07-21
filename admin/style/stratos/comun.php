<?php

// ******* ********* ********* ********* ********* ********* ********* *********
// Configuration: Change values to fit your environment
// ******* ********* ********* ********* ********* ********* ********* *********

include_once('config.php');

define('TAM_MAX_THUMB', 120);

// ******* ********* ********* ********* ********* ********* ********* ********* CONEXION A LA BASE DE DATOS
function connectDCM4($persistente = true)
{
  if ($persistente) {
    $link = pg_pconnect("host='localhost' port='5432' dbname='pacsdb' user='postgres' password='superkullin75'");
  }
  else {
    $link = pg_pconnect("host='localhost' port='5432' dbname='pacsdb' user='postgres' password='superkullin75'");
  }
  if (!$link)
  {
    echo 'Error al conectar con la Base de Datos del sistema PACS.';
  }
  return $link;
}
// ******* ********* ********* ********* ********* ********* ********* ********* ICONO EN CUANTO AL SEXO DEL PACIENTE
function iconoSexo($sex) {
  switch ($sex) {
    case 'M': // Male
      $cad = "<img width='32px;' align='absbottom' src='./im/icon_male.png' alt='MALE'>";
      break;
    case 'F': // Female
      $cad = "<img width='32px;' align='absbottom' src='./im/icon_female.png' alt='FEMALE'>";
      break;
    default:
      $cad = "";
  }
  return $cad;
}
// ******* ********* ********* ********* ********* ********* ********* ********* FALTA ANALISAR
function fnLlamadaVisor($sop_instance_uid, $urlGateway) {
  //echo 'entre a fnLlamadaVisor (comun.php) <br>';
  $url = 'ajVisor.php?sop_instance_uid='.$sop_instance_uid.'&urlGateway='.$urlGateway;
  //echo 'urlLLamada: '.$url.'<br>';
  $llamada = "javascript:makeHttpRequest('$url', 'setValorSalida', 'col_der', false);";
  //echo 'llamada: '.$llamada.'<br>';mio
  return $llamada;
}
// ******* ********* ********* ********* ********* ********* ********* ********* FALTA ANALISAR
function urlWADO($study_iuid, $series_iuid, $sop_iuid, $thumb = false) {
  //echo 'entre a urlWADO (comun.php) <br>';mio
  $urlWADO = WADO_SERVER."/wado?requestType=WADO";
  $urlWADO .= "&studyUID=$study_iuid&seriesUID=$series_iuid&objectUID=$sop_iuid";
  if ($thumb) {
    //echo 'entre al if de urlWADO (comun.php) <br>';mio
    $urlWADO .= "&rows=".TAM_MAX_THUMB."&columns=".TAM_MAX_THUMB;
  }
  //echo '<br>urlwado:'.$urlWADO;mio
  return $urlWADO;
}
// ******* ********* ********* ********* ********* ********* ********* ********* ME RETORNA UNA URL DE LA FORMA 
// http://???/???/pacsGateway.php?studyUID=???&seriesUID=???&sop_instance_uid=???&thumb&jpeg
function urlJPEG($study_iuid, $series_iuid, $sop_iuid, $thumb = false) {
  $gateway = getUrlBase().GATEWAY_SCRIPT;
  
  $params = "?studyUID=$study_iuid&seriesUID=$series_iuid&sop_instance_uid=$sop_iuid";
  
  /*$gateway = "http://192.168.1.200/biopacs/pacs/pngs/";
  $query = "select pk from instance where sop_iuid='$sop_iuid'";
  $linkDCM4 = connectDCM4();
  $resPre = pg_query($linkDCM4, $query);
  $fila = pg_fetch_assoc($resPre);
  $id = $fila['pk'];
  $query = "select filepath from files where instance_fk=$id";
  $resPre = pg_query($linkDCM4, $query);
  $fila = pg_fetch_assoc($resPre);
  $filepath = $fila['filepath'];
  $params = $filepath;*/
  
  if ($thumb) {
    //echo 'entre al if de urlJPEG (comun.php) <br>';mio
    //$params .= "&rows=".TAM_MAX_THUMB."&columns=".TAM_MAX_THUMB;
    $params .= "&thumb";
  }
  $params .= "&jpeg"; 
  
  //$params .= "_s.png";
  
  $url = $gateway.$params;
  return $url;
}
// ******* ********* ********* ********* ********* ********* ********* *********
// ******* ********* ********* ********* ********* ********* ********* *********
function htmlMiniaturasPaciente($patId, $patIdIssuer) {
  //echo 'entre a htmlMiniaturasPaciente (comun.php) <br>';mio
  $xmlPaciente = getXmlPaciente($patId, $patIdIssuer);
  $infoPaciente = obtenerInfoPaciente($xmlPaciente);//$xmlPaciente = nada
  //echo 'info '.$infoPaciente.'<br>';mio
  //mostrarInfoPaciente($infoPaciente);
  mostrarEstudios_v2($infoPaciente);
}

// ******* ********* ********* ********* ********* ********* ********* *********

function getUrlBase() {
  //echo 'entre a getUrlBase (comun.php) <br>';mio
  $protocol = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://');
  $urlBase = $protocol.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
  //echo 'urlBase '.$urlBase.'<br>';//mio
  $urlBase .= substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/') + 1);
  //echo 'urlBase '.$urlBase.'<br>';//mio
  return $urlBase;
}

// ******* ********* ********* ********* ********* ********* ********* *********

function getXmlPaciente($patId, $patIdIssuer) {
  //echo 'entre a getXmlPaciente (comun.php) <br>';mio
  $params = "?patId=$patId".($patIdIssuer == '' ? '' : "&patIdIssuer=$patIdIssuer");
  $gateway = getUrlBase().GATEWAY_SCRIPT;
  $filename = $gateway.$params;
  
  $xmlPaciente = simplexml_load_file($filename);
  //echo 'parametro'.$xmlPaciente.' termino<br>';//mio
  return $xmlPaciente; // no retorna nada
}

// ******* ********* ********* ********* ********* ********* ********* *********
function obtenerInfoPaciente($xmlPaciente) {
  //echo 'entre a obtenerInfoPaciente (comun.php) <br>';mio
  $patientInfo = nodo2array($xmlPaciente);
  // print_r($patientInfo);
  return $patientInfo;
}

// ******* ********* ********* ********* ********* ********* ********* *********
function mostrarInfoPaciente($datosPaciente) {
  //echo 'entre a mostrarInfoPaciente (comun.php) <br>';mio
  $infoPaciente = $datosPaciente['hijos'][0];
  echo "<div style='background-color: #151E1D;'>";
  echo "<div id='infopaciente' style='padding-top: 3px;'>\n";
  $nombre = $infoPaciente['atributos']['fullname'];
//  $nombre = iconv ("ISO-8859-1", "UTF-8", $infoPaciente['pat_name']);
  echo "$nombre<br>\n";
  echo "<p style='text-align:center;'><span class='numhc'>{$infoPaciente['atributos']['patId']}</span>".iconoSexo($infoPaciente['atributos']['sex'])."<br>\n";
//  echo "numHC: {$infoPaciente['pat_id']}<br>\n";
  echo "F.nac: {$infoPaciente['atributos']['birthdate']}</p>\n";
  echo "</div>\n";
  echo "</div>\n";
}

// ******* ********* ********* ********* ********* ********* ********* *********

function setJsVar($varName, $value) {
  //echo 'entre a setJsVar (comun.php) <br>';mio
  echo "<script language='javascript' type='text/javascript'>\n";
//  echo "alert($value)";
  $pre = array("<", ">");
  $post = array("&lt;", "&gt;");
  // $value = str_replace($pre, $post, $value);
  echo "var $varName = \"".addcslashes($value, '"')."\";\n";
  echo "</script>\n";
}

// ******* ********* ********* ********* ********* ********* ********* *********

function strSeriesEstudio($study_iuid, $series) {//falta
  //echo 'entre a strSeriesEstudio (comun.php) <br>';mio
  $numSeries = count($series);
  // Las series muestran su primera imagen. En caso de haber mas de una, generan AJAX para mostrar resto y/o ocultar.
  $numSerie = 0;
  $strSeries = '';
  foreach($series as $serie) {
    //echo 'entre a foreach de strSeriesEstudio (comun.php) <br>';mio
  // echo "SERIE: *** ";
  // print_r($serie);
    $series_iuid = $serie['atributos']['seriesUID'];
    $num_imagenes = $serie['atributos']['numinstances'];
    $numSerie++; // No se puede utilizar {$serie['serie_num']} porque no siempre comienzan en el #1

    $cad_imagenes = $num_imagenes == 1 ? 'imagen' : 'im&aacute;genes';
    $cad_serie = "Serie $numSerie [{$serie['atributos']['modality']}]: $num_imagenes $cad_imagenes";
    // Identificador de la primera instancia de la serie:
    $first_sop_iuid = $serie['hijos'][0]['atributos']['objectUID'];    
    $urlThumb = urlJPEG($study_iuid, $series_iuid, $first_sop_iuid, true);
    $pacsGateway = getUrlBase().GATEWAY_SCRIPT;
    $llamadaVisor = fnLlamadaVisor($first_sop_iuid, $pacsGateway);
    
    $onMouseOverMin = "this.className = 'miniatura_hover';";
    $onMouseOutMin = "this.className = 'miniatura';";
    // onmouseover=\"$onMouseOverMin\" onmouseout=\"$onMouseOutMin\" 

    $strSeries .= "<div class='bloque_serie'>";
    if ($num_imagenes == 1) {
      //echo 'entre al if de strSeriesEstudio (comun.php) <br>';mio
      $strSeries .= "$cad_serie";
      $strSeries .= "<div id='i1_series_$series_iuid'><img class='miniatura' onmouseover=\"$onMouseOverMin\" onmouseout=\"$onMouseOutMin\" src='$urlThumb' onclick=\"$llamadaVisor\"></div>";
    }
    else {
      //echo 'entre a l else de strSeriesEstudio (comun.php) <br>';mio
      $onClick = "javascript:mostrarOcultarInstancias('$study_iuid', '$series_iuid');";

      $onMouseOver = "this.className = 'psEnlaceSer_hover';";
      $onMouseOut = "this.className = 'pseudoenlace';";
      // onmouseover=\"$onMouseOver\" onmouseout=\"$onMouseOut\" 

      $strSeries .= "<div onclick=\"$onClick\" onmouseover=\"$onMouseOver\" onmouseout=\"$onMouseOut\" class='pseudoenlace'>[+] $cad_serie</div>";
      $strSeries .= "<div id='i1_series_$series_iuid'><img class='miniatura' onmouseover=\"$onMouseOverMin\" onmouseout=\"$onMouseOutMin\" src='$urlThumb' onclick=\"$llamadaVisor\"></div>";
      $strSeries .= "<div id='series_$series_iuid' style='display:none;'>";
      $strSeries .= "</div><!-- [serie] -->";
    }
    $strSeries .= "</div>";
    // $strSeries .= "<!-- class='bloque_serie' -->";

  }
  return $strSeries;
}

// ******* ********* ********* ********* ********* ********* ********* *********
/*
 * Se utilizan children() y attributes() para convertir un SimpleXMLElement en Array
 */

function nodo2array($nodo)
{
  //echo 'entre a nodo2array (comun.php) <br>';mio
  $arrayNodo = array();
  $arrayNodo['nombre'] = nodoGetName($nodo);
  $arrayNodo['atributos'] = array();
  foreach($nodo->attributes() as $nombre => $valor) {
    //echo 'entre al 1º foreach de nodo2array (comun.php) <br>';mio
    $arrayNodo['atributos'][$nombre] = (string) $valor;
  }
  $arrayNodo['hijos'] = array();
  foreach ($nodo->children() as $nodoHijo) {
    //echo 'entre al 2º foreach de nodo2array (comun.php) <br>';mio
    $arrayHijo = nodo2Array($nodoHijo);
    array_push($arrayNodo['hijos'], $arrayHijo);
  }
  return $arrayNodo;
}

// ******* ********* ********* ********* ********* ********* ********* *********

/*
 * Mientras no dispongamos de SimpleXMLElement->getName() usamos esta funcion
 */

function nodoGetName($nodo)
{
  //echo 'entre a nodoGetName (comun.php) <br>';mio
  $nodoXML = $nodo->asXML();
  //echo 'haber:'.$nodo.'<br>nodo:'.$nodoXML;mio
  // Se elimina la primera linea del XML: <?xml version="1.0"? >
  if (substr($nodoXML, 1, 4) == '?xml') {
    //echo 'entre al 1º if de nodoGetName (comun.php) <br>';mio
    $nodoXML_ = strtok($nodoXML, "\n");
    $nodoXML = strtok("\n");
  }
  $stringXML = substr(ltrim($nodoXML), 1);
  $nombre = strtok($stringXML, ' >');
  if ($nombre == '?xml') {
    //echo 'entre al 2º if de nodoGetName (comun.php) <br>';mio
    echo $stringXML."<br />\n";
  }
  return $nombre;
}

// ******* ********* ********* ********* ********* ********* ********* *********
// Version 2:
// ******* ********* ********* ********* ********* ********* ********* *********

function estudiosPaciente2($patientPk, $linkDCM4) {//patienPK se el id del study
  //echo 'entre a estudiosPaciente2 (comun.php) <br>';mio
  $query = "
    SELECT
      p.pat_id,
      st.study_iuid, st.study_datetime, st.study_desc, st.mods_in_study, st.num_series, st.num_instances as study_instances,
      se.series_iuid, se.modality, se.series_no as serie_num, se.num_instances as num_instances,
      i.sop_iuid, i.inst_no as inst_num
    FROM patient p, study st, series se, instance i
    WHERE st.pk = $patientPk
      AND st.patient_fk = p.pk
      AND st.pk = se.study_fk
      AND se.pk = i.series_fk
    ORDER BY study_datetime DESC, serie_num ASC, inst_num ASC
  ";
  
  //$resPre = mysql_query($query, $linkDCM4);
  $resPre = pg_query($linkDCM4, $query);  
//  echo mysql_error();
  //echo pg_error();mio
  // Se escoge unicamente el primer elemento de la lista

/*
  $study_iuid = 0;
  $series_iuid = 0;
  $sop_iuid = 0;
*/
  $estudios = array();
//  $estudio = array();

  //while ($episodio = mysql_fetch_assoc($resPre)) {
  while ($episodio = pg_fetch_assoc($resPre)) {
    //echo 'entre al while de estudiosPaciente2 (comun.php) <br>';mio
    // print_r($episodio);
    $study_iuid = $episodio['study_iuid'];
    $series_iuid = $episodio['series_iuid'];
    $sop_iuid = $episodio['sop_iuid'];
    // echo $study_iuid."<br>".$series_iuid."<br>".$sop_iuid."<br>";
    if (!array_key_exists($study_iuid, $estudios)) {
      //echo 'entre al 1º if de estudiosPaciente2 (comun.php) <br>';mio
//      $study_datetime = strftime("%Y-%m-%d", strtotime($episodio['study_datetime']));
      $study_datetime = $episodio['study_datetime'];
      $estudio = array(
        'study_instance_uid' => $study_iuid,
        'study_datetime' => $study_datetime,
        'modalidades' => $episodio['mods_in_study'],
        'descripcion' => $episodio['study_desc'],
        'num_series' => $episodio['num_series'],
        'series' => array()
      );
      $estudios[$study_iuid] = $estudio;
    }

    if (!array_key_exists($series_iuid, $estudios[$study_iuid]['series'])) {
      //echo 'entre al 2º if de estudiosPaciente2 (comun.php) <br>';mio
      $serie = array(
        'serie_instance_uid' => $episodio['series_iuid'],
        'serie_num' => $episodio['serie_num'],
        'modalidad' => $episodio['modality'],
        'num_instancias' => $episodio['num_instances'],
        'instancias' => array()
      );
      $estudios[$study_iuid]['series'][$series_iuid] = $serie;
      // echo "S";
    }

    $instancia = array(
      'sop_instance_uid' => $sop_iuid,
      'inst_num' => $episodio['inst_num']
    );
    $estudios[$study_iuid]['series'][$series_iuid]['instancias'][$sop_iuid] = $instancia;
    // echo "I<br>";
  }

  return $estudios;
}

// ******* ********* ********* ********* ********* ********* ********* *********

function mostrarEstudios_v2($infoPaciente) {
  //echo 'entre a mostrarEstudios_v2 (comun.php) <br>';mio
  $estudios = $infoPaciente['hijos'][1]['hijos'];
  foreach($estudios as $estudio) {
    //echo 'entre a foreach de mostrarEstudios_v2 (comun.php) <br>';mio
    $study_iuid = $estudio['atributos']['studyUID'];
    // valid var name as a javascript identifier
    $study_jsid = 'study_'.str_replace('.', '_', $study_iuid);

    $onMouseOver = "this.className = 'psEnlaceEst_hover';";
    $onMouseOut = "this.className = 'pseudoenlace';";
    $seriesEstudio = $estudio['hijos'];
/*    
echo "Series del estudio: ";
print_r($seriesEstudio);    
*/    
    // Se generan fragmentos HTML que se incluiran a posteriori para evitar precargas de imagenes innecesarias
    $onClick = "javascript:mostrarOcultarSeries('$study_jsid');";
//    $varName = 'kk';

    //print_r($strSeries);
//    setJsVar($varName, $strSeries);
    
    echo "<div class='bloque_estudio'>\n";
    echo "<div onclick=\"$onClick\" onmouseover=\"$onMouseOver\" onmouseout=\"$onMouseOut\" class='pseudoenlace'> {$estudio['atributos']['date']}, {$estudio['atributos']['description']}</div>\n";
    //echo "<div id='$study_jsid' style='display:none;'>";
    $strSeries = strSeriesEstudio_v2($study_iuid, $seriesEstudio);
    
    echo $strSeries;        
    echo "</div><!-- [int. estudio] -->\n";
    echo "</div><!-- class='bloque_estudio' -->\n\n";
  }
}

// ******* ********* ********* ********* ********* ********* ********* *********
function strSeriesEstudio_v2($study_iuid, $series) {
  //echo 'entre a strSeriesEstudio_v2 (comun.php) <br>';mio
  $numSeries = count($series);
  // Las series muestran su primera imagen. En caso de haber mas de una, generan AJAX para mostrar resto y/o ocultar.
  $numSerie = 0;
  $strSeries = '';
  foreach($series as $serie) {
    //echo 'entre a foreach de strSeriesEstudio_v2 (comun.php) <br>';mio
    // echo "SERIE: *** ";
    // print_r($serie);
    $series_iuid = $serie['atributos']['seriesUID'];
    $series_jsid = 'series_'.str_replace('.', '_', $series_iuid);
    $num_imagenes = $serie['atributos']['numinstances'];
    $numSerie++; // No se puede utilizar {$serie['serie_num']} porque no siempre comienzan en el #1
    $cad_imagenes = $num_imagenes == 1 ? 'imagen' : 'im&aacute;genes';
    $cad_serie = "Serie $numSerie [{$serie['atributos']['modality']}]: $num_imagenes $cad_imagenes";
/*
    // Identificador de la primera instancia de la serie:
    $first_sop_iuid = $serie['hijos'][0]['atributos']['objectUID'];    
    $urlThumb = urlWADO($study_iuid, $series_iuid, $first_sop_iuid, true);
    $llamadaVisor = fnLlamadaVisor($first_sop_iuid);
*/    
    $onMouseOverMin = "this.className = 'miniatura_hover';";
    $onMouseOutMin = "this.className = 'miniatura';";
    // onmouseover=\"$onMouseOverMin\" onmouseout=\"$onMouseOutMin\" 

    $strSeries .= "<div class='bloque_serie'>\n";
    
    $strInstancias = strInstanciasSerie_v2($study_iuid, $series_iuid, $serie['hijos']);
    /*
    if ($num_imagenes == 1) {
      $strSeries .= "$cad_serie";
      $strSeries .= "<div id='$series_jsid' style='display:none;'>";
      echo $strInstancias;
      $strSeries .= "</div><!-- [serie] -->\n";
    }
    else {
    */
      $onClick = "javascript:mostrarOcultarInstancias('$series_jsid');";
      $onMouseOver = "this.className = 'psEnlaceSer_hover';";
      $onMouseOut = "this.className = 'pseudoenlace';";

      ////$strSeries .= "<div onclick=\"$onClick\" onmouseover=\"$onMouseOver\" onmouseout=\"$onMouseOut\" class='pseudoenlace'>[+] $cad_serie</div>\n";
      //++++++++
      $strSeries .= "$cad_serie";
      $strSeries .= "<div id='i1_series_$series_iuid'>$strInstancias</div>";
      //++++++++
      // $strSeries .= "<div id='i1_series_$series_iuid'>\n";
      // $strSeries .= "<img class='miniatura' onmouseover=\"$onMouseOverMin\" onmouseout=\"$onMouseOutMin\" src='$urlThumb' onclick=\"$llamadaVisor\">";
      // $strSeries .= "</div>\n";
      //$strInstancias = strInstanciasSerie_v2($study_iuid, $series_iuid, $serie['hijos']);
      setJsVar($series_jsid, $strInstancias);
      $strSeries .= "<div id='$series_jsid' style='display:none;'>";
      $strSeries .= "</div><!-- [serie] -->\n";
//    }
    $strSeries .= "</div><!-- class='bloque_serie' -->\n";
  }
  return $strSeries;
}

// ******* ********* ********* ********* ********* ********* ********* ********* ME ROTORNA LA IMAGEN
function strInstanciasSerie_v2($study_iuid, $serie_iuid, $instancias) {
  //echo 'entre a strInstanciasSerie_v2 (comun.php) <br>';mio
  $numImgs = count($instancias);

  $onMouseOverMin = "this.className = 'miniatura_hover';";
  $onMouseOutMin = "this.className = 'miniatura';";
  $strInstancias = '';

  foreach($instancias as $instancia) {
    //echo 'entre a foreach de strIntanciasSerie_v2 (comun.php) <br>';
    // echo "Instancia ***";
    // print_r($instancia);
    $sop_iuid = $instancia['atributos']['objectUID'];
    $sop_jsid = 'sop_'.str_replace('.', '_', $sop_iuid);
    $strInstancias .= "<div id='$sop_jsid'>";
//    if ($instancia['inst_num'] == 1) {
      $urlThumb = urlJPEG($study_iuid, $serie_iuid, $sop_iuid, true);
      $pacsGateway = getUrlBase().GATEWAY_SCRIPT;   
      $llamadaVisor = fnLlamadaVisor($sop_iuid, $pacsGateway);
      $title = "Img. {$instancia['atributos']['instance_num']}/$numImgs";
      $strInstancias .= "<img class='miniatura' onmouseover=\"$onMouseOverMin\" onmouseout=\"$onMouseOutMin\" src='$urlThumb' onclick=\"$llamadaVisor\" title='$title'>";
//    }
    //echo 'llamada: '.$llamadaVisor.'<br>';
    $strInstancias .= "</div><!-- [miniatura] -->";
  }
  //echo $strInstancias;
  return $strInstancias;
}

// ******* ********* ********* ********* ********* ********* ********* *********

?>
