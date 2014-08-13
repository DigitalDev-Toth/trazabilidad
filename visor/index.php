<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>FALP - Trazabilidad</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script src="js/libs/jquery-2.1.1.min.js"></script>
        <script src="js/libs/bootstrap.min.js"></script>
        <script src="js/libs/raphael-min.js"></script>
        <script src="js/libs/comet.js"></script>
        <script src="js/tools/module.js"></script>
        <script src="js/tools/submodule.js"></script>
        <script src="js/tools/patient.js"></script>
        <script src="js/tools/maker.js"></script>
        <script type="text/javascript">
            var ZONE = {},
                MODULES = {},
                PATIENTS = {},
                MAKE = null,
                PAPER = null;
            
            $(function () {
                PAPER = Raphael('workspace', '100%', '100%');
                var w = $(window).width(),
                    h = $(window).height();

                PAPER.setViewBox(0, 0, w, h, true);
                PAPER.canvas.setAttribute('preserveAspectRatio', 'xMinYMin');
                
                MAKE = new MAKER();

                message('Conectando al servidor...');
                $.get('../services/zoneInfo.php?zone=1',function (data, status) {
                    message('Servidor conectado!. Esperando los datos...');
                    if (status === 'success') {
                        if (data === 'error') {
                            message('Error al obtener los datos!');
                        } else if (data === 'error_session') {
                            window.location.href = '../admin';
                        } else {                            
                            var info = JSON.parse(data);
                            ZONE['id'] = info.id;
                            ZONE['name'] = info.name;
                            ZONE['seats'] = info.seats;
//                            console.log(info);
                            
                            MAKE.module(info.name, info.id, 'waiting-room', 'center', '#818878', info.shape, null, info.seats);
                            for (var i = 0; i < info.modules.length; i++) {   
                                MAKE.module(info.modules[i].name, info.modules[i].id, 'module', info.modules[i].position, '#'+ info.modules[i].color, info.modules[i].shape, info.modules[i].submodules);
                            }    
                            MAKE.patient('16.025.167-0', 88, 'waiting', 2, 2);
                            MAKE.patient('16.025.167-1', 89, 'waiting', 1, 2);
                            MAKE.patient('16.025.167-2', 90, 'waiting', 1, 2);
                            MAKE.patient('16.025.167-3', 87, 'on_serve', 8, 35);
                            MAKE.patient('16.025.167-4', 86, 'on_serve', 2, 3);
                            MAKE.patient('16.025.167-5', 85, 'on_serve', 4, 6);
                            message('Objetos creados');
                            console.log(MODULES);
                        }
                    }
                });                
            });
            
            message = function (message) {
                $('#message').fadeOut(500, function () {
                    $('#message').html(message);
                    $('#message').fadeIn(1000);
                });
                setTimeout(function () {
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
