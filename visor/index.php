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
        <script src="js/tools/module.js"></script>
        <script src="js/tools/submodule.js"></script>
        <script src="js/tools/patient.js"></script>
        <script src="js/tools/maker.js"></script>
        <script type="text/javascript">
            var ZONE = {},
                MODULES = {},
                PATIENTS = {},
                PAPER = null;
            
            $(function () {
                PAPER = Raphael('workspace', '100%', '100%');
                var w = $(window).width();
                var h = $(window).height();

                PAPER.setViewBox(0, 0, w, h, true);
                PAPER.canvas.setAttribute("preserveAspectRatio", "xMinYMin");
                
                var make = new MAKER();

                message('Conectando al servidor...');
                $.get("../services/zoneInfo.php?zone=1",function (data, status) {
                    message('Servidor conectado!. Esperando los datos...');
                    if (status === 'success') {
                        if (data==='error') {
                            message('Error al obtener los datos!');
                        } else {
                            var info = JSON.parse(data);
                            ZONE['id'] = info.id;
                            ZONE['name'] = info.name;
                            ZONE['seats'] = info.seats;
                            console.log(info);
                            
                            for (var i = 0; i < info.modules.length; i++) {   
                                make.module(info.modules[i].name, info.modules[i].id, 'module', info.modules[i].position, '#'+ info.modules[i].color, info.modules[i].submodules);
                            }
                            var wr = make.module(info.name, info.id, 'waiting-room', 'center', '#818878', null, info.seats);
                            console.log(wr.nextPos);
                            message('Objetos creados');
                            console.log(MODULES);
                            make.patient(1);
                            make.patient(2);
                            make.patient(3);
                            make.patient(4);
                            make.patient(5);
                            make.patient(6);
                            make.patient(7);
                            make.patient(8);
                            setTimeout(function () {
                                make.goTo(1, null, null);
                            }, 2000);
                            setTimeout(function () {
                                make.goTo(2, null, null);
                            }, 4000);
                            setTimeout(function () {
                                make.goTo(3, null, null);
                                make.goTo(1, 3, 7);
                            }, 6000);
                            setTimeout(function () {
                                make.goTo(4, null, null);
                            }, 8000);
                            setTimeout(function () {
                                make.goTo(5, null, null);
                            }, 10000);
                            setTimeout(function () {
                                make.goTo(6, null, null);
                                make.goTo(1, null, null);
                            }, 12000);
                            setTimeout(function () {
                                make.goTo(7, null, null);
                            }, 14000);
                            setTimeout(function () {
                                make.goTo(8, null, null);
                                make.goTo(6, 1, 1);
                            }, 16000);
//                            console.log(PATIENTS);
                        }
                    }
                });                
            });
            
            message = function (message) {
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
