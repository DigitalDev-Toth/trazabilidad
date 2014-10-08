var PATIENT = function (id, name, ticket, datetime, idModule, storage) {
    this.id = id; // rut
    this.name = name;
    this.shape = 'circulo';
    this.idModule = idModule;
    this.lastIdModule = null;
    this.lastIdSubmodule = null;
    this.seat = null; // seat for waiting room
    this.place = null; // place for limb
    this.ticket = ticket;
    this.datetime = new Date(datetime).getTime();
    this.timeOn = null;
    this.initTimeOn = null;
    this.endTimeOn = null;
    this.interval = null;
    this.el = null; // element DOM for patient
    this.text = null; // text DOM for patient
    if (!storage) {
        this.setElem(idModule);
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
    var p = 'M'+ x +','+ y +'m-12,0a12,12 0 1,0 24,0a12,12 0 1,0 -24,0z';
    this.el = PAPER.path(p).attr({
        'fill': '#01DF01',
        'fill-opacity': 1,
        'stroke': '#000',
        'stroke-width': 1        
    }); 
    
    this.text = PAPER.text(x, y, '').attr({
        'fill': '#000',
        'font-size': '10px'
    });
    
    $(this.el.node).popover({
        'container': 'body',
        'trigger': 'click',
        'html': true,
        'placement': 'auto',
        'content': this.id
    });
    
    var el = $(this.el.node);
    $(this.text.node).on('click', function () {            
        el.trigger('click');
    });
};
PATIENT.prototype.goToWaitingRoom = function (idPatient, storage) {   
    MODULES['wr'].seatsPos[this.seat].patient = idPatient;
    if (this.lastIdModule !== null) {
        this.endTimeOn = new Date().getTime();
        this.timeOn = new Date(this.endTimeOn - this.initTimeOn).getTime();
        MODULES[this.lastIdModule].submodules[this.lastIdSubmodule].patientsAttended++;
        MODULES[this.lastIdModule].submodules[this.lastIdSubmodule].setTimeOn(this.timeOn);
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
            'font-size': '10px'
        });
        
        $(this.el.node).popover({
            'container': 'body',
            'trigger': 'click',
            'html': true,
            'placement': 'auto'
        });
        
        var datetime = this.datetime,
            id = this.id,
            name = this.name,
            el = $(this.el.node),
            idModule = this.idModule;
        this.interval = setInterval(function () {            
            var time = new Date().getTime() - datetime,
                minutes = Math.floor((time / 1000) / 60),
                content = id +'<br />'+
                        name +'<br />'+
                        'Esperando por: '+ MODULES[idModule].name +'<br />'+
                        minutes +' minutos';
            el.attr('data-content', content);
        }, 1000);

        var el = $(this.el.node);
        $(this.text.node).on('click', function () {            
            el.trigger('click');
        });
    } else {
        var bx = PATIENTS[idPatient].el.attrs.path[0][1],
            by = PATIENTS[idPatient].el.attrs.path[0][2],
            fx = MODULES['wr'].seatsPos[this.seat].x,
            fy = MODULES['wr'].seatsPos[this.seat].y;
        var s = this.shapePath(),
            bp = 'M'+ bx +','+ by + s;

        this.text.attr({text: this.ticket});
        
        var datetime = this.datetime,
            id = this.id,
            name = this.name,
            el = $(this.el.node),
            idModule = this.idModule;
        
        this.interval = setInterval(function () {
            var time = new Date().getTime() - datetime,
                minutes = Math.floor((time / 1000) / 60),
                content = id +'<br />'+
                        name +'<br />'+
                        'Esperando por: '+ MODULES[idModule].name +'<br />'+
                        minutes +' minutos';
            el.attr('data-content', content);
        }, 1000);
        
        $(this.el.node).popover('hide');
        this.el.animate({path: bp}, 500, '>', (function (t) {
            return function () {
                var fp = 'M'+ fx +','+ fy + s;
                t.text.animate({x: fx, y: fy}, 1000);
                t.el.animate({path: fp}, 1000);
            };        
        })(this)); 
    }
};
PATIENT.prototype.goToLimb = function (idPatient, storage) {
    clearInterval(this.interval);
    MODULES['lb'].placesPos[this.place].patient = idPatient;
    if (storage) {        
        var x = MODULES['lb'].placesPos[this.place].x,
            y = MODULES['lb'].placesPos[this.place].y;
        var s = this.shapePath(),
            fp = 'M'+ x +','+ y + s;
            
        this.el = PAPER.path(fp).attr({
            'fill': '#01DF01',
            'fill-opacity': 1,
            'stroke': '#000',
            'stroke-width': 1        
        }); 
        
        $(this.el.node).popover({
            'container': 'body',
            'trigger': 'click',
            'html': true,
            'placement': 'auto',
            'content': this.id
        });
        
        this.el.animate({'fill-opacity': 0}, 5000, '>', (function (t) {
            return function () {
                $(t.el.node).popover('destroy');
                t.el.remove();                
                MODULES['lb'].placesPos[t.place].patient = null;
                delete PATIENTS[idPatient];
            };
        })(this));
    } else {
        var bx = PATIENTS[idPatient].el.attrs.path[0][1],
            by = PATIENTS[idPatient].el.attrs.path[0][2],
            fx = MODULES['lb'].placesPos[this.place].x,
            fy = MODULES['lb'].placesPos[this.place].y;
    
        var s = this.shapePath(),
            bp = 'M'+ bx +','+ by + s;
        
        this.text.remove();
        $(this.el.node).popover('hide');
        this.el.animate({path: bp}, 500, '>', (function (t) {
            return function () {
                var fp = 'M'+ fx +','+ fy + s;
                t.el.animate({path: fp}, 1000, '>', (function (s) {
                    return function () {
                        s.el.animate({'fill-opacity': 0}, 5000, '>', (function (r) {
                            return function () {
                                $(r.el.node).popover('destroy');
                                r.el.remove();                                
                                MODULES['lb'].placesPos[r.place].patient = null;
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
    clearInterval(this.interval);
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
                
                $(this.el.node).popover({
                    'container': 'body',
                    'trigger': 'click',
                    'html': true,
                    'placement': 'auto',
                    'content': content
                });
                
                var el = $(this.el.node);
                $(this.text.node).on('click', function () {            
                    el.trigger('click');
                });
            } else {
                var s = this.shapePath(),
                    p = 'M'+ x +','+ y + s,
                    content = this.id +'<br />'+
                                this.name;
                
                $(this.el.node).attr('data-content', content);
                
                $(this.el.node).popover('hide');
                this.text.animate({x: x, y: y}, 1000);          
                this.el.animate({path: p, 'fill': '#01DF01'}, 1000);
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
                    p = 'M'+ x +','+ y + s,
                    content = this.id +'<br />'+
                                this.name;               
                
                this.el = PAPER.path(p).attr({
                    'fill': '#01DF01',
                    'stroke': '#fff',
                    'stroke-width': 10        
                }); 
                
                this.text = PAPER.text(x, y, this.ticket).attr({
                    'fill': '#000',
                    'font-size': '10px'
                });  
                
                $(this.el.node).popover({
                    'container': 'body',
                    'trigger': 'click',
                    'html': true,
                    'placement': 'auto',
                    'content': content
                });
                
                var el = $(this.el.node);
                $(this.text.node).on('click', function () {            
                    el.trigger('click');
                });
            } else {
                var s = this.shapePath(),
                    p = 'M'+ x +','+ y + s,
                    content = this.id +'<br />'+
                                this.name;                
                
                $(this.el.node).attr('data-content', content);
                
                var el = $(this.el.node);              
                
                $(this.el.node).popover('hide');                
                this.text.animate({x: x, y: y}, 1000);
                this.el.animate({path: p, 'fill': '#01DF01'}, 1000);
            }              
            break;
    }
    if (MODULES['wr'].seatsPos[this.seat] !== undefined) {
        MODULES['wr'].seatsPos[this.seat].patient = null;
    }    
};
PATIENT.prototype.shapePath = function () {
    if (this.shape === 'circulo') {
        var s = 'm-12,0a12,12 0 1,0 24,0a12,12 0 1,0 -24,0z';
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