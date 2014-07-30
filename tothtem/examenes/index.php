<?php

 if (isset($_GET['id']))
    {
        if(strlen($_GET['id'])<1){
            echo "url invalida";   
            die();  
        }else{
            session_start();
            $_SESSION['md5Id']  = $_GET['id'];
        }
     
    }


   //echo md5(60699);
        error_reporting(E_ALL);
 ini_set('display_errors', 1);
require_once('phps/recaptchalib.php');
$publickey = "6LeUWfUSAAAAAIOzklorfAo4BG_I3afqcWYsR4Ly";
$privatekey = "6LeUWfUSAAAAAKEKZ5Lsw0a3_SxLwnagh7kL8Mhm";
$error = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Informes</title>
    <script src="js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
    <script src="js/bootstrap.js"></script>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/2-col-portfolio.css" rel="stylesheet">

</head>
<script type="text/javascript">
    var RecaptchaOptions = {
    theme : 'clean'
 };
</script>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
              <h3 class="page-header">Mis Examenes
                    
                </h3>
            </div>
        </div>
        <div class="row" id="login">
          <div class="col-lg-8 col-md-8" >
            <div class="panel panel-default">
            <div class="panel-heading">Verificacion</div>
              <div class="panel-body" >

                <form role="form">
                  <div class="form-group">
                    Para ver sus examenes , complete los siguientes campos<br>
                    <label>Rut</label>
                    <input type="text" class="form-control" id="rut" placeholder="Ej: 12.345.678-9" required>
                  </div>
                   <div id="formAlert" class="alert alert-warning" style="display:none">  
                    Verifique su rut e intentelo de nuevo.
                  </div>
                  <div class="form-group">
                    <label>Captcha</label><br>
                    Como medida de seguridad ingrese el texto que aparece acontinuacion
                <?php require_once('phps/recaptchalib.php');
                      $publickey = "6LeUWfUSAAAAAIOzklorfAo4BG_I3afqcWYsR4Ly";
                      echo recaptcha_get_html($publickey);
                ?>
                   <div id="formCaptcha" class="alert alert-warning" style="display:none">  
                    Ingrese el texto correctamente
                  </div>
                  </div>
                  <button type="button" class="btn btn-primary" onclick="getData();">Aceptar</button>
                </form>       
             
              </div>
            </div>
          </div>
        </div>


        <div class="row" id="exams" style="display:none">
          <div class="col-lg-6 col-md-6" >
            <div class="panel panel-default">
            <div class="panel-heading">Examenes</div>
              <div class="panel-body" >
                <form role="form">
                  <div class="form-group" id="AllMyExams">
                  </div>
                </form>       
             
              </div>
            </div>
          </div>
        </div>
   


        <hr>
        <footer>
          <div class="row">
            <div class="col-lg-12">
              <p>Toth 2014 &copy;</p>
            </div>
          </div>
        </footer>

</div>
<script type="text/javascript">

function captcha(result){
        var v1 = $("input#recaptcha_challenge_field").val();
        var v2 = $("input#recaptcha_response_field").val();
        $.ajax({
              type: "POST",
              url: "phps/check.php",
              data: {
                        "recaptcha_challenge_field" : v1, 
                        "recaptcha_response_field" : v2
              },
              dataType: "text",
              error: function(){
                    alert("error petición ajax");
              },
              success: function(data){
                console.log("result: "+result);
                if(result!=0){
                      console.log(data);
                      if(data == 0){
                        Recaptcha.reload();
                          $("#formCaptcha").fadeIn('slow');
                          setInterval(function(){ $("#formCaptcha").fadeOut('slow');}, 5000);
                      }else{
                          showExam(JSON.parse(result));   
                      }
                  }else{
                    badRut();
                  }
              }
        });
         
  }
var rutPatient="";
function getData(){
    if($("#rut").val()!=""){
        idPatient=decodeURIComponent("<?php echo rawurlencode($_GET['id']); ?>");
        rutPatient=$("#rut").val().toUpperCase();
        var result = null;
        var scriptUrl = "phps/checkData.php?idPatient=" + idPatient+"&rutPatient="+rutPatient;
        $.ajax({
            url: scriptUrl,
            type: 'get',
            dataType: 'html',
            async: false,
            success: function(data) {
                result = data;
            } 
        });
        captcha(result);
    }else{
       badRut();
    }
 
}


 function badRut(){
    //$("#rut").fadeTo(100, 0.1).fadeTo(200, 1.0).fadeTo(100, 0.1).fadeTo(200, 1.0);
    $("#formAlert").fadeIn('slow');
    setInterval(function(){ $("#formAlert").fadeOut('slow');}, 5000);
 }
   

function showExam(result){
    $( "#AllMyExams" ).empty();
    if(result!=0){
        $("#login").fadeOut('slow', function() {
            dataP=getDataPatient(rutPatient.toUpperCase());
            dataJsonP=JSON.parse(dataP);
            var content="<div class='alert alert-info'>"+dataJsonP[0]['rut']+" "+dataJsonP[0]['name']+" "+dataJsonP[0]['lastname']+"</div>";
            $("#exams").fadeIn('slow');
            content += "<table class='table table-striped' >";
            content+= '<tr><th> Nombre Examen </th><th>Fecha</th><th>Doctor de referencia</th><th>Mostrar</th></tr>';
            var dataJson;
            for (var i = Object.keys(result).length - 1; i >= 0; i--) {
                dataJson="";
                data=getHistoryReport(result[i]['id']);
                dataJson=JSON.parse(data);
                for (var j = Object.keys(dataJson).length - 1; j >= 0; j--) {
                    var doctor=dataJson[j]['doctor'];
                    if(doctor==null){
                        doctor="Sin referencia";
                    }
                    var url="phps/history_viewer.php?id="+dataJson[j]['id'];
                    content+= '<tr><td>' + dataJson[j]['exam'] + '</td><td>' + dataJson[j]['date']+ '</td><td>' + doctor+ ' </td><td> <a href='+url+' target="_blank"> <span class="glyphicon glyphicon-list-alt" style="font-size: 1.9em;"></span></a>  </td></tr>';    
                };
            }
            content += "</table>";
            $('#AllMyExams').append(content);
        });
    }else{
        var content = "<div class='alert alert-success'>No tiene examenes para mostrar</div>";
        $('#AllMyExams').append(content);
    }
}


function getHistoryReport(idCalendar){
    var result = null;
    var scriptUrl = "phps/getExams.php?calendar=" + idCalendar;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
    });
    return result;
}
function getDataPatient(rut){
    var result = null;
    var scriptUrl = "phps/getDataPatient.php?rut=" + rut;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
    });
    return result;
}
</script>
</body>
</html>