var PATIENT = function () {
    this.id = null;
    this.zone = null;
    this.module = null;
    this.submodule = null;
    this.waitingFor = null;
    this.attentionNum = null;
    this.rut = null;
    this.name = null; 
    this.el = null;
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
PATIENT.prototype.goToWaitingRoom = function () {
    var x = MODULES['wr'].el.attrs.x + 50,
        y = MODULES['wr'].el.attrs.y + 50;
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
    }
    
};