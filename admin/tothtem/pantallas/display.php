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



        <!--<div class="container" style="width:100%;border: 1px solid black;">-->
        <div style="width:100%; height: 100%;">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-header">Toth:
                        <small id="modalityTitle">Sala de Espera<label id="modality"></label></small>
                    </h2>
                </div>
            </div>

            <div class="row" style="height: 300px;">
                <!--<div class="col-lg-12 col-md-12" >-->
                <div id="modalities" class="panel panel-default" style="height: 100%;">

                        <!--<div class="panel-heading" >Panel Paciente
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
                       
                        </div>-->

                </div>
                <!--</div>-->
            </div>
           
            <div class="row" style="height: 300px;">
                <div class="col-lg-10 col-md-10" style="height: 40%;" >
                    <b>   <div class="alert alert-info" style="height: 100%;">
                          <div id="rss" style="height: 80%;"></div><hr>CNN noticias
                      </div>
                    </b>
                </div>
                <div class="col-lg-2 col-md-2" >
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
                var modalityName=getModality(modalityPage);//Contiene un json con las modalidades y últimos tickets
                $('#modalityName').text(modalityName['modalityName']);

                //$('#modality').text(modalityPage);
                $('#rss').rssfeed('http://www.cnnchile.com/rss/',{}, function(e) {
                    $(e).find('div.rssBody').vTicker({ showItems: 1});
                    $(".rssBody").height('100%');
                });
                $('#weather').weatherfeed(['CIXX0031']);
                showModalities(modalityName);  
            });


            //Devuelve una colección de modalidades junto con su último ticket almacenado
            function getModality(idModality){
                var result = null;
                var scriptUrl = "phpToth/getModality.php?idModality=" + idModality;
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
            /*function getLastTicket(idModality){
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
            }*/

            //Crea cada panel por modalidad dentro del div principal 'modalities' incluyendo su conteo de tickets
            function showModalities(tickets){
                var panel="";
                var modalityWidth = 100 / tickets.length;
                for(i=0;i<tickets.length;i++){

                    var color = " background-color:#D9EDF7;";
                    
                    if(i%2==0) color="";
                    //panel += '<div style="display: inline-block;border: 1px solid black; width: 50%">';
                    panel += '<div style="display: inline-block; width: '+modalityWidth+'%;'+color+'">';
                    panel += '<div class="panel-heading" style="font-size:150%;">'+tickets[i]["modalityName"]+'</div>';
                    panel += '<div class="panel-body" align="center">';
                    panel += '<div class="col-lg-5 col-md-5" >'
                    panel += '<table>';
                    //panel += '<tr style="font-size:30px;"><th id="modalityName'+tickets[i]["modality"]+'"></th></tr>';
                    panel += '<tr style="font-size:150%;"><th>Numero</th><th>Modulo</th></tr>';
                    panel += '<b>';
                    panel += '<tr style="font-size:700%;" id="rows'+tickets[i]["modality"]+'"><td id="number'+tickets[i]["modality"]+'"></td><td id="module'+tickets[i]["modality"]+'" ></td></tr>';
                    panel += '</b>';
                    panel += '</table>';
                    panel += '</div>';
                    panel += '</div>';
                    panel += '</div>';
                }
                $('#modalities').html(panel);

                for(i=0;i<tickets.length;i++){
                  showLastTicket(tickets[i]["modalityTicket"],"A",tickets[i]["modality"]);
                }
            }

            //Asigna el ticket correspondiente a la modalidad enviada
            function showLastTicket(ticket,module,modality){
                if(ticket!=null){
                    if(ticket<10){
                        ticket='00'+ticket;
                    }
                    if(ticket>=10 && ticket<100){
                        ticket='0'+ticket;
                    }
                 
                    $('#rows'+modality).fadeOut('slow', function() {
                        $('#rows'+modality).fadeIn('slow');
                        $('#number'+modality).text(ticket);
                        $('#module'+modality).text(module);
                    }); 
                }else{
                    $('#rows'+modality).fadeOut('slow', function() {
                        $('#rows'+modality).fadeIn('slow');
                        $('#number'+modality).text('S/N');
                        $('#module'+modality).text('');
                    });
                }
            }

      

            function setNumber(message){
                var jMessage = JSON.parse(message);
                if(jMessage['jsonType']=='nextTicket'){
                    var currentNumber=jMessage['initNumber'];
                    var modality=jMessage['modality'];
                    var module=jMessage['currentModuleLetter'];
                   
                    //$('#number').text(currentNumber);
                    $('#modality'+modality).text(modality);
                    //$('#module').text(module);
                    showLastTicket(currentNumber,module,modality);
        
                    // if(currentNumber<10){
                    //   currentNumber='00'+currentNumber;
                    // }
                    // if(currentNumber>=10 && currentNumber<100){
                    //   currentNumber='0'+currentNumber;
                    // }
                }

            }
        </script>

</body>
</html>