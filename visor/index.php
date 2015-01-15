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
        <script src="http://falp.biopacs.com:8000/socket.io/socket.io.js"></script>
        <script src="js/libs/jquery.tothtip.js"></script>
        <script src="js/libs/bootstrap.min.js"></script>
        <script src="js/libs/raphael-min.js"></script>
        <script src="js/libs/raphaeljs-infobox.js"></script>
        <script src="js/libs/comet.js"></script>
        <script src="js/libs/jquery.dataTables.min.js"></script>
        <script src="js/libs/dataTables.tableTools.js"></script>
        <script src="js/libs/dataTables.bootstrap.js"></script>
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
                ZONE = '<?php echo $zone ?>'; 
            var socket = io.connect('http://falp.biopacs.com:8000');
            $(function () {                 
                socketComet();

                PAPER = Raphael('workspace', '100%', '100%');
                var w = $(window).width(),
                    h = $(window).height();

                PAPER.setViewBox(0, 0, w, h, true);
                PAPER.canvas.setAttribute('preserveAspectRatio', 'xMinYMin');
                
                MAKE = new MAKER();

                message('Conectando al servidor...');
                $.get('../services/zoneInfo.php?zone='+ ZONE, function (data, status) {
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
                            MAKE.module('Salida', info.id, 'limb', null, 'center', '#A24A4A', null, null, null, null);
                            for (var i = 0; i < info.modules.length; i++) {   
                                MAKE.module(info.modules[i].name, info.modules[i].id, 'module', info.modules[i].type, info.modules[i].position, '#'+ info.modules[i].color, info.modules[i].shape, info.modules[i].max_wait, info.modules[i].submodules);
                            }  
                            MAKE.wrInfo();
                            $.get('../services/info_Module.php?zone='+ ZONE, function (data, status) {
                                var dm = $.parseJSON(data);
                                for (var i = 0; i < dm.length; i++) {
                                    if (parseInt(dm[i].dbtype) === 1) {
                                        MAKE.tothtemInfo(dm[i].idModule, dm[i].total_tickets, dm[i].modules, dm[i].first_ticket, dm[i].last_ticket);
                                    } else {
                                        if (MODULES[dm[i].idModule].totalSubmodulesInactive < MODULES[dm[i].idModule].totalSubmodules) {
                                            MAKE.moduleInfo(dm[i].idModule, dm[i].served_tickets, dm[i].average, dm[i].maxtime, dm[i].mintime);
                                        }                                        
                                    }
                                }
//                                console.log(dm);
                            });
                            $.get('../services/info_Submodule.php?zone='+ ZONE, function (data, status) {
                                var dsm = $.parseJSON(data);
                                for (var i = 0; i < dsm.length; i++) {
                                    if (dsm[i].session !== null && MODULES[dsm[i].module].submodules[dsm[i].submodule].state !== 'inactivo') {
                                        MAKE.submoduleInfo(dsm[i].module, dsm[i].submodule, dsm[i].user, dsm[i].session, dsm[i].patients, dsm[i].average, dsm[i].maxtime, dsm[i].mintime);
                                    }                                    
                                }
//                                console.log(dsm);
                            });
                            $.get('../services/getPatients.php?zone='+ ZONE, function (data, status) {
//                                console.log(data);
                                var jsonData = JSON.parse(data);
                                for(i=0; i<jsonData.length;i++){
                                    MAKE.patient(jsonData[i].rut, jsonData[i].name, jsonData[i].ticket, jsonData[i].datetime, jsonData[i].attention, jsonData[i].module, jsonData[i].sub_module);
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
        <div id="bitacora" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-viewer">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Bitácora</h4>
                    </div>
                    <div class="modal-body">
                        <div id="bitacoraContent" class="row"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
