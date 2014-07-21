<?
$wado = "http://localhost:8080/wado?requestType=WADO";
$series_pk=$_GET['series_pk'];
$separator = "@@";

include("../inc/libs/db.class.php");
$study = new DB("study", "pk", "pacsdb");
$series = new DB("series", "pk", "pacsdb");
$patient = new DB("patient", "pk", "pacsdb");

$patient_row = $patient->doSql("SELECT * FROM patient WHERE pk=(SELECT patient_fk FROM study WHERE pk=(SELECT study_fk FROM series WHERE pk=$series_pk))");
//calculo edad
$birthDate=$patient_row['pat_birthdate'];
$dia=date("d");
$mes=date("m");
$ano=date("Y");
//fecha de nacimiento
$dianaz=substr($birthDate,-2,2);
$mesnaz=substr($birthDate,4,2);
$anonaz=substr($birthDate,0,4);
if (($mesnaz == $mes) && ($dianaz > $dia)) {
 $ano=($ano-1);
}
if ($mesnaz > $mes) {
 $ano=($ano-1);
}
$edad=$ano-$anonaz;
$nmes=$mes-$mesnaz;

if($patient_row['pat_sex']=='F' || $patient_row['pat_sex']=='f') $sex="Mujer";
elseif($patient_row['pat_sex']=='M' || $patient_row['pat_sex']=='m') $sex="Hombre";
else $sex="Indefinido";

$study_row = $study->doSql("SELECT * FROM study WHERE pk=(SELECT study_fk FROM series WHERE pk=$series_pk)");
$series_row = $series->doSql("SELECT * FROM series WHERE pk=$series_pk");

$pat_name="pat_name=".str_replace("^", " ", $patient_row['pat_name'])."\n";
$pat_age="pat_age=".$edad." years, ".$nmes." Months\n";
$study_desc="study_desc=".$study_row['study_desc']."\n";
$serie_desc="serie_desc=".$series_row['series_desc']."\n";
$institution="institution=".$series_row['institution']."\n";
$pat_sex="pat_sex=".$sex."\n";

$imagesTxt = "images=";
$images = $study->doSql("SELECT * FROM instance WHERE series_fk=$series_pk");
$i=0;
do
{
	$imagesTxt .= $wado."*studyUID=".$study_row['study_iuid']."*seriesUID=".$series_row['series_iuid']."*objectUID=".$images['sop_iuid'].$separator;
	//$imagesTxt .= $i.$separator;
	$i++;
}while($images=pg_fetch_assoc($study->actualResults));

$script = 'visorStratos_002.swf';
$nombre = 'visorStratos';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="style/biopacs.css" rel="stylesheet" type="text/css" />
</head>
	<body>
		<div align="center" style="color: #FFFFFF; font-size: 16px; font-weight: bold;">
		<? if($series_pk) { ?>
		   <object class='visor'
			  classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000'
			  id='<? echo $nombre; ?>'
			  align='middle'>
			  <param name='allowScriptAccess' value='always' />
			  <param name='movie' value='<? echo $script; ?>' />
			  <param name='quality' value='high' />
			  <param name='scale' value='noscale' /> 
			  <param name='bgcolor' value='#000000' />
			  <param name='FlashVars' value='<? echo "series_pk=$series_pk&$pat_name&$pat_age&$study_desc&$serie_desc&$institution&$pat_sex&$imagesTxt"; ?>'>
				<embed class='visor'
				  src='<? echo $script; ?>'
				  quality='high'
				  scale='noscale'
				  bgcolor='#000000'
				  name='$nombre'
				  width = '100%'
      			  height = '100%'
				  align='middle'
				  allowScriptAccess='always'
				  type='application/x-shockwave-flash'
				  pluginspage='http://www.macromedia.com/go/getflashplayer'
				  FlashVars='<? echo "series_pk=$series_pk&$pat_name&$pat_age&$study_desc&$serie_desc&$institution&$pat_sex&$imagesTxt"; ?>'
				/>
			</object>
		<? } ?>
		</div>
	</body>
</html>

