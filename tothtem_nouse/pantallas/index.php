<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>pantallas</title>
	  <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/2-col-portfolio.css" rel="stylesheet">
  
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Pantalla:
                    <small id="modalityTitle">...</small>
                </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-8" >
                <div class="panel panel-default">
                    <div class="panel-heading">Panel De Control</div>
                    <div class="panel-body" align="center">
                        <div class="col-lg-10 col-md-10" >
                            <label>Numero</label><br>
                            <label id="content" style="font-size:70px;"></label>
                            <div id="buttons">
                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet(2)">  <span class="glyphicon glyphicon-minus"></span></button>
                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet(1)" id="plusButton">  <span class="glyphicon glyphicon-plus"></span></button>-
                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet(3)">  <span class="glyphicon glyphicon-repeat"></span></button>
                                <button type="button" class="btn btn-default btn-lg" onclick="sendComet(4)">  <span class="glyphicon glyphicon-bullhorn"></span></button> 
                            </div>
                        </div>
                        <div style="right: 85px;" class="col-lg-2 col-md-2" >
                            <label>Modulo</label><br>
                            <div id="buttonsModules" align="center">
                                <button type="button" style="width:45px"  class="btn btn-default btn-xs " onclick="changeModule(1)">  <span class="glyphicon glyphicon-chevron-up"></span></button>
                                <button id="module" type="button" class="btn btn-default btn-lg" disabled>A</button>
                                <button type="button" style="width: 45px;" class="btn btn-default btn-xs " onclick="changeModule(0)">  <span class="glyphicon glyphicon-chevron-down"></span></button>
                            </div>
                        </div>
                    </div>
                    <table id="contentTicket" class="table table-striped" >
                        <th>Numero de atencion</th><th>RUT</th><th>Hora pedido</th><th> Atendido?</th>
                    </table>
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

//davidwalsh.name/demo/bg-clouds.jpg


var submodule="";
var currentModule=0;
var currentNumber='';
function changeModule(changeM){
    var array=["A","B","C","D","E"];

    if(changeM==1){
      currentModule++;
    }else{
      currentModule--;
    }
    if(currentModule==-1){
      currentModule=4;
    }
    if(currentModule==5){
      currentModule=0;
    }
    $("#module").text(array[currentModule]);
}

$( document ).ready(function() {
    submodule=decodeURIComponent("<?php echo rawurlencode($_GET['id']); ?>");

    if(submodule!=""){
        //$("#patientView").attr("src", "pantalla.php?id="+module);
        var json=getModule(submodule);
        var dataModality=JSON.parse(json);

        var lastTicketModality=dataModality['modalityTicket'];
        var modalityName=dataModality['modalityName'];

        $("#modalityTitle").text(modalityName);
        initNumber=lastTicketModality;
        //$('#content').text(initNumber);
        sendComet(0);
        refreshTable();
    }else{
        alert("Falta Modalidad!");
    }
  	//segun la bd
});

var initNumber;

function getModule(idSubModule){
    var result = null;
    var scriptUrl = "phps/getModule.php?idSubModule=" + idSubModule;
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
function refreshTickets(idsubModule,attention,tickets){
    var result = null;
    var scriptUrl = "phps/refreshTickets.php?submodule="+idsubModule+"&attention="+attention+"&tickets="+tickets;
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
function sendComet(type){
  	$('#buttons :input').attr('disabled', true);

  	if(type==1){
    		initNumber++;
        //remove last ticket
        var attention='';
        if($("#checkServe").is(':checked')) {  
            attention='on_serve';
        }else{
            attention='no_serve';
        }
        refreshTickets(submodule,attention,(initNumber-1));
        //comet2.doRequest("!");
  	}
  	if(type==2){
        if(initNumber>1){
            initNumber--;
        }
  	}
  	if(type==3){
    		if (confirm('reset?')) {
    			  initNumber=1;
    		}
  	}
    currentNumber=initNumber;
    if(currentNumber<10){
        currentNumber='00'+currentNumber;
    }
    if(currentNumber>=10 && currentNumber<100){
        currentNumber='0'+currentNumber;
    }
    $('#content').fadeOut("slow",function(){
        $('#content').fadeIn("fast");
        if(currentNumber!='null'){
            $('#content').text(currentNumber);
        }else{
            $('#content').text('S/N');
        } 
    });

  	window.setTimeout(function(){
      	$('#buttons :input').attr('disabled', false);
    }, 1000);
    var currentModuleLetter=$("#module").text();
    //write document
  	comet.doRequest(initNumber+"?"+module+"?"+currentModuleLetter);
}



// comet implementation
var Comet = function (data_url) {
    this.timestamp = 0;
    this.url = data_url;
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
        $('#buttons :input').attr('disabled', true);
        window.setTimeout(function(){
            $('#buttons :input').attr('disabled', false);
        }, 1000);
        var message=response.msg;

        var split=message.split("?");
        currentNumber=split[0];
        var currentModality=split[1];

        if(modality==currentModality){
            initNumber=currentNumber;
            if(currentNumber<10){
                currentNumber='00'+currentNumber;
            }
            if(currentNumber>=10 && currentNumber<100){
                currentNumber='0'+currentNumber;
            }
           
            $('#content').fadeOut("slow",function(){
            $('#content').fadeIn("fast"); 
                if(currentNumber!='null'){
                    $('#content').text(currentNumber);
                }else{
                    $('#content').text('S/N');
                } 
            });
            window.setTimeout(function(){
                $('#buttons :input').attr('disabled', false);
            }, 1000);
        }
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



// comet implementation 2!
var Comet2 = function (data_url) {
    this.timestamp = 0;
    this.url = data_url;
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
                  setTimeout(function(){ comet2.connect(); }, 1000);           
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
      //var json= JSON.parse(response.msg);
      //{"newticket":25,"modality":"34","date_t":"06\/06\/2014","hour_start":"10:48","hour_end":"NaN","rut":"17.443.625-8"}
      
            //var ticket=json['newticket'];
            //var hourStart=json['hour_start'];
            //var rut=json['rut'];
            //consulta ultimos 5 ticketsTable
        refreshTable();
      //$("#jsonData").text(response.msg);    
    }

    this.doRequest = function(request) {
        $.ajax({
          type : 'get',
          url : this.url,
          data : {'msg' : request}
        });
    }
}

//var comet2 = new Comet2('../prototipo/phps/backend.php');
//comet2.connect();

function refreshTable(){
    var totalResult=getLast5Tickets(submodule,initNumber);
    if(totalResult==0){
        $('#contentTicket tr').has('td').remove();
        $('#contentTicket').append('<tr><td>No hay pacientes en espera...</td></tr>');
    }else{
        var ticketsTable = JSON.parse(totalResult);
        var cant=Object.keys(ticketsTable).length;
        $('#contentTicket').fadeOut('slow', function() {
            $('#contentTicket tr').has('td').remove();
                for (var i=0;i<cant;i++) {
                    if(i==0){
                        $('#contentTicket').append('<tr  class="info"><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime']+'</td><td> <input type="checkbox" id="checkServe" checked></td></tr>');
                    }else{
                        $('#contentTicket').append('<tr><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime']+'</td>  </tr></tr>');  
                    }
                }
            $('#contentTicket').fadeIn('slow');
        });
    }
}
function getLast5Tickets(idModule,last){
    var result = null;
    var scriptUrl = "phps/lastTickets.php?submodule="+idModule+"&last="+last;
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