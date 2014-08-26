<?php
include ('../intraNet/scripts/libs/db.class.php');
$patient = $_REQUEST['p'];
$pass = $_REQUEST['pw'];
$db = NEW DB();
$sql = "SELECT id FROM patient WHERE password_temp='$pass' AND id=$patient";
$data = $db->doSql($sql);
if($data) {
    $sql = "UPDATE patient SET password='$pass', password_temp='' WHERE id=$patient";
    $db->doSql($sql);

    echo 'Contrase&ntilde;a activada';
    echo '<br>Se redireccionar&aacute; a la p&aacute;gina de Inicio de Sesi&oacute;n';
    echo '<br>Si pasados 5 segundos no se redirecciona, puede hacer clic en el siguiente enlace:';
    echo '<br><a href="../login.php" class="login-btn">Ir a Inicio de Sesi&oacute;n</a>';

}else{
    echo 'Este enlace ha expirado';
}

?>

<script type="text/javascript">
function redireccionar(){
    window.location="../login.php";
} 
setTimeout ("redireccionar()", 5000);
</script>

