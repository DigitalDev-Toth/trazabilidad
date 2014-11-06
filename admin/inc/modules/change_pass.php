<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../../login.php?error=hack"); header('Content-Type: text/html; charset=utf-8');  }

include("libs/db.class.php");
 
if(isset($_POST['my_pass']))
{
	$users = new DB("users", "id");
	
	$row = $users->doSql('SELECT password FROM users WHERE id='.$_SESSION['UserId']);
	if(md5($_POST['my_pass'])==$row['password'])
	{
		if($_POST['new_pass'] == $_POST['renew_pass']){
			if($_POST['new_pass'] == '' || $_POST['renew_pass']==''){
					?>
					<script>
					//	alert("No se permiten Contraseña en Blanco");	
					</script>
					<?
				}
				else{
					$new = md5($_POST['new_pass']);
					$users->doSql('UPDATE users SET password ='."'".$new."'".' WHERE id = '.$_SESSION['UserId']);
					?>
					<script>
						alert("Contrase\u00f1a modificada satisfactoriamente");
					</script>
					<?
				}
		}else{
			?>
			<script>
				alert("Las contrase\u00f1as no coinciden");	
			</script>
			<?
		}
	}
	else
	{
			?>
			<script>
				alert("Contrase\u00f1a incorrecta, intentelo nuevamente");
			</script>
			<?
	}
}

$users = new DB("users", "id");
$users->myPathForm='../';
$_REQUEST['password']='';
?>
<script src="js/jquery-1.6.4.min.js" type="text/javascript"></script>
<link href="../style/styleAll.css" rel="stylesheet" type="text/css" />
<script>
	$(document).ready(function(){
		$("#pass").fadeIn(600);
	});
/*blinker*/
	function blinker(n, level){
	n.style.backgroundColor="#FFA6A6";
	setTimeout("document.getElementsByName('"+n.name+"')[0].style.backgroundColor=''", 50);
	if(level!=10){
		setTimeout("blinker(document.getElementsByName('"+n.name+"')[0], "+(level+1)+")",100);
	}
}
function verify(f){
	con=0;
	for(ii=0;n=f.elements[ii];ii++){
		if(n.id=="isNull"){
			if(n.value==''){
				blinker(n, 0);
				con++;
			}
		}
	}
	if (con>0){
		return false;
	}else{
		return true;
	}
}
/*fin blinker*/
</script>
	<div align="center" id="showTitle">Cambiar Contrase&ntilde;a</div>

	<div align="center" id="change_pass">
		<form name="between" id="between" method="POST" action="" onsubmit="return false;">
			<table>
				<tr>
					<td class="td_pass">Contrase&ntilde;a actual</td>			
					<?
					$users->myPathShow = "../";
					echo $users->makeObjectForm("my_pass", array('type'=>'password', 'isNull'=>'NO'));
					?>				
				</tr>
				<tr>
					<td class="td_pass">Nuevo Contrase&ntilde;a</td>
					<?  echo $users->makeObjectForm("new_pass", array('type'=>'password', 'isNull'=>'NO')); ?>
				</tr>
				<tr>
					<td class="td_pass">Confirmar nueva Contrase&ntilde;a</td>
					<?  echo $users->makeObjectForm("renew_pass", array('type'=>'password', 'isNull'=>'NO'));?>
				</tr>
				<tr><td colspan="2"><input id="button" style="float:right;" type="submit" value="Cambiar" onclick="if(verify(this.form)) between.submit();"></td></tr>
			</table>
		</form>
	</div>
