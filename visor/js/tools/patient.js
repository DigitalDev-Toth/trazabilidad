var PATIENT = function (id) {
    this.id = id;
    this.zone = null;
    this.module = null;
    this.submodule = null;
    this.waitingFor = null;
    this.seat = null;
    this.attentionNum = null;
    this.rut = null;
    this.name = null; 
    this.el = null;
    this.posInitial = {x: 0, y: 0};
    this.setElem();
    this.goToTothem();
};
PATIENT.prototype.setElem = function () {
    this.el = PAPER.circle(200, 200, 12).attr({
        'fill': '#ccc',
        'stroke': 'none'                        
    });
};
PATIENT.prototype.goToTothem = function () {
    this.el.animate({cx: 70, cy: 70}, 1000);   
};
PATIENT.prototype.goToWaitingRoom = function (idPatient) {
    var x = MODULES['wr'].seatsPos[this.seat].x,
        y = MODULES['wr'].seatsPos[this.seat].y;
    MODULES['wr'].seatsPos[this.seat].patient = idPatient;
    this.el.animate({cx: x, cy: y}, 1000);   
};
PATIENT.prototype.goTo = function (idModule, idSubmodule) {
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
            this.el.animate({cx: x, cy: y}, 1000);   
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
            this.el.animate({cx: x, cy: y}, 1000);   
            break;
    }
    MODULES['wr'].seatsPos[this.seat].patient = null;
};