<?php
//include("libs/db.class.php");
$rut = $_REQUEST['rut'];

$dbconn = pg_connect("host=biopacs.com port=5432 dbname=es14b_hrt2 user=postgres password=justgoon") or die('NO HAY CONEXION: ' . pg_last_error());

$sql= "SELECT * FROM patient WHERE rut='$rut'";


$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$rows = pg_numrows($result);
for($i=0;$i<$rows; $i++){
	$line = pg_fetch_array($result, null, PGSQL_ASSOC);
	$name =  $line['name'].' '.$line['lastname'];

}
pg_free_result($result);
// Closing connection
pg_close($dbconn);
echo $name;
?>