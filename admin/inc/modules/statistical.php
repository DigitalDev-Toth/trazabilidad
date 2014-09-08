<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title></title>
    <link href="../inc/js/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../inc/js/bootstrap/css/simple-sidebar.css" rel="stylesheet">
    <script type="text/javascript" src="../inc/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="../inc/js/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../inc/js/comet.js"></script>

    <!-- Descargar librerias para la version final!-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="http://cdn.oesmith.co.uk/morris-0.5.1.min.js"></script>
    <!-- /fin -->

</head>
<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav" id="sideBarZones">
                <li><a href="#" class="text-center">Zonas:</a></li>

            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                      <div class="panel panel-default">
                          <div class="panel-heading">Horas Peak</div>
                          <div class="panel-body">
                                <div id="pickHours">
                            
                        </div>
                          </div>
                        </div>
                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="showCurrentModules">

                            <div class="panel-group" id="accordionPanel">
                                
                            </div>  

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>

  

</body>

<script type="text/javascript">
    
    $.post('../inc/services/getSelectors.php', {type: 'zone', user: "<?php echo $_SESSION['UserId']; ?>"}, function(data, textStatus, xhr) {
        var JsonData= JSON.parse(data);
        for (var i = 0; i < JsonData.length; i++) {
            $("#sideBarZones").append("<li><a href='#' onClick='showModule("+ JsonData[i].id +")' >"+ JsonData[i].name +"</a></li>");   
        };  
    });


    function showModule(id){
        hourPicks();


        $.ajax({type :"post",url : "../inc/services/getSelectors.php",data : 'type=module&user=<?php echo $_SESSION["UserId"]; ?>&zone='+id,
            /*beforeSend:function(){
                document.getElementById('loader_image').src="img/loading.png";
                document.getElementById('loader').style.display="inline";
            },*/
            success:function(newJson){
                var JsonData= JSON.parse(newJson);
                var html="";
                for (var i = 0; i < JsonData.length; i++) {

                    html+= '<div class="panel panel-default"><div id="d'+JsonData[i].id+'" class="panel-heading"><h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse'+JsonData[i].id+'">';
                    html+= JsonData[i].name+'</a><i id="h'+JsonData[i].id+'" class="indicator glyphicon glyphicon-chevron-down pull-right"></i></h4></div>';
                    html+= '<div id="collapse'+JsonData[i].id+'" class="panel-collapse collapse">';
                    html+= '<div class="panel-body" id="t'+JsonData[i].id+'">';
               
                    html+= '</div></div></div></div>';
                    //showTickets(JsonData[i].id);<div class="panel panel-default">
                };
                $("#accordionPanel").html(html);
                $( document ).ready(function() {
                    $(".accordion-toggle").click(function(e) {
                        var module = e.currentTarget.parentNode.parentNode.id.split('d');
                      
                        if($("#h"+module[1]).hasClass('glyphicon-chevron-down')){
                            $("#h"+module[1]).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
                        }else{
                            $("#h"+module[1]).removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
                        }
                        setTimeout(function(){
                            showStatistical(module[1])

                        }, 100);
                          
                    });

                });
            }
        });
    }
    function showStatistical(module){
       //$.post('../services/lastTickets.php', {module: module}, function(data, textStatus, xhr) {
           // if(data!=0){
                var html= '<div class="row"><div class="col-md-6" id="table'+module+'" >  </div>  <div class="col-md-6"> <div id="morris'+module+'" style="height: 250px;"></div>    </div></div>';
                $("#t"+module).html(html+'<br/>');   
                 createTable("table"+module,module);
           // }
            
        //});

    }

    function createChart(idMorris,numbers){
            var cien=numbers[3]+numbers[4]+numbers[5];
            $("#"+idMorris).empty();
            Morris.Donut({
            element: idMorris,
            data: [
                {value: parseFloat(numbers[3]*100/cien).toFixed(2), label: 'Pacientes Atendidos'},
                {value: parseFloat(numbers[4]*100/cien).toFixed(2), label: 'Pacientes No Atendidos'},
                {value: parseFloat(numbers[5]*100/cien).toFixed(2), label: 'Pacientes Derivados'}
            ],
            backgroundColor: '#ccc',
             labelColor: '#000',

             colors: [
              '#429FCA',
              '#296496',
              '#717171'
              ],
              formatter: function (x,data) { 
                $("#row3").removeClass('info');
                $("#row4").removeClass('info');
                $("#row5").removeClass('info');
                if(data.label=='Pacientes Atendidos'){
                    $("#row3").addClass('info');
                }
                if(data.label=='Pacientes No Atendidos'){
                    $("#row4").addClass('info');
                }
                if(data.label=='Pacientes Derivados'){
                    $("#row5").addClass('info');
                }
                return x + "%";}
            });
    }
    function createTable(idTable,module){
        var texts=['Promedio Tiempos De Espera','Promedio Tiempos De Atencion','Promedio Tiempo Inactivo','Cantidad de pacientes atendidos','Cantidad de pacientes no atendidos','Cantidad de pacientes derivados','Pacientes en espera'];
        var html='<table class="table table-bordered table-striped">';
        var results=[];
        for (var i = 0; i < texts.length ; i++) {
            if(i<3){
                var hour=(Math.floor(Math.random() * 19) + 10)+":"+(Math.floor(Math.random() * 19) + 10);
                html+='<tr>  <th>'+texts[i] +'</th><td>'+hour+'</td> </tr>'
            }else{
                var number=(Math.floor(Math.random() * 100) + 5);
                html+='<tr id="row'+i+'">  <th>'+texts[i] +'</th><td>'+number+'</td> </tr>'
                results[i]=number;
            }

                
        };
        html+='</table>';
        $("#"+idTable).html(html+'<br/>');  
         createChart('morris'+module,results);



    }
    function rn(op){
        if(op==0){
            return (Math.floor(Math.random() * 60) + 30);
        }else{
            return (Math.floor(Math.random() * 55) + 1);
        }
        
    }

    function hourPicks(){
         $("#pickHours").empty();
      Morris.Area({

    element: 'pickHours',

    data: [            
   
        { hours: '08:00', a: rn(0) , b: rn(1) },
        { hours: '09:00', a: rn(0) , b: rn(1) },
        { hours: '10:00', a: rn(0) , b: rn(1) },
        { hours: '11:00', a: rn(0) , b: rn(1) },
        { hours: '12:00', a: rn(0) , b: rn(1) },
        { hours: '13:00', a: rn(0) , b: rn(1) },
        { hours: '14:00', a: rn(0) , b: rn(1) },
        { hours: '15:00', a: rn(0) , b: rn(1) },
        { hours: '16:00', a: rn(0) , b: rn(1) },
        { hours: '17:00', a: rn(0) , b: rn(1) },
        { hours: '18:00', a: rn(0) , b: rn(1) },
        { hours: '19:00', a: rn(0) , b: rn(1) },
 
    ],
    parseTime: false,

    xkey: 'hours',
    ykeys: ['b', 'a'],
      labels: ['No atendidos', 'Atendidos']

}); 
         
    }
/*
modulos
    -promedio tiempos de espera
    -promedio tiempos de atencion
    -promedio tiempo inactivo
    -cantidad de pacientes por hora
        -atendidos
        -no atendidos


subModulos
    -inicio y cierre de sesion
    -cantidad atendida

*/
    
</script>

</html>