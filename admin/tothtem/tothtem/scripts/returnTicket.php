<html>
  <head>
  <script src="../js/jquery-2.0.3.js" type="text/javascript"></script>
  <script src="../js/jquery-barcode.min.js" type="text/javascript" ></script>  
  </head>
  <body>
    <div align="center"> 
      <img src="../img/logo.png" style="width: 128px;"><br>
      <label>Su Numero de atencion:</label><br>
      <label id="ticket" style="font-size:42pt;"></label>

      <div id="barCode"></div>
      <div id="time"></div>
      <div id="Date"></div>
      <div id="urlSmartphone"></div>
      <div id="coments"></div>
    </div>   
  </body>
</html>
<script type="text/javascript">
$(document).ready(function() {

});

var rut = decodeURIComponent("<?php echo rawurlencode($_GET['rut']); ?>");
//var modality = decodeURIComponent("<?php echo rawurlencode($_GET['modality']); ?>");
var module = decodeURIComponent("<?php echo rawurlencode($_GET['ticketOption']); ?>");
var moduleSpecial = decodeURIComponent("<?php echo rawurlencode($_GET['moduleSpecial']); ?>");

console.log(module,rut,moduleSpecial);
var jsonData=getLastTicket(module,rut,moduleSpecial);
console.log(jsonData);
var json = JSON.parse(jsonData);
/*Arreglo json con 2 registros:
  1- Para comet de submódulos de recepción de tickets
  2- Para comet de visualización
*/

var newTicket=json['newticket'];
$("#ticket").text(newTicket);

$("#barCode").barcode(rut, "datamatrix"); 
//code39 code93 code128 codabar Datamatrix->qr
console.log(jsonData);
//BACKEND PARA EL COMET, SE INTEGRA CON LOS MÓDULOS DE GESTIÓN, HABILITAR UNA VEZ ESTÉ EN GIT
$.post('../../../../visor/comet/backend.php',{msg: jsonData},function(data, textStatus, xhr){
    //console.log("comet->"+data);
});
var newDate=new Date();
var month=newDate.getMonth()+1;
var day=newDate.getDate()+1;
var year=newDate.getFullYear();
var hour=newDate.getHours();
var min=newDate.getMinutes();
if (month < 10) { month = '0' + month;}
if (day < 10) { day = '0' + day; }
if (min < 10) { min = '0' + min; }
var time=hour+":"+min;
var Tdate=day+"/"+month+"/"+year;
$("#time").text(time);
$("#Date").text(Tdate);

$(document).ready(function () {
	window.print();
});



function getLastTicket(module,rut,moduleSpecial){
    var ticket = null;
    var scriptUrl = "getTicket.php?module="+module+"&rut="+rut+"&moduleSpecial="+moduleSpecial;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            ticket = data;
        } 
    });

    return ticket;
}


</script>

