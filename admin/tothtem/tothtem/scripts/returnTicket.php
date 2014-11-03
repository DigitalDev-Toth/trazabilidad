<html>
  <head>
  <script src="../js/jquery-2.0.3.js" type="text/javascript"></script>
  <script src="../js/jquery-barcode.min.js" type="text/javascript" ></script>  
  <script src="http://192.168.0.104:8000/socket.io/socket.io.js"></script>
  </head>
  <body>
    <div align="center"> 
      <img src="../img/logo.png" style="width: 128px;"><br><br>
         <div id="ZoneModule"></div>
      <label>Su Numero de atencion:</label><br>
      <b><label id="ticket" style="font-size:42pt;"></label></b>
      <div id="barCode"></div>
      <br>
   
      <div id="time"></div>
      <div id="Date"></div>
      <div id="urlSmartphone"></div>
      <div id="coments"></div>
    </div>   
  </body>
</html>
<script type="text/javascript">

var socket = io.connect('http://192.168.0.104:8000');
var rut = decodeURIComponent("<?php echo rawurlencode($_GET['rut']); ?>");
var module = decodeURIComponent("<?php echo rawurlencode($_GET['ticketOption']); ?>");
var moduleSpecial = decodeURIComponent("<?php echo rawurlencode($_GET['moduleSpecial']); ?>");
var totemId = decodeURIComponent("<?php echo rawurlencode($_GET['totemId']); ?>");
var jsonData=getLastTicket(module,rut,moduleSpecial);
var json = JSON.parse(jsonData);
/*Arreglo json con 2 registros:
  1- Para comet de submódulos de recepción de tickets
  2- Para comet de visualización
*/

//Agrega la informacion al ticket
ticketInfo(json['module'],moduleSpecial,json['newticket']);
$(document).ready(function () {
	window.print();
});



function getLastTicket(module,rut,moduleSpecial){
    var ticket = null;
    var scriptUrl = "getTicket.php?module="+module+"&rut="+rut+"&moduleSpecial="+moduleSpecial+"&ip="+totemId;
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

function ticketInfo(module,special,newTicket){
  var info = null;
  var scriptUrl = "ticketInfo.php?special="+special+"&module="+module;
  $.ajax({
      url: scriptUrl,
      type: 'get',
      dataType: 'html',
      async: false,
      success: function(data) {
          info = data;
      } 
  });
  if(newTicket.length==2){
    newTicket='00'+newTicket;
  }
  if(newTicket.length==3){
    newTicket='0'+newTicket;
  }

  var json = JSON.parse(info);
  $("#ZoneModule").html(json['zone']+" <br> "+json['module']);
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
  $("#time").text('Hora Pedido: '+time);
  $("#Date").text('Fecha: '+Tdate);

  $("#ticket").text(newTicket);
  $("#barCode").barcode(rut, "code39"); 
  //code39 code93 code128 codabar Datamatrix->qr datamatrix

  //BACKEND PARA EL COMET, SE INTEGRA CON LOS MÓDULOS DE GESTIÓN, HABILITAR UNA VEZ ESTÉ EN GIT
  socket.send(jsonData);
  /*$.post('../../../../visor/comet/backend.php',{msg: jsonData},function(data, textStatus, xhr){
      //console.log("comet->"+data);
  });*/

  
}


</script>

