<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
      <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pacientes</title>
  <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery.zrssfeed.js" type="text/javascript"></script>
    <script src="js/jquery.vticker.js" type="text/javascript"></script>
    <script src="js/jquery.zweatherfeed.js" type="text/javascript"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/2-col-portfolio.css" rel="stylesheet">
  </head>
  <body>



<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <h2 class="page-header">FALP:
        <small id="modalityTitle">modalidad:<label id="modality"></label></small>
      </h2>
    </div>
  </div>

    <div class="row">
      <div class="col-lg-10 col-md-10" >
        <div class="panel panel-default">

          <div class="panel-heading" >Panel Paciente
          </div>

          <div class="panel-body" align="center">
            <div class="col-lg-5 col-md-5" >
              <table >
                <tr style="font-size:30px;"><th id="modalityName"></th></tr>
                <tr style="font-size:30px;"><th>Numero</th><th>Modulo</th></tr>
                <b>
                <tr style="font-size:100px;" id="rows"><td id="number"></td><td id="module" ></td></tr>
                </b>
              </table>
            </div>
         
          </div>

        </div>
      </div>
    </div>
   
    <div class="row">
      <div class="col-lg-8 col-md-8" >
        <b>
        <div class="alert alert-info">
      
        <div id="rss">
        </div><br>CNN noticias</div></b>
      </div>
      <div class="col-lg-4 col-md-4" >
      <div id="weather"></div>
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
var modalityPage='';
$(document).ready(function() {

  modalityPage=decodeURIComponent("<?php echo rawurlencode($_GET['id']); ?>");
  var modalityName=getModality(modalityPage);
  $('#modalityName').text(modalityName['modalityName']);

  $('#modality').text(modalityPage);
  $('#rss').rssfeed('http://www.cnnchile.com/rss/',{}, function(e) {
    $(e).find('div.rssBody').vTicker({ showItems: 1});
    $(".rssBody").height(76);
  });
  $('#weather').weatherfeed(['CIXX0031']);
  var results=getLastTicket(modalityPage);
  showLastTicket(results,"A");
  
});
function getModality(idModality){
    var result = null;
    var scriptUrl = "phps/getModality.php?idModality=" + idModality;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
    });
    return JSON.parse(result);
}
function getLastTicket(idModality){
    var result = null;
    var scriptUrl = "phps/getLastTicket.php?modality=" + idModality;
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
function showLastTicket(ticket,module){

  if(ticket!=''){
    if(ticket<10){
      ticket='00'+ticket;
    }
    if(ticket>=10 && ticket<100){
      ticket='0'+ticket;
    }
 
    $('#rows').fadeOut('slow', function() {
        $('#rows').fadeIn('slow');
        if(ticket=='null'){
          $('#number').text('S/N');
          $('#module').text('');
        }else{
           $('#number').text(ticket);
          $('#module').text(module);
        }
       
    });
    
  }
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
          setTimeout(function(){ comet.connect(); }, 1000);           
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
    setNumber(response.msg);    
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



function setNumber(message){
  var split=message.split("?");
  var currentNumber=split[0];
  var modality=split[1];
  var module=split[2];

  if(modality==modalityPage){
   
    //$('#number').text(currentNumber);
    $('#modality').text(modality);
    //$('#module').text(module);
    showLastTicket(currentNumber,module);
  }

    // if(currentNumber<10){
    //   currentNumber='00'+currentNumber;
    // }
    // if(currentNumber>=10 && currentNumber<100){
    //   currentNumber='0'+currentNumber;
    // }

}
</script>

</body>
</html>