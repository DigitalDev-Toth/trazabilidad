var PATIENT = function (id, name, ticket, datetime, idModule, storage) {
    this.id = id; // rut
    this.name = name;
    this.shape = 'circulo';
    this.idModule = idModule;
    this.lastIdModule = null;
    this.lastIdSubmodule = null;
    this.nextIdModule = null;
    this.seat = null; // seat for waiting room
    this.place = null; // place for limb
    this.ticket = ticket;
    this.datetime = new Date(datetime).getTime();
    this.timeOn = null;
    this.initTimeOn = null;
    this.endTimeOn = null;
    this.wt = null;
    this.interval = null;
    this.el = null; // element DOM for patient
    this.text = null; // text DOM for patient
    this.elTop = null;
    this.elInfo = $('<div></div>');
    if (!storage) {
        this.setElem(idModule);
        var a = this.id.replace(/\./g, ''),
            b = a.replace('-', '');
        this.elTop.node.id = 'p'+ b;
    }    

};
PATIENT.prototype.blink = function (i) {
    if (i < 7) {
        i++;
        var stroke = this.el.attrs.stroke;
        this.el.animate({stroke: '#fff'}, 100, 'linear', (function (t, i) {
            return function() {
                t.el.animate({stroke: stroke}, 100, t.blink(i));
            };
        })(this, i));
    }

};
PATIENT.prototype.setElem = function (idModule) {
    switch (MODULES[idModule].pos) {
        case 'superior':
            var x = $(window).width() / 2,
                y = $(window).height() / 4;
            break;
        case 'izquierda':
            var x = $(window).width() / 4,
                y = $(window).height() / 2;
            break;
        case 'inferior':
            var x = $(window).width() / 2,
                y = $(window).height() - ($(window).height() / 4);
            break;
        case 'derecha':
            var x = $(window).width() - ($(window).width() / 4),
                y = $(window).height() / 2;
            break;
        case 'superior-izquierda':
            var x = $(window).width() / 4,
                y = $(window).height() / 4;
            break;
        case 'superior-derecha':
            var x = $(window).width() - ($(window).width() / 4),
                y = $(window).height() / 4;
            break;
        case 'inferior-izquierda':
            var x = $(window).width() / 4,
                y = $(window).height() - ($(window).height() / 4);
            break;
        case 'inferior-derecha':
            var x = $(window).width() - ($(window).width() / 4),
                y = $(window).height() - ($(window).height() / 4);
            break;
    }
    var p = 'M'+ x +','+ y +'m-15,0a15,15 0 1,0 30,0a15,15 0 1,0 -30,0z';
    this.el = PAPER.path(p).attr({
        'fill': '#01DF01',
        'fill-opacity': 1,
        'stroke': '#000',
        'stroke-width': 1        
    }); 
    
    this.text = PAPER.text(x, y, '').attr({
        'fill': '#000',
        'font-size': '12px'
    });
    
    this.elTop = PAPER.path(p).attr({
        'fill': 'red',
        'fill-opacity': '0',
        'stroke-width': '0'        
    }); 
    
    var elInfo = this.elInfo;
    $(this.elTop.node).tothtip(elInfo);
    
    this.tooltipInfo();
};
PATIENT.prototype.goToWaitingRoom = function (idPatient, storage) {   
    MODULES['wr'].seatsPos[this.seat].patient = idPatient;
    this.wt = true;
    if (this.lastIdModule !== null) {
        this.endTimeOn = new Date().getTime();
        this.timeOn = new Date(this.endTimeOn - this.initTimeOn).getTime();
        MODULES[this.lastIdModule].submodules[this.lastIdSubmodule].patientsAttended++;
        MODULES[this.lastIdModule].submodules[this.lastIdSubmodule].setTimeOn(this.timeOn);
        if (MODULES[this.lastIdModule].dbType === 1) {
            MODULES[this.lastIdModule].totalTicketsIssued++;
            MODULES[this.lastIdModule].ticketsTo[this.nextIdModule]++;
            var timeLastTicket = new Date().getTime();
            if (timeLastTicket > MODULES[this.lastIdModule].timeLastTicket) {
                MODULES[this.lastIdModule].timeLastTicket = timeLastTicket;
            }            
        } else {
            MODULES[this.lastIdModule].attended++;
            MODULES[this.lastIdModule].setTimeOn(this.timeOn);
        }
    }    
    if (storage) {
        var x = MODULES['wr'].seatsPos[this.seat].x,
            y = MODULES['wr'].seatsPos[this.seat].y;
        var s = this.shapePath(),
            fp = 'M'+ x +','+ y + s;
            
        this.el = PAPER.path(fp).attr({
            'fill': '#01DF01',
            'fill-opacity': 1,
            'stroke': '#000',
            'stroke-width': 1        
        }); 
        
        this.text = PAPER.text(x, y, this.ticket).attr({
            'fill': '#000',
            'font-size': '12px'
        });
        
        this.elTop = PAPER.path(fp).attr({
            'fill': 'red',
            'fill-opacity': '0',
            'stroke-width': '0'        
        }); 
        
        var a = this.id.replace(/\./g, ''),
            b = a.replace('-', '');
        this.elTop.node.id = 'p'+ b;
    
        var t = this,
            elInfo = this.elInfo;
        $(this.elTop.node).tothtip(elInfo);
        $(this.elTop.node).on('click', function () {
            t.bitacora();            
        });
        
        this.tooltipInfo();
    } else {
        var bx = PATIENTS[idPatient].el.attrs.path[0][1],
            by = PATIENTS[idPatient].el.attrs.path[0][2],
            fx = MODULES['wr'].seatsPos[this.seat].x,
            fy = MODULES['wr'].seatsPos[this.seat].y;
        var s = this.shapePath(),
            bp = 'M'+ bx +','+ by + s;

        this.text.attr({text: this.ticket});   
        
        this.el.animate({path: bp}, 500, '>', (function (t) {
            return function () {
                var fp = 'M'+ fx +','+ fy + s;
                t.text.animate({x: fx, y: fy}, 1000);
                t.el.animate({path: fp}, 1000);
                t.elTop.animate({path: fp}, 1000);
            };        
        })(this)); 
    }
};
PATIENT.prototype.goToLimb = function (idPatient, storage) {
//    MODULES['lb'].placesPos[this.place].patient = idPatient;
    this.wt = false;
    if (storage) {        
//        var x = MODULES['lb'].placesPos[this.place].x,
//            y = MODULES['lb'].placesPos[this.place].y;
        var x = MODULES['lb'].el.attrs.cx,
            y = MODULES['lb'].el.attrs.cy;
        var s = this.shapePath(),
            fp = 'M'+ x +','+ y + s;
            
        this.el = PAPER.path(fp).attr({
            'fill': '#01DF01',
            'fill-opacity': 1,
            'stroke': '#000',
            'stroke-width': 1        
        }); 
        
        this.elTop = PAPER.path(fp).attr({
            'fill': 'red',
            'fill-opacity': '0',
            'stroke-width': '0'        
        }); 
        
        this.el.animate({'fill-opacity': 0}, 2000, '>', (function (t) {
            return function () {
                t.el.remove();  
                t.elTop.remove();
                t.text.remove();  
//                MODULES['lb'].placesPos[t.place].patient = null;
                delete PATIENTS[idPatient];
            };
        })(this));
    } else {
        var bx = PATIENTS[idPatient].el.attrs.path[0][1],
            by = PATIENTS[idPatient].el.attrs.path[0][2],
//            fx = MODULES['lb'].placesPos[this.place].x,
//            fy = MODULES['lb'].placesPos[this.place].y;
            fx = MODULES['lb'].el.attrs.cx,
            fy = MODULES['lb'].el.attrs.cy;
    
        var s = this.shapePath(),
            bp = 'M'+ bx +','+ by + s;
        
        this.text.remove();

        this.el.animate({path: bp}, 500, '>', (function (t) {
            return function () {
                var fp = 'M'+ fx +','+ fy + s;
                t.el.animate({path: fp}, 1000, '>', (function (s) {
                    return function () {
                        s.el.animate({'fill-opacity': 0}, 2000, '>', (function (r) {
                            return function () {
                                r.el.remove();  
                                r.elTop.remove();  
                                r.text.remove();     
//                                MODULES['lb'].placesPos[r.place].patient = null;
                                delete PATIENTS[idPatient];
                            };
                        })(s));
                    };
                })(t));
            };        
        })(this)); 
    }
};
PATIENT.prototype.goTo = function (idModule, idSubmodule, storage) {
    this.wt = false;
    this.initTimeOn = new Date().getTime();
    this.lastIdModule = idModule;
    this.lastIdSubmodule = idSubmodule;
    switch (MODULES[idModule].el.type) {
        case 'rect':
            if (MODULES[idModule].pos === 'superior' || MODULES[idModule].pos === 'izquierda') {
                var x = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                    y = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5;
            } else if (MODULES[idModule].pos === 'inferior') {
                var x = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                    y = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) - 5;
            } else if (MODULES[idModule].pos === 'derecha') {
                var x = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) - 5,
                    y = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5;
            }         
            if (storage) {
                var s = this.shapePath(),
                    p = 'M'+ x +','+ y + s,
                    content = this.id +'<br />'+
                                this.name;               
                
                this.el = PAPER.path(p).attr({
                    'fill': '#01DF01',
                    'stroke': '#000',
                    'stroke-width': 1        
                }); 
                
                this.text = PAPER.text(x, y, this.ticket).attr({
                    'fill': '#000',
                    'font-size': '10px'
                });
                
                this.elTop = PAPER.path(p).attr({
                    'fill': 'red',
                    'fill-opacity': '0',
                    'stroke-width': '0'        
                }); 
    
                var elInfo = this.elInfo;
                $(this.elTop.node).tothtip(elInfo);
        
                this.tooltipInfo();
            } else {
                var s = this.shapePath(),
                    p = 'M'+ x +','+ y + s;               
                
                this.text.animate({x: x, y: y}, 1000);          
                this.el.animate({path: p, 'fill': '#01DF01'}, 1000);
                this.elTop.animate({path: p}, 1000);
            }                
            break;
        case 'path':
            if (MODULES[idModule].pos === 'superior-izquierda') {
                if (MODULES[idModule].totalSubmodules <= 3) {
                    var px = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                        py = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5,
                        x = ((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) - 14,
                        y = ((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) + 24;
                } else {
                    if (MODULES[idModule].submodules[idSubmodule].el.attrs.width < MODULES[idModule].submodules[idSubmodule].el.attrs.height) {
                        var x = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                            y = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5;
                    } else {
                        var x = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                            y = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5;
                    }
                }
            } else if (MODULES[idModule].pos === 'superior-derecha') {
                if (MODULES[idModule].totalSubmodules <= 3) {
                    var px = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                        py = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5,
                        x = ((px * Math.cos(Math.PI * (1 / 4))) - (py * Math.sin(Math.PI * (1 / 4)))) + 20,
                        y = ((px * Math.sin(Math.PI * (1 / 4))) + (py * Math.cos(Math.PI * (1 / 4)))) - 12;
                } else {
                    if (MODULES[idModule].submodules[idSubmodule].el.attrs.width < MODULES[idModule].submodules[idSubmodule].el.attrs.height) {
                        var x = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                            y = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5;
                    } else {
                        var x = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) - 5,
                            y = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5;
                    }
                }
            } else if (MODULES[idModule].pos === 'inferior-izquierda') {
                if (MODULES[idModule].totalSubmodules <= 3) {
                    var px = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                        py = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5,
                        x = -((px * Math.cos(Math.PI * -(7 / 4))) - (py * Math.sin(Math.PI * -(7 / 4)))) + 14,
                        y = -((px * Math.sin(Math.PI * -(7 / 4))) + (py * Math.cos(Math.PI * -(7 / 4)))) + 64;
                } else {
                    if (MODULES[idModule].submodules[idSubmodule].el.attrs.width < MODULES[idModule].submodules[idSubmodule].el.attrs.height) {
                        var x = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                            y = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) - 5;
                    } else {
                        var x = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                            y = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5;
                    }
                }
            } else if (MODULES[idModule].pos === 'inferior-derecha') {
                if (MODULES[idModule].totalSubmodules <= 3) {
                    var px = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                        py = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5,
                        x = -((px * Math.cos(Math.PI * (7 / 4))) - (py * Math.sin(Math.PI * (7 / 4)))) + 61,
                        y = -((px * Math.sin(Math.PI * (7 / 4))) + (py * Math.cos(Math.PI * (7 / 4)))) + 29;
                } else {
                    if (MODULES[idModule].submodules[idSubmodule].el.attrs.width < MODULES[idModule].submodules[idSubmodule].el.attrs.height) {
                        var x = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) + 5,
                            y = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) - 5;
                    } else {
                        var x = MODULES[idModule].submodules[idSubmodule].el.attrs.x + (MODULES[idModule].submodules[idSubmodule].el.attrs.width / 2) - 5,
                            y = MODULES[idModule].submodules[idSubmodule].el.attrs.y + (MODULES[idModule].submodules[idSubmodule].el.attrs.height / 2) + 5;
                    }
                }
            } 
            
            if (storage) {
                var s = this.shapePath(),
                    p = 'M'+ x +','+ y + s;               
                
                this.el = PAPER.path(p).attr({
                    'fill': '#01DF01',
                    'stroke': '#fff',
                    'stroke-width': 10        
                }); 
                
                this.text = PAPER.text(x, y, this.ticket).attr({
                    'fill': '#000',
                    'font-size': '10px'
                });  
                
                this.elTop = PAPER.path(p).attr({
                    'fill': 'red',
                    'fill-opacity': '0',
                    'stroke-width': '0'        
                }); 
    
                var elInfo = this.elInfo;
                $(this.elTop.node).tothtip(elInfo);
        
                this.tooltipInfo();
            } else {
                var s = this.shapePath(),
                    p = 'M'+ x +','+ y + s;
                
                var el = $(this.el.node);              
                              
                this.text.animate({x: x, y: y}, 1000);
                this.el.animate({path: p, 'fill': '#01DF01'}, 1000);
                this.elTop.animate({path: p}, 1000);
            }              
            break;
    }
    if (MODULES['wr'].seatsPos[this.seat] !== undefined) {
        MODULES['wr'].seatsPos[this.seat].patient = null;
    }    
};
PATIENT.prototype.shapePath = function () {
    if (this.shape === 'circulo') {
        var s = 'm-15,0a15,15 0 1,0 30,0a15,15 0 1,0 -30,0z';
        return s;
    } else if (this.shape === 'cuadrado') {
        var s = 'm10,-10l0,20l-20,0l0,-20z';
        return s;
    } else if (this.shape === 'triangulo') {
        var s = 'm0,-12l12,24l-24,0z';
        return s;
    } else if (this.shape === 'rombo') {
        var s = 'm0,-10l10,10l-10,10l-10,-10z';
        return s;
    } else if (this.shape === 'pentagono') {
        var s = 'm0,-10l10,8l-4,12l-12,0l-4,-12z';
        return s;
    } else if (this.shape === 'hexagono') {
        var s = 'm0,-10l10,6l0,10l-10,6l-10,-6l0,-10z';
        return s;
    } else if (this.shape === 'cruz') {
        var s = 'm-4,-10l10,0l0,6l6,0l0,10l-6,0l0,6l-10,0l0,-6l-6,0l0,-10l6,0l0,-6z';
        return s;
    } else if (this.shape === 'estrella') {
        var s = 'm0,-12l3,6l9,0l-5,6l2,9l-9,-5 l-9,5l2,-9l-5,-6l9,0z';
        return s;
    }
};
PATIENT.prototype.tooltipInfo = function () {
    this.interval = setInterval((function (t) {  
        return function () {
            var wt = new Date().getTime() - t.datetime;
            if (Math.floor(((wt / 1000) / 60) / 60) < 10) {
                var wtHours = '0'+ Math.floor(((wt / 1000) / 60) / 60);
            } else {
                var wtHours = Math.floor(((wt / 1000) / 60) / 60);
            }

            if (new Date(wt).getMinutes() < 10) {
                var wtMinutes = '0'+ new Date(wt).getMinutes();
            } else {
                var wtMinutes = new Date(wt).getMinutes();
            }

            if (new Date(wt).getSeconds() < 10) {
                var wtSeconds = '0'+ new Date(wt).getSeconds();
            } else {
                var wtSeconds = new Date(wt).getSeconds();
            }

            var waitingTime = wtHours +':'+ wtMinutes +':'+ wtSeconds;

            var content = '<u>Nombre</u>: '+ t.name +'<br />';
            content += '<u>Rut</u>: '+ t.id +'<br />';
            content += '<u>Motivo visita</u>: '+ MODULES[t.idModule].name +'<br />';
            if (t.wt) {
                content += '<u>Tiempo de espera</u>: '+ waitingTime +'<br />';
                content += '<u>Ticket</u>: '+ t.ticket;
            }
            t.elInfo.html(content);
        };
    })(this), 1000);
};
PATIENT.prototype.bitacora = function () {
    var t = this;
    $.post('../services/getBitacoraViewer.php', {rut: t.id}, function (dataPatient, status) {
        var d = $.parseJSON(dataPatient);
        if (d.length === 1) {
            var contentPatient = '<div class="col-md-12">';
            contentPatient += '<table class="table table-bordered">';
            contentPatient += '    <tr>';
            contentPatient += '        <th colspan="6" class="text-center bg-primary">Resultados</th>';
            contentPatient += '    </tr>';
            contentPatient += '    <tr>';
            contentPatient += '        <th>RUT/DNI: </th>';
            contentPatient += '        <td>'+ d[0]["rut"] +'</td>';
            contentPatient += '        <th>N° Ficha:</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '        <th>Estado Actual</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '    </tr>';
            contentPatient += '    <tr>';
            contentPatient += '        <th>Nombre: </th>';
            contentPatient += '        <td>'+ d[0]["name"] +' '+ d[0]["lastname"] +'</td>';
            contentPatient += '        <th>N° P. Tratamiento:</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '        <th>Maximo T. de espera:</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '    </tr>';
            contentPatient += '    <tr>';
            contentPatient += '        <th>Fecha de Nacimiento: </th>';
            contentPatient += '        <td>'+ d[0]["birthdate"] +'</td>';
            contentPatient += '        <th>N° Presupuesto:</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '        <th>T. espera cumulado</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '    </tr>';
            contentPatient += '</table>';
            contentPatient += '</div>';
        } else {
            var contentPatient = '<div class="col-md-12 text-center">Sin resultados</div>';
        }
        
        $.post('../services/getLogDataViewer.php', {rut: t.id}, function (dataLogs, status) {
            if (dataLogs !== 0) {
                var d = $.parseJSON(dataLogs);
                var contentLogs = '<div class="col-md-12 text-center">';
                contentLogs += '<table id="dataLogs" class="table table-striped table-bordered">';
                contentLogs += '    <thead>';
                contentLogs += '        <tr>';
                contentLogs += '            <th>Fecha</th>';
                contentLogs += '            <th>Hora</th>';
                contentLogs += '            <th>Descripción</th>';
                contentLogs += '            <th>Zona</th>';
                contentLogs += '            <th>Módulo</th>';
                contentLogs += '            <th>Submódulo</th>';
                contentLogs += '            <th>Usuario</th>';
                contentLogs += '            <th>Hora inicio de espera</th>';
                contentLogs += '            <th>Hora inicio de atención</th>';
                contentLogs += '            <th>Hora fin de atención</th>';
                contentLogs += '            <th>Total espera</th>';
                contentLogs += '            <th>Total atención</th>';
                contentLogs += '        </tr>';
                contentLogs += '    </thead>';
                contentLogs += '    <tbody>';
                for (var i = 0; i < d.length; i++) {
                    contentLogs += '        <tr>';
                    contentLogs += '            <td>'+ d[i].date +'</td>';
                    contentLogs += '            <td>'+ d[i].time +'</td>';
                    contentLogs += '            <td>'+ d[i].description +'</td>';
                    contentLogs += '            <td>'+ d[i].zone +'</td>';
                    contentLogs += '            <td>'+ d[i].module +'</td>';
                    contentLogs += '            <td>'+ d[i].submodule +'</td>';
                    contentLogs += '            <td>'+ d[i].username +'</td>';
                    contentLogs += '            <td>'+ d[i].waitingStart +'</td>';
                    contentLogs += '            <td>'+ d[i].attentionStart +'</td>';
                    contentLogs += '            <td>'+ d[i].attentionFinish +'</td>';
                    contentLogs += '            <td>'+ d[i].waitingTime +'</td>';
                    contentLogs += '            <td>'+ d[i].attentionTime +'</td>';
                    contentLogs += '        </tr>';
                }
                contentLogs += '    </tbody>';
                contentLogs += '</table>';
                contentLogs += '</div>';
            } else {
                var contentLogs = '<div class="col-md-12 text-center">';
                contentLogs += '<table id="dataLogs" class="table table-striped table-bordered">';
                contentLogs += '    <thead>';
                contentLogs += '        <tr>';
                contentLogs += '            <th>Fecha</th>';
                contentLogs += '            <th>Hora</th>';
                contentLogs += '            <th>Descripción</th>';
                contentLogs += '            <th>Zona</th>';
                contentLogs += '            <th>Módulo</th>';
                contentLogs += '            <th>Submódulo</th>';
                contentLogs += '            <th>Usuario</th>';
                contentLogs += '            <th>Hora inicio de espera</th>';
                contentLogs += '            <th>Hora inicio de atención</th>';
                contentLogs += '            <th>Hora fin de atención</th>';
                contentLogs += '            <th>Total espera</th>';
                contentLogs += '            <th>Total atención</th>';
                contentLogs += '        </tr>';
                contentLogs += '    </thead>';
                contentLogs += '    <tbody>';
                contentLogs += '        <tr>';
                contentLogs += '            <td>Sin resultados</td>';
                contentLogs += '        </tr>';
                contentLogs += '    </tbody>';
                contentLogs += '</table>';
                contentLogs += '</div>';
            }            
            
            var content = contentPatient;
            content += contentLogs;

            $('#bitacoraContent').html(content);
            $('#dataLogs').addClass('table table-bordered table-hover');
            $('#dataLogs').dataTable({
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "vendor/datatables/copy_csv_xls_pdf.swf"
                },
                "language": {
                    "url": "vendor/datatables/languaje.lang"
                }
            });

            $('#bitacora').modal('show');
        });    
    });    
};