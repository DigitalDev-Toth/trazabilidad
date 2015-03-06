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
        <script src="js/libs/jquery.tothcontextmenu.js"></script>
        <script src="js/libs/bootstrap.min.js"></script>
        <script src="js/libs/raphael-min.js"></script>
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
                BACKUP_MODULES = {},
                SUBMODULES = {},
                BACKUP_SUBMODULES = {},
                PATIENTS = {},
                BACKUP_PATIENTS = {},
                MAKE = null,
                PAPER = null;
                ZONE = '<?php echo $zone ?>',
                MENU = {},
                MODE = 1000000; 
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
                    message('Servidor conectado. Esperando los datos...');
                    if (status === 'success') {
                        if (data === 'error') {
                            message('Error al obtener los datos!');
                        } else if (data === 'error_session') {
                            window.location.href = '../admin';
                        } else {                            
                            var info = JSON.parse(data);
                            
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
                            });
                            $.get('../services/info_Submodule.php?zone='+ ZONE, function (data, status) {
                                var dsm = $.parseJSON(data);
                                for (var i = 0; i < dsm.length; i++) {
                                    if (dsm[i].session !== null && MODULES[dsm[i].module].submodules[dsm[i].submodule].state !== 'inactivo') {
                                        MAKE.submoduleInfo(dsm[i].module, dsm[i].submodule, dsm[i].user, dsm[i].session, dsm[i].patients, dsm[i].average, dsm[i].maxtime, dsm[i].mintime);
                                    }                                    
                                }
                            });
                            $.get('../services/getPatients.php?zone='+ ZONE, function (data, status) {
                                var jsonData = JSON.parse(data);
                                for (i = 0; i < jsonData.length; i++) {
                                    MAKE.patient(jsonData[i].rut, jsonData[i].name, jsonData[i].ticket, jsonData[i].datetime, jsonData[i].attention, jsonData[i].module, jsonData[i].sub_module);
                                }
                                console.log(PATIENTS);
                            });
