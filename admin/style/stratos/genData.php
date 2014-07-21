<?
$series_pk=$_GET['series_pk'];

$separator = "$$";
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

echo "name=".str_replace("^", " ", $patient_row['pat_name'])."<br>";
echo "age=".$edad." years, ".$nmes." Months<br>";
echo "study_desc=".$study_row['study_desc']."<br>";
echo "serie_desc=".$series_row['series_desc']."<br>";
echo "institution=".$series_row['institution']."<br>";
echo "sex=".$sex."<br>";

$imagesTxt = "images=";
$images = $study->doSql("SELECT * FROM instance WHERE series_fk=$series_pk");
$i=0;
do
{
	$imagesTxt .= $images['sop_iuid'].$separator;
	//$imagesTxt .= $i.$separator;
	$i++;
}while($images=pg_fetch_assoc($study->actualResults));
echo $imagesTxt;
?>
