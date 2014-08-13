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
    sendComet();
});
var rut = decodeURIComponent("<?php echo rawurlencode($_GET['rut']); ?>");
var modality = decodeURIComponent("<?php echo rawurlencode($_GET['modality']); ?>");
var jsonData=getLastTicket(modality,rut);
var json = JSON.parse(jsonData);
var newTicket=json['newticket'];
$("#ticket").text(newTicket);

$("#barCode").barcode(rut, "datamatrix"); 
//code39 code93 code128 codabar Datamatrix->qr

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



function getLastTicket(modality,rut){
	 var ticket = null;
     var scriptUrl = "getTicket.php?modality="+modality+"&rut="+rut;
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


//super Comet!
function sendComet(){
    comet.doRequest(jsonData);
}



// comet implementation
var Comet = function (data_url) {
  this.timestamp = 0;
  this.url = data_url;
  console.debug(this.url)
  this.noerror = true;

  this.connect = function() {
    var self = this;
    $.ajax({
      type : 'get',
      url : this.url,
      dataType : 'json', 
      data : {'timestamp' : self.timestamp},
      success : function(response) {
        self.timestamp = response.timestamp;
        self.handleResponse(response);
        self.noerror = true;          
      },
      complete : function(response) {
        // send a new ajax request when this request is finished
        if (!self.noerror) {
          // if a connection problem occurs, try to reconnect each 5 seconds
          setTimeout(function(){ comet.connect(); }, 5000);           
        }else {
          // persistent connection
          self.connect(); 
        }

        self.noerror = false; 
      }
    });
  }

  this.disconnect = function() {}

  this.handleResponse = function(response) {
    //var currentNumber=response.msg;
  }

  this.doRequest = function(request) {
      $.ajax({
        type : 'get',
        url : this.url,
        data : {'msg' : request}
      });
  }

}

var comet = new Comet('backend.php');
comet.connect();

</script>