//                            MAKE.patient('16.025.167-0', 'Sebastián Rodríguez', '15C', null, 'limb', 36, 53);
                            
                            message('Objetos creados');
                            console.log(MODULES);                            
                            for (var i in MODULES) {
                                if (MODULES[i].type === 'module' && MODULES[i].dbType > 1) {
                                    var object = {name: MODULES[i].name, id: MODULES[i].id};
                                    MENU[i] = object;
                                }                    
                            }
                            var object = {name: 'Todos', id: '1000000'};
                            MENU[1000000] = object;
                        }
                    }
                });                    
                $(window).tothcontextmenu(MENU);
                $(window).on('contextmenu', function (event) {
                    event.preventDefault();
                    return false;
                });

                // da fuck this shit?
                $(window).keypress(function (event) {
                    if (event.which === 49 && MODE === 1000000) {
                        MAKE.goTo('tothtem', '16.025.167-0', 'Perrowwww', 'in', null, null, 33, 47);                        
                    } else if (event.which === 50) {
                        MAKE.goTo('module', '16.025.167-0', 'Perrowwww', 'to', '15C', null, 34, null);
                    }
                });
            });
            function repaintViewport () {
                PAPER.clear();              
                if (MODE === 1000000) {
                    stopVariables ();
                    MODULES = {};
                    SUBMODULES = {};
                    PATIENTS = {};
                    MAKE = new MAKER();
                    $.get('../services/zoneInfo.php?zone='+ ZONE, function (data, status) {                           
                        var info = JSON.parse(data);

                        MAKE.module(info.name, info.id, 'waiting-room', null, 'center', '#818878', null, null, null, info.seats);
                        MAKE.module('Salida', info.id, 'limb', null, 'center', '#A24A4A', null, null, null, null);
                        for (var i = 0; i < info.modules.length; i++) {   
                            MAKE.module(info.modules[i].name, info.modules[i].id, 'module', info.modules[i].type, info.modules[i].position, '#'+ info.modules[i].color, info.modules[i].shape, info.modules[i].max_wait, info.modules[i].submodules);
                        }  
                        MAKE.wrInfo();
                        $.get('../services/getPatients.php?zone='+ ZONE, function (data, status) {
                            var jsonData = JSON.parse(data);
                            for (i = 0; i < jsonData.length; i++) {
                                MAKE.patient(jsonData[i].rut, jsonData[i].name, jsonData[i].ticket, jsonData[i].datetime, jsonData[i].attention, jsonData[i].module, jsonData[i].sub_module);
                            }                            
                        });
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
                        });
                        $.get('../services/info_Submodule.php?zone='+ ZONE, function (data, status) {
                            var dsm = $.parseJSON(data);
                            for (var i = 0; i < dsm.length; i++) {
                                if (dsm[i].session !== null && MODULES[dsm[i].module].submodules[dsm[i].submodule].state !== 'inactivo') {
                                    MAKE.submoduleInfo(dsm[i].module, dsm[i].submodule, dsm[i].user, dsm[i].session, dsm[i].patients, dsm[i].average, dsm[i].maxtime, dsm[i].mintime);
                                }                                    
                            }
                        });
                    });                    
                } else {                    
                    stopVariables ();
                    MODULES = {};
                    SUBMODULES = {};
                    PATIENTS = {};
                    MAKE = new MAKER();
                    $.get('../services/zoneInfo.php?zone='+ ZONE, function (data, status) {                           
                        var info = JSON.parse(data);

                        MAKE.module(info.name, info.id, 'waiting-room', null, 'center', '#818878', null, null, null, info.seats);
                        MAKE.module('Salida', info.id, 'limb', null, 'center', '#A24A4A', null, null, null, null);
                        for (var i = 0; i < info.modules.length; i++) {
                            if (MODE === parseInt(info.modules[i].id)) {
                                MAKE.module(info.modules[i].name, info.modules[i].id, 'module', info.modules[i].type, 'superior', '#'+ info.modules[i].color, info.modules[i].shape, info.modules[i].max_wait, info.modules[i].submodules);
                            }                               
                        }
                        MAKE.wrInfo();
                        $.get('../services/getPatients.php?zone='+ ZONE, function (data, status) {
                            var jsonData = JSON.parse(data);
                            for (i = 0; i < jsonData.length; i++) {
                                if (MODE === parseInt(jsonData[i].module)) {
                                    MAKE.patient(jsonData[i].rut, jsonData[i].name, jsonData[i].ticket, jsonData[i].datetime, jsonData[i].attention, jsonData[i].module, jsonData[i].sub_module);
                                }                                
                            }                            
                        });
                        $.get('../services/info_Module.php?zone='+ ZONE, function (data, status) {
                            var dm = $.parseJSON(data);
                            for (var i = 0; i < dm.length; i++) {
                                if (MODE === parseInt(dm[i].idModule)) {
                                    MAKE.moduleInfo(dm[i].idModule, dm[i].served_tickets, dm[i].average, dm[i].maxtime, dm[i].mintime);                                        
                                }
                            }
                        });
                        $.get('../services/info_Submodule.php?zone='+ ZONE, function (data, status) {
                            var dsm = $.parseJSON(data);
                            for (var i = 0; i < dsm.length; i++) {
                                if (MODE === parseInt(dsm[i].module)) {                                    
                                    if (dsm[i].session !== null && MODULES[dsm[i].module].submodules[dsm[i].submodule].state !== 'inactivo') {                                    
                                        MAKE.submoduleInfo(dsm[i].module, dsm[i].submodule, dsm[i].user, dsm[i].session, dsm[i].patients, dsm[i].average, dsm[i].maxtime, dsm[i].mintime);                                                                       
                                    }  
                                }
                            }
                        });
                    }); 
                }                
            }
            function stopVariables () {  
                MAKE = null;                
                for (var i in MODULES) {
                    clearInterval(MODULES[i].interval);
                    clearInterval(MODULES[i].ivTothtemInfo);
                    clearInterval(MODULES[i].ivInfo);                    
                    clearInterval(MODULES[i].ivwrInfo);
                    if (MODULES[i].type === 'module' && MODULES[i].dbType > 1) {
                        for (var j in MODULES[i]) {
                            delete MODULES[i].submodules[j];
//                            clearInterval(MODULES[i].submodules[j].interval);
//                            clearInterval(MODULES[i].submodules[j].ivPauseTime);
                        }
                    }
                }
                for (var i in PATIENTS) {
                    clearInterval(PATIENTS[i].interval);
                }               
            }
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

        <div id="bitacoraPatient" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-viewer">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Bitácora paciente</h4>
                    </div>
                    <div class="modal-body">
                        <div id="bitacoraPatientContent" class="row well well-sm"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="bitacoraSubmodule" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-viewer">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Bitácora funcionario</h4>
                    </div>
                    <div class="modal-body">
                        <div id="bitacoraSubmoduleContent" class="row"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
