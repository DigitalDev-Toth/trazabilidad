var PATIENT = function (id, ticket, datetime, idModule, storage) {
    this.id = id; // rut
    this.shape = 'circulo';
    this.seat = null; // seat for waiting room
    this.place = null; // place for limb
    this.ticket = ticket;
    this.datetime = new Date(datetime).getTime();
    this.el = null; // element DOM for patient
    this.text = null; // text DOM for patient
    if (!storage) {
        this.setElem(idModule);
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
        'fill': '#ccc',
        'fill-opacity': 1,
        'stroke': '#000',
        'stroke-width': 0        
    }); 
};
PATIENT.prototype.goToWaitingRoom = function (idPatient, storage) {   
    MODULES['wr'].seatsPos[this.seat].patient = idPatient;
    if (storage) {
        var x = MODULES['wr'].seatsPos[this.seat].x,
            y = MODULES['wr'].seatsPos[this.seat].y;
        var s = this.shapePath(),
            fp = 'M'+ x +','+ y + s;
            
        this.el = PAPER.path(fp).attr({
            'fill': '#ccc',
            'fill-opacity': 1,
            'stroke': '#000',
            'stroke-width': 0        
        }); 

        this.text = PAPER.text(x, y, this.ticket).attr({
            'fill': '#000',
            'font-size': '10px'
        });

        this.el.node.setAttribute('data-toggle', 'popover');
        this.el.node.setAttribute('data-placement', 'top'); 
        this.el.node.setAttribute('data-content', this.id); 

        $(this.el.node).popover({
            'container': 'body',
            'trigger': 'click'
        });

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
        if (this.text === null) {
            this.text = PAPER.text(bx, by, this.ticket).attr({
                'fill': '#000',
                'font-size': '10px'
            });
            var el = $(this.el.node);
            $(this.text.node).on('click', function () {            
                el.trigger('click');
            });
        } else {
            this.text.attr({text: this.ticket});
        }
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
    MODULES['lb'].placesPos[this.place].patient = idPatient;
    if (storage) {        
        var x = MODULES['lb'].placesPos[this.place].x,
            y = MODULES['lb'].placesPos[this.place].y;
        var s = this.shapePath(),
            fp = 'M'+ x +','+ y + s;
            
        this.el = PAPER.path(fp).attr({
            'fill': '#ccc',
            'fill-opacity': 1,
            'stroke': '#000',
            'stroke-width': 0        
        }); 

        this.el.node.setAttribute('data-toggle', 'popover');
        this.el.node.setAttribute('data-placement', 'top'); 
        this.el.node.setAttribute('data-content', this.id); 

        $(this.el.node).popover({
            'container': 'body',
            'trigger': 'click'
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
                    p = 'M'+ x +','+ y + s;               
                
                this.el = PAPER.path(p).attr({
                    'fill': '#ccc',
                    'stroke': '#000',
                    'stroke-width': 0        
                }); 
                
                this.text = PAPER.text(x, y, this.ticket).attr({
                    'fill': '#000',
                    'font-size': '10px'
                });
                
                this.el.node.setAttribute('data-toggle', 'popover');
                if (MODULES[idModule].pos === 'superior') {
                    this.el.node.setAttribute('data-placement', 'bottom');
                } else {
                    this.el.node.setAttribute('data-placement', 'top');
                }                 
                this.el.node.setAttribute('data-content', this.id); 

                $(this.el.node).popover({
                    'container': 'body',
                    'trigger': 'click'
                });
                
                var el = $(this.el.node);
                $(this.text.node).on('click', function () {            
                    el.trigger('click');
                });
            } else {
                var s = this.shapePath(),
                    p = 'M'+ x +','+ y + s;
            
                this.el.node.setAttribute('data-toggle', 'popover');
                if (MODULES[idModule].pos === 'superior') {
                    this.el.node.setAttribute('data-placement', 'bottom');
                } else {
                    this.el.node.setAttribute('data-placement', 'top');
                }                 
                this.el.node.setAttribute('data-content', this.id); 

                $(this.el.node).popover({
                    'container': 'body',
                    'trigger': 'click'
                });
                
                var el = $(this.el.node);
                $(this.text.node).on('click', function () {            
                    el.trigger('click');
                });

                $(this.el.node).popover('hide');
                if (this.text !== null) {
                    this.text.animate({x: x, y: y}, 1000);
                }           
                this.el.animate({path: p, 'fill': '#ccc'}, 1000);
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
                    'fill': '#ccc',
                    'stroke': '#000',
                    'stroke-width': 0        
                }); 
                
                this.text = PAPER.text(x, y, this.ticket).attr({
                    'fill': '#000',
                    'font-size': '10px'
                });
                
                this.el.node.setAttribute('data-toggle', 'popover');
                if (((MODULES[idModule].pos === 'superior-izquierda' || MODULES[idModule].pos === 'superior-derecha') &&
                    MODULES[idModule].totalSubmodules > 3 && 
                    MODULES[idModule].submodules[idSubmodule].el.attrs.width < MODULES[idModule].submodules[idSubmodule].el.attrs.height) || 
                    ((MODULES[idModule].pos === 'superior-izquierda' || MODULES[idModule].pos === 'superior-derecha') && 
                    MODULES[idModule].totalSubmodules <= 3)) {
                    this.el.node.setAttribute('data-placement', 'bottom');
                } else {
                    this.el.node.setAttribute('data-placement', 'top');
                }   
                this.el.node.setAttribute('data-content', this.id); 

                $(this.el.node).popover({
                    'container': 'body',
                    'trigger': 'click'
                });
                
                var el = $(this.el.node);
                $(this.text.node).on('click', function () {            
                    el.trigger('click');
                });
            } else {
                var s = this.shapePath(),
                    p = 'M'+ x +','+ y + s;
                
                this.el.node.setAttribute('data-toggle', 'popover');
                if (((MODULES[idModule].pos === 'superior-izquierda' || MODULES[idModule].pos === 'superior-derecha') &&
                    MODULES[idModule].totalSubmodules > 3 && 
                    MODULES[idModule].submodules[idSubmodule].el.attrs.width < MODULES[idModule].submodules[idSubmodule].el.attrs.height) || 
                    ((MODULES[idModule].pos === 'superior-izquierda' || MODULES[idModule].pos === 'superior-derecha') && 
                    MODULES[idModule].totalSubmodules <= 3)) {
                    this.el.node.setAttribute('data-placement', 'bottom');
                } else {
                    this.el.node.setAttribute('data-placement', 'top');
                }                 
                this.el.node.setAttribute('data-content', this.id); 

                $(this.el.node).popover({
                    'container': 'body',
                    'trigger': 'click'
                });
                
                var el = $(this.el.node);
                if (this.text !== null) {
                    $(this.text.node).on('click', function () {            
                        el.trigger('click');
                    });
                }               
                
                $(this.el.node).popover('hide');
                if (this.text !== null) {                
                    this.text.animate({x: x, y: y}, 1000);
                }
                this.el.animate({path: p}, 1000);
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
    }   
};