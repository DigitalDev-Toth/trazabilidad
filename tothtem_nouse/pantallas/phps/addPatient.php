<?php
include ('../intraNet/scripts/libs/db.class.php');
$rut=$_REQUEST['rut'];
$name=$_REQUEST['name'];
$lastname=$_REQUEST['lastname'];
$cellphone=$_REQUEST['cellphone'];
$email=$_REQUEST['email'];
$pass = md5($_REQUEST['password']);
$db = NEW DB();
$sql="INSERT INTO patient(rut,name,lastname,cellphone,email,password_temp) VALUES('$rut','$name','$lastname','$cellphone','$email','$pass')";
$db->doSql($sql);
$sql="SELECT id FROM patient ORDER BY id DESC LIMIT 1";
$row = $db->doSql($sql);
$id = $row['id'];
$para      = $email;
$titulo = 'Clinica San Martin Validacion Correo';
$mensaje = "Favor seguir el link para activar cuenta.<br> http://ns2.digitaldev.org/sanmartin/phps/validateMail.php?p=$id&pw=$pass";
$cabeceras = 'From: scarcamo@biopacs.com' . "\r\n" .
    'Reply-To: scarcamo@biopacs.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($para, $titulo, $mensaje, $cabeceras);
echo "OKIDOKI";
?>
