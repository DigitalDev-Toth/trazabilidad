<?php
if (isset($_GET['idZone'])) {
    $zone = $_GET['idZone'];
} else {
    echo 'Falta Id Zona!';
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>FALP - Trazabilidad</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script src="js/libs/jquery-2.1.1.min.js"></script>
        <script src="js/libs/jquery.tothtip.js"></script>
        <script src="js/libs/bootstrap.min.js"></script>
        <script src="js/libs/raphael-min.js"></script>
        <script src="js/libs/raphaeljs-infobox.js"></script>
        <script src="js/libs/comet.js"></script>
        <script src="js/tools/module.js"></script>
        <script src="js/tools/submodule.js"></script>
        <script src="js/tools/patient.js"></script>
        <script src="js/tools/maker.js"></script>
        <script type="text/javascript">
            var MODULES = {},
                SUBMODULES = {},
                PATIENTS = {},
                MAKE = null,
                PAPER = null;
            
            $(function () {
                var idZone= '<?php echo $zone ?>';
//                console.log(idZone);

                PAPER = Raphael('workspace', '100%', '100%');
                var w = $(window).width(),
                    h = $(window).height();

                PAPER.setViewBox(0, 0, w, h, true);
                PAPER.canvas.setAttribute('preserveAspectRatio', 'xMinYMin');
                
                MAKE = new MAKER();

                message('Conectando al servidor...');
                $.get('../services/zoneInfo.php?zone='+ idZone, function (data, status) {
                    message('Servidor conectado!. Esperando los datos...');
                    if (status === 'success') {
                        if (data === 'error') {
                            message('Error al obtener los datos!');
                        } else if (data === 'error_session') {
                            window.location.href = '../admin';
                        } else {                            
                            var info = JSON.parse(data);
//                            console.log(info);
                            
                            MAKE.module(info.name, info.id, 'waiting-room', null, 'center', '#818878', null, null, null, info.seats);
                            MAKE.module('Limbo', info.id, 'limb', null, 'center', '#A24A4A', null, null, null, null);
                            for (var i = 0; i < info.modules.length; i++) {   
                                MAKE.module(info.modules[i].name, info.modules[i].id, 'module', info.modules[i].type, info.modules[i].position, '#'+ info.modules[i].color, info.modules[i].shape, info.modules[i].max_wait, info.modules[i].submodules);
                            }  
                            MAKE.wrInfo();
                            MAKE.tothtemInfo(33, 25, {34: 12, 35: 13}, '2014-10-06 08:30:00', '2014-10-06 19:28:10');
                            MAKE.moduleInfo(34, 77, '2014-10-06 00:15:00', '2014-10-06 00:45:00', '2014-10-06 00:05:00');
                            $.get('../services/info_Submodule.php?zone='+ idZone, function (data, status) {
                                var dsm = $.parseJSON(data);
                                for (var i = 0; i < dsm.length; i++) {
                                    MAKE.submoduleInfo(dsm[i].module, dsm[i].submodule, dsm[i].user, dsm[i].session, dsm[i].patients, dsm[i].average, dsm[i].maxtime, dsm[i].mintime);
                                }
                            });
//                            MAKE.submoduleInfo(34, 49, 'Juanita Melo', '2014-10-06 17:30:10', 2, '2014-10-06 00:02:23', '2014-10-06 00:10:02', '2014-10-06 00:05:01');
                            $.get('../services/getPatients.php?zone='+ idZone, function (data, status) {
//                                console.log(data);
                                var jsonData = JSON.parse(data);
                                for(i=0; i<jsonData.length;i++){
                                    MAKE.patient(jsonData[i].rut, 'Juan Perez', jsonData[i].ticket, jsonData[i].datetime, jsonData[i].attention, jsonData[i].module, jsonData[i].sub_module);
                                }
                            });
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
