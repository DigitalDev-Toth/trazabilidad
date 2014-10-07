
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pantalla</title>
  	<script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/comet.js"></script>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/2-col-portfolio.css" rel="stylesheet">
  
</head>
<body>
    <div class="container">
        <div class="row" id="contentMain">
            
        </div>>
        <div class="row">
        <br>
               
                    <button type="button" class="btn btn-default btn-lg" onclick="sendComet('minus')" id="minusButton" title="Volver a llamar número anterior"><span class="glyphicon glyphicon-minus"></span></button>
                    <button type="button" class="btn btn-default btn-lg" onclick="sendComet('plus')" id="plusButton" title="Llamar paciente"><span class="glyphicon glyphicon-plus"></span></button>
                    <button type="button" class="btn btn-default btn-lg" onclick="sendComet('notHere')" id="notHereButton" title="No llegó paciente" style="color: red;"><span class="glyphicon glyphicon-remove-circle"></span></button>
                    <button type="button" class="btn btn-default btn-lg" onclick="sendComet('finished')" id="finishedButton" title="Finalizar Atención" ><span class="glyphicon glyphicon-thumbs-up"></span></button>
                    <button type="button" class="btn btn-default btn-lg" onclick="sendComet('redirect')" id="redirectButton" title="Derivar"><span class="glyphicon glyphicon-share"></span></button>
          

        </div>
    </div> 





<script type="text/javascript">

function test() {
    $("#contentMain").append('text');
}

</script>
</body>
</html>