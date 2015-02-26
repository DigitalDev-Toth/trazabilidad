function socketComet(){
    socket.on('connect', function() {
        socket.on('message', function(message) {
//            console.log(message);
            var data = $.parseJSON(message);
            if ((data.comet === 'tothtem' || data.comet === 'module') && SUBMODULES[data.submodule] !== undefined) {
                MAKE.goTo(data.comet, data.rut, data.name, data.action, data.newticket, data.datetime, data.module, data.submodule);
            } else if (data.comet === 'submodule') {
                if (SUBMODULES[data.id] !== undefined) {
                    if (data.state === 'activo') {
                        $.get('../services/info_Submodule.php?zone='+ ZONE, function (d, status) {
                            var dsm = $.parseJSON(d);
                            for (var i = 0; i < dsm.length; i++) {
                                if (data.id === dsm[i].submodule) {
                                    MAKE.submoduleInfo(dsm[i].module, dsm[i].submodule, dsm[i].user, dsm[i].session, dsm[i].patients, dsm[i].average, dsm[i].maxtime, dsm[i].mintime);
                                    MODULES[SUBMODULES[data.id]].submodules[data.id].setActive();
                                }
                            }
                        });
                    } else if (data.state === 'inactivo') {
                        MODULES[SUBMODULES[data.id]].submodules[data.id].setInactive();
                    } else if (data.state === 'blink') {
                        MODULES[SUBMODULES[data.id]].submodules[data.id].blink(0);
                    } else if (data.state === 'pausado') {
                        MODULES[SUBMODULES[data.id]].submodules[data.id].setPause();
                    }
                }
            } 

        });

    });
}