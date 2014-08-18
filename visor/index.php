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
            var MODULES = {},
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
//                            console.log(info);
                            
                            MAKE.module(info.name, info.id, 'waiting-room', 'center', '#818878', info.shape, null, info.seats);
                            MAKE.module('Limbo', info.id, 'limb', 'center', '#A24A4A', null, null, null);
                            for (var i = 0; i < info.modules.length; i++) {   
                                MAKE.module(info.modules[i].name, info.modules[i].id, 'module', info.modules[i].position, '#'+ info.modules[i].color, info.modules[i].shape, info.modules[i].submodules);
                            }    
                            MAKE.patient('16.025.167-0', 88, '2014-08-18 12:56:30', 'waiting', 2, 2);
                            MAKE.patient('16.025.167-1', 89, '2014-08-18 12:56:30', 'waiting', 1, 2);
                            MAKE.patient('16.025.167-2', 90, '2014-08-18 12:56:30', 'waiting', 1, 2);
                            MAKE.patient('16.025.167-3', 91, '2014-08-18 12:56:30', 'waiting', 2, 2);
                            MAKE.patient('16.025.167-4', 92, '2014-08-18 12:56:30', 'waiting', 1, 2);
                            MAKE.patient('16.025.167-5', 93, '2014-08-18 12:56:30', 'waiting', 2, 2);
                            MAKE.patient('16.025.167-6', 94, '2014-08-18 12:56:30', 'waiting', 1, 2);
                            MAKE.patient('16.025.167-7', 95, '2014-08-18 12:56:30', 'waiting', 2, 2);
                            MAKE.patient('16.025.167-8', 96, '2014-08-18 12:56:30', 'waiting', 1, 2);
                            MAKE.patient('16.025.167-9', 97, '2014-08-18 12:56:30', 'waiting', 2, 2);
                            MAKE.patient('16.025.167-10', 98, '2014-08-18 12:56:30', 'waiting', 1, 2);
                            MAKE.patient('16.025.167-11', 99, '2014-08-18 12:56:30', 'waiting', 2, 2);
                            MAKE.patient('16.025.167-12', 60, '2014-08-18 12:56:30', 'waiting', 1, 2);
                            MAKE.patient('16.025.167-13', 61, '2014-08-18 12:56:30', 'waiting', 2, 2);
                            MAKE.patient('16.025.167-14', 61, '2014-08-18 12:56:30', 'waiting', 2, 2);
                            MAKE.patient('16.025.167-15', 61, '2014-08-18 12:56:30', 'waiting', 2, 2);
                            MAKE.patient('16.025.167-16', 61, '2014-08-18 12:56:30', 'waiting', 2, 2);
                            MAKE.patient('16.025.167-17', 61, '2014-08-18 12:56:30', 'waiting', 2, 2);
                            MAKE.patient('16.025.167-18', 61, '2014-08-18 12:56:30', 'waiting', 2, 2);

                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-2', 90, '2014-08-18 12:56:30', 'limb', 1, 2);
                            MAKE.patient('16.025.168-3', 87, '2014-08-18 12:56:30', 'on_serve', 8, 35);
                            MAKE.patient('16.025.168-4', 86, '2014-08-18 12:56:30', 'on_serve', 2, 3);
                            MAKE.patient('16.025.168-5', 85, '2014-08-18 12:56:30', 'on_serve', 4, 6);

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
