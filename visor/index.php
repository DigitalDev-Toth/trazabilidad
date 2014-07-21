<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>FALP - Trazabilidad</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/raphael-min.js"></script>
        <script src="js/module.js"></script>
        <script src="js/submodule.js"></script>
        <script src="js/maker.js"></script>
        <script type="text/javascript">
            var MODULES = {},
                PATIENTS = [],
                PAPER;
            
            $(function () {

                PAPER = Raphael('workspace', '100%', '100%');
                var w = $(window).width();
                var h = $(window).height();

                PAPER.setViewBox(0, 0, w, h, true);
                PAPER.canvas.setAttribute("preserveAspectRatio", "xMinYMin");
                
                var make = new MAKER();
                message('Conectando al servidor...');
                $.get("../services/zoneInfo.php?zone=1",function(data,status){
                    message('Servidor conectado!. Esperando los datos...');
                    if(status=='success') {
                        if(data==='error') {
                            message('Error al obtener los datos!');
                        } else {
                            var zone = {};
                            info = JSON.parse(data);
                            $.each(info, function(index, mod){
                                if($.isPlainObject(mod)) {
                                    make.module(mod.name, mod.id, 'module', mod.position, '#'+mod.color, mod.submodules);
                                } else {
                                    zone[index] = mod;
                                }
                            });
                            make.module(zone.name, zone.id, 'waiting-room', 'center', '#818878', null, zone.seats);
                            message('Objetos creados');
                        }
                    }
                });
            });
            message = function(message) {
                $('#message').fadeOut(500, function() {
                    $('#message').html(message);
                    $('#message').fadeIn(1000);
                });
                setTimeout(function() {
                      $('#message').fadeOut(500);
                }, 5000);
            };
        </script>
    </head>
    <body>
        <div id="workspace"></div>
        <div id="message">Mensaje de estados...</div>
    </body>
</html>
