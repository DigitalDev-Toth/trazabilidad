<?php
session_start();
if(!isset($_SESSION['Username'])) { header("location: ../../login.php?error=hack"); header('Content-Type: text/html; charset=latin1');  }
?>


<link href="../../style/styleAll.css" rel="stylesheet" type="text/css" />
<script src="../js/validaRut.js" type="text/javascript"></script>
<script src="../js/restriction.js" type="text/javascript"></script>
<?
/*
  Author: Cesar - Date: 09/03/10

  NONE.
*/
include("../libs/db.class.php");
$employee = new DB("employee", "id");
$employee->exceptions(array("id"));
$employee->changeFormObject('employee.gender','menu',NULL,array("Masculino"=>"M","Femenino"=>"F"));

//$employee->fooAdditions("employee.commune_id", array('type'=>'varchar', 'isNull'=>'NO'));
//$employee->autocomplete("employee_commune_id", "commune", "->name,employee.employee_commune->id");
//$employee->changeFormObject('employee.commune','hidden');
$employee->changeFormObject("employee.rut", "text", NULL, NULL, 'onblur="Valida_Rut(this)" onkeypress="return solorut_menu(event);"');
//$employee->changeFormObject("employee.rut", "text", NULL, NULL, 'onChange="Valida_Rut(this)" onkeypress="return solorut_menu(event);"');
$employee->changeFormObject("employee.phone", "text",NULL,NULL,'onkeypress="return onlyNumeric(event);"');

$employee->checkItemIfExist("employee_rut", "employee", "rut");
$employee->changeFormObject("employee.name", "text",NULL,NULL,'onkeypress="return onlyNotNumeric(event);"');
$employee->changeFormObject("employee.lastname", "text",NULL,NULL,'onkeypress="return onlyNotNumeric(event);"');

$employee->relation("branch", "branch", "id", "name");
$design = array(array('employee.rut', 'employee.name', 'employee.lastname'),
				array('employee.birthdate', 'employee.gender', 'employee.phone'),
				//array('employee.commune_id', 'employee.address', NULL),
				array('employee.address', 'employee.mail', 'employee.date_contract'),
				array('employee.special_terms', NULL, NULL)
				);
$employee->changeFormDesign($design);

$employee->changeFormObject("employee.special_terms", "basicEditor");

//$employee->relation("afp", "afp", "id", "name");
//$employee->changeFormObject('employee.isapre', 'text', NULL, NULL,'readonly');

//$datos = $employee->doSql("select id, name from prevision");
do
{
	if($datos['id']!='')
	{
		$datosArray[$datos['name']] = $datos['id'];
	}
}while($datos = pg_fetch_assoc($employee->actualResults));

//$employee->changeFormObject("employee.prevision", "menu", NULL, $datosArray, 'onchange="verificar(this.form, this.selectedIndex)"');
$employee->changeFormObject("employee.mail", "text", NULL, NULL, 'onchange="mail(this.value)"');


if (isset($_GET['update']))
{
	//var_dump($_REQUEST);
	$employee->updateData($_GET['update'], FALSE);
	$cmna = new DB();
	//$row = $cmna->doSql("select name from commune where id in(select commune from employee where id=".$_REQUEST['update'].")");
	//echo "<script>form_main.employee_commune_id.value='".$row['name']."'</script>";
}
else
{
	echo '<div algin="center" id="showTitle">INSERTAR EMPLEADOS</div>';
	
	if($employee->insertData(FALSE))
	{
		echo '<br><div id="back">';
		echo '<a href="../contentMain.php?module=employee"><img src="../../images/back.png"/>Volver al menu de EMPLEADOS</a>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><img src="../../images/mas.png"/>Agregar EMPLEADOS</a><br>';
		echo '</div>';
		exit();
	}
	echo '<br><div id="back"><a href="../contentMain.php?module=employee"><img src="../../images/back.png"/>Volver al menu de EMPLEADOS</a></div><br>';
	
}
?>
<script language="javascript">
function verificar(form, e){ 
	valor= form.employee_prevision.options[e].value;
	if(valor==4)// colocar el id que tiene isapre en prevision
	{
		
	  <?for ($j = 0; $j < $i; $j++)
	  {?>
			<?echo $stringIsapreScript[$j];?>
		<?
	  }?>   
	  employee.employee_isapre.style.display=''
	  campo = document.getElementById('lbl_employee_isapre');
	  campo.style.display = '';
	}
	else
	{
		employee.employee_isapre.style.display='none'
		campo = document.getElementById('lbl_employee_isapre');
		campo.style.display = 'none';
	}
	return true;
}

function mail(texto){

    var mailres = true;            
    var cadena = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890@._-";
    
    var arroba = texto.indexOf("@",0);
    if ((texto.lastIndexOf("@")) != arroba) arroba = -1;
    
    var punto = texto.lastIndexOf(".");
                
     for (var contador = 0 ; contador < texto.length ; contador++){
        if (cadena.indexOf(texto.substr(contador, 1),0) == -1){
            mailres = false;
            break;
     }
    }

    if ((arroba > 1) && (arroba + 1 < punto) && (punto + 1 < (texto.length)) && (mailres == true) && (texto.indexOf("..",0) == -1))
	{
		mailres = true;
	}
    else
	{		
		mailres = false;
		alert("Email no Valido");
	}
                
    return mailres;
}

</script>

<style type="text/css">
	table#tb_employee_address{
		width:100%;
	} 
	.lbl {
		width:80px;
	}
	td#td_employee_address input{
		width:95%;
	} 

</style>
<script>employee.employee_isapre.style.display='none'
		campo = document.getElementById('lbl_employee_isapre');
		campo.style.display = 'none';
</script>
