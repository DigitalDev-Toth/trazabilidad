<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title></title>
    <link href="../js/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../js/bootstrap/css/simple-sidebar.css" rel="stylesheet">
    <script type="text/javascript" src="../js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/comet.js"></script>

</head>
<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav" id="sideBarZones">
                          
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
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
    
    $.post('../services/getSelectors.php', {type: 'zone', user: "<?php echo $_SESSION['UserId']; ?>"}, function(data, textStatus, xhr) {
        var JsonData= JSON.parse(data);
        for (var i = 0; i < JsonData.length; i++) {
            $("#sideBarZones").append("<li><a href='#' onClick='showModule("+ JsonData[i].id +")' >"+ JsonData[i].name +"</a></li>");   
        };  
    });


    function showModule(id){
        $.ajax({type :"post",url : "../services/getSelectors.php",data : 'type=module&user=<?php echo $_SESSION["UserId"]; ?>&zone='+id,
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
                    html+= 'Sin tickets';
                    html+= '</div></div></div></div>';
                    //showTickets(JsonData[i].id);<div class="panel panel-default">
                };
                $("#accordionPanel").html(html);
                $( document ).ready(function() {
                    /*$('.accordion-toggle').click(function (e){
                        var module = e.currentTarget.parentNode.parentNode.id.split('d');
                        showTickets(module[1]);
                        //Cambiar ícono al desplegar
                        var chevState = $(e.target).siblings("i.indicator").toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
                        console.log(chevState);
                        $("i.indicator").not(chevState).removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
                    });*/

                    $(".accordion-toggle").click(function(e) {
                        var module = e.currentTarget.parentNode.parentNode.id.split('d');
                        //var module = e.currentTarget.id.split('d');
                        showTickets(module[1]);

                        //Cambiar ícono al desplegar
                        if($("#h"+module[1]).hasClass('glyphicon-chevron-down')){
                            $("#h"+module[1]).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
                        }else{
                            $("#h"+module[1]).removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
                        }
                    });

                });
            }
        });
    }

    function showTickets(module){
        $.post('../services/lastTickets.php', {module: module}, function(data, textStatus, xhr) {
            if(data!=0){
                var JsonData= JSON.parse(data);
                var html="<table class='table table-striped'>";
                html+="<tr><th>Ticket</th><th>Rut</th><th>Fecha</th><th>Tiempo de Espera</th></tr>";

                var html2="<table class='table table-striped'>";
                html2+="<tr><th>Submódulo</th><th>Ticket</th><th>Tiempo</th><th>Tiempo de Atención</th></tr>";
                for (var i = 0; i < JsonData.length; i++) {
                    if(JsonData[i].typeJson=='ticket'){
                        html+="<tr><td>"+JsonData[i].ticket+"</td><td>"+JsonData[i].rut+"</td><td>"+JsonData[i].datetime+"</td><td>"+JsonData[i].waitingTime+"</td></tr>";
                    }else{
                        html2+="<tr><td>"+JsonData[i].name+"</td><td>"+JsonData[i].ticket+"</td><td>"+JsonData[i].datetime+"</td><td>"+JsonData[i].waitingTime+"</td></tr>";
                    };
                };
                html +="</table>";
                html2 +="</table>";
                $("#t"+module).html(html+'<br/>'+html2);   
            }
            
        });

    }
    
</script>

</html>