<?php
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','On');
include ('../intraNet/scripts/libs/db.class.php');
$rut = $_REQUEST['rut'];
$pass = md5($_REQUEST['pass']);
$email = $_REQUEST['email'];

$db = NEW DB();
$sql = "UPDATE patient SET password_temp='$pass', email='$email' WHERE rut='$rut'";
$row = $db->doSql($sql);

$sql = "SELECT id, password_temp FROM patient WHERE rut='$rut'";
$row = $db->doSql($sql);
$id = $row['id'];
if($row['password_temp']==$pass){
    $para      = $email;
    $titulo = 'Clinica San Martin Validacion Correo';
    $mensaje = "Favor seguir el link para activar cuenta.<br> http://ns2.digitaldev.org/sanmartin/phps/validateMail.php?p=$id&pw=$pass";
    $cabeceras = 'From: scarcamo@biopacs.com' . "\r\n" .
        'Reply-To: scarcamo@biopacs.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    mail($para, $titulo, $mensaje, $cabeceras);
    echo 1;
}else{
    echo 2;
}

?>
