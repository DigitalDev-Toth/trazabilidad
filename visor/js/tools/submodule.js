var SUBMODULE = function (name, id, idModule, posModule, countSubmodules, state) {
    this.id = id;
    this.name = name;
    this.idModule = idModule;
    this.posModule = posModule;
    this.countSubmodules = countSubmodules;
    this.state = state;
    this.el = null; // element in DOM
    this.text = null;
    this.elInfo = null;
    this.executive = null;
    this.activeTime = null;
    this.patientsAttended = null;
    this.average = null;
    this.max = null;
    this.min = null;
    this.timeOn = null;
    this.interval = null;
    this.setElem();    
};
SUBMODULE.prototype.textAttrs = function (color) {
    return {
        'fill': this.setColor(color, 0.5),
        'font-size': '10px'
    };
};
SUBMODULE.prototype.setColor = function (hex, lum) {
    hex = String(hex).replace(/[^0-9a-f]/gi, '');
    if (hex.length < 6) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    lum = lum || 0;
    var rgb = "#", c, i;
    for (i = 0; i < 3; i++) {
        c = parseInt(hex.substr(i * 2, 2), 16);
        c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
        rgb += ("00"+ c).substr(c.length);
    }
    return rgb;
};
SUBMODULE.prototype.attrs = function () {
    if(this.state === 'activo') {
        var stroke = this.setColor(MODULES[this.idModule].color, -0.3);
        var fill = this.setColor(MODULES[this.idModule].color, -0.2);
    } else {
        var fill = '#aaa';
        var stroke = '#777';
    }
    return {
        'fill': fill,
        'stroke': stroke,
        'stroke-width': 2,
        'stroke-linejoin': 'round'
    };
};
SUBMODULE.prototype.setActive = function () {
    this.state = 'activo';
    this.el.animate(this.attrs(), 500);
};
SUBMODULE.prototype.setInactive = function () {
    this.state = 'inactivo';
    this.el.animate(this.attrs(), 500);
};
SUBMODULE.prototype.blink = function (i) {
    if (i < 7) {
        i++;
        var stroke = this.el.attrs.stroke;
        this.el.animate({stroke: '#fff'}, 100, 'linear', (function (t, i) {
            return function () {
                t.el.animate({stroke: stroke}, 100, t.blink(i));
            };
        })(this, i));
    }
};
SUBMODULE.prototype.setElem = function () {
    switch (this.posModule) {
        case 'superior':
            var x = MODULES[this.idModule].el.attrs.x + (MODULES[this.idModule].submoduleWidth * this.countSubmodules) + 10,
                y = MODULES[this.idModule].el.attrs.y + 4;
            this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
            this.text = PAPER.text(x + 8, y + 30, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
            this.text.rotate(-90);
            break;
        case 'izquierda':
            var x = MODULES[this.idModule].el.attrs.x + 4,
                y = MODULES[this.idModule].el.attrs.y + (MODULES[this.idModule].submoduleWidth * this.countSubmodules) + 10;
            this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs());
            this.text = PAPER.text(x + 30, y + 8, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
            //text.rotate(-90);
            break;
        case 'inferior':
            var x = MODULES[this.idModule].el.attrs.x + (MODULES[this.idModule].submoduleWidth * this.countSubmodules) + 10,
                y = MODULES[this.idModule].el.attrs.y + (MODULES[this.idModule].submoduleHeight - 60) - 4;
            this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
            this.text = PAPER.text(x + 8, y + 30, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
            this.text.rotate(-90);
            break;
        case 'derecha':
            var x = MODULES[this.idModule].el.attrs.x + (MODULES[this.idModule].submoduleHeight - 60) - 4,
                y = MODULES[this.idModule].el.attrs.y + (MODULES[this.idModule].submoduleWidth * this.countSubmodules) + 10;
            this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs());
            this.text = PAPER.text(x + 30, y + 8, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                //text.rotate(-90);
            break;
        case 'superior-izquierda':
            if (MODULES[this.idModule].totalSubmodules >= 4) {
                if (this.countSubmodules < parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) {
                    var x = 10,
                        y = 90 + (40 * this.countSubmodules);
                    this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs());
                    this.text = PAPER.text(x + 30, y + 8, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                } else {
                    var x = 90 + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))))),
                        y = 10;
                    this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());  
                    this.text = PAPER.text(x + 8, y + 30, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                    this.text.rotate(-90);
                }
            } else if (MODULES[this.idModule].totalSubmodules === 3) {
                var w = 5,
                    h = 5,
                    px = w,
                    py = h,
                    x = ((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) + 40 - (40 * this.countSubmodules),
                    y = ((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) + 64,
                    xt = ((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) + 75,
                    yt = ((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) - 5 + (40 * this.countSubmodules);
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(-45, 20, 30);
                this.text = PAPER.text(xt, yt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 2) {
                var w = 5,
                    h = 5,
                    px = w,
                    py = h,
                    x = ((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) + 20 - (40 * this.countSubmodules),
                    y = ((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) + 64,
                    xt = ((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) + 75,
                    yt = ((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) + 14 + (40 * this.countSubmodules);
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(-45, 20, 30);
                this.text = PAPER.text(xt, yt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 1) {
                var w = 5,
                    h = 5,
                    px = w,
                    py = h,
                    x = ((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) - (40 * this.countSubmodules),
                    y = ((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) + 64,
                    xt = ((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) + 75,
                    yt = ((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) + 36 + (40 * this.countSubmodules);
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(-45, 20, 30);
                this.text = PAPER.text(xt, yt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(45, 20, 30);
            }          
            break;   
        case 'superior-derecha':
            if (MODULES[this.idModule].totalSubmodules >= 4) {
                if (this.countSubmodules < parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) {
                    var w = $(window).width() - 5,
                        x = w - 90 - (40 * parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) + (40 * this.countSubmodules),
                        y = 10;
                    this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                    this.text = PAPER.text(x + 8, y + 30, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                    this.text.rotate(-90);
                } else {
                    var w = $(window).width() - 10,
                        x = w - (100 - 40),
                        y = 90 + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0)))));
                    this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs()); 
                    this.text = PAPER.text(x + 30, y + 8, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                }
            } else if (MODULES[this.idModule].totalSubmodules === 3) {
                var w = $(window).width() - 5,
                    h = 5,
                    px = w - 150,
                    py = h - 100,
                    x = ((px * Math.cos(Math.PI * (1 / 4))) - (py * Math.sin(Math.PI * (1 / 4)))) + 46 - (40 * this.countSubmodules),
                    y = -((px * Math.sin(Math.PI * (1 / 4))) + (py * Math.cos(Math.PI * (1 / 4)))) - 86,
                    xt = ((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) + 105,
                    yt = -((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) + 88 - (40 * this.countSubmodules);
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(45, 20, 30);
                this.text = PAPER.text(xt, yt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(-45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 2) {
                var w = $(window).width() - 5,
                    h = 5,
                    px = w - 150,
                    py = h - 100,
                    x = ((px * Math.cos(Math.PI * (1 / 4))) - (py * Math.sin(Math.PI * (1 / 4)))) + 26 - (40 * this.countSubmodules),
                    y = -((px * Math.sin(Math.PI * (1 / 4))) + (py * Math.cos(Math.PI * (1 / 4)))) - 86,
                    xt = ((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) + 105,
                    yt = -((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) + 68 - (40 * this.countSubmodules);
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(45, 20, 30);
                this.text = PAPER.text(xt, yt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(-45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 1) {
                var w = $(window).width() - 5,
                    h = 5,
                    px = w - 150,
                    py = h - 100,
                    x = ((px * Math.cos(Math.PI * (1 / 4))) - (py * Math.sin(Math.PI * (1 / 4)))) + 6 - (40 * this.countSubmodules),
                    y = -((px * Math.sin(Math.PI * (1 / 4))) + (py * Math.cos(Math.PI * (1 / 4)))) - 86,
                    xt = ((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) + 105,
                    yt = -((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) + 48 - (40 * this.countSubmodules);
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(45, 20, 30);
                this.text = PAPER.text(xt, yt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(-45, 20, 30);
            }     
            break;
        case 'inferior-izquierda':
            if (MODULES[this.idModule].totalSubmodules >= 4) {
                if (this.countSubmodules < parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) {
                    var h = $(window).height(),
                        x = 10,
                        y = h - 90 - (40 * parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) + (40 * this.countSubmodules);
                    this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs());
                    this.text = PAPER.text(x + 30, y + 8, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                } else {
                    var h = $(window).height() - 10,
                        x = 90 + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))))),
                        y = h - (100 - 40);
                    this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs()); 
                    this.text = PAPER.text(x + 8, y + 30, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                    this.text.rotate(-90);
                }
            } else if (MODULES[this.idModule].totalSubmodules === 3) {
                var w = 5,
                    h = $(window).height() - 5,
                    px = w - 150,
                    py = h - 100,
                    x = -((px * Math.cos(Math.PI * (7 / 4))) - (py * Math.sin(Math.PI * (7 / 4)))) - 102 - (40 * this.countSubmodules),
                    y = -((px * Math.sin(Math.PI * (7 / 4))) + (py * Math.cos(Math.PI * (7 / 4)))) + 134,
                    xt = -((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) - 51,
                    yt = ((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) - 60 + (40 * this.countSubmodules);
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(-135, 20, 30);
                this.text = PAPER.text(xt, yt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(-45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 2) {
                var w = 5,
                    h = $(window).height() - 5,
                    px = w - 150,
                    py = h - 100,
                    x = -((px * Math.cos(Math.PI * (7 / 4))) - (py * Math.sin(Math.PI * (7 / 4)))) - 122 - (40 * this.countSubmodules),
                    y = -((px * Math.sin(Math.PI * (7 / 4))) + (py * Math.cos(Math.PI * (7 / 4)))) + 134,
                    xt = -((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) - 51,
                    yt = ((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) - 40 + (40 * this.countSubmodules);
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(-135, 20, 30);
                this.text = PAPER.text(xt, yt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(-45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 1) {
                var w = 5,
                    h = $(window).height() - 5,
                    px = w - 150,
                    py = h - 100,
                    x = -((px * Math.cos(Math.PI * (7 / 4))) - (py * Math.sin(Math.PI * (7 / 4)))) - 142 - (40 * this.countSubmodules),
                    y = -((px * Math.sin(Math.PI * (7 / 4))) + (py * Math.cos(Math.PI * (7 / 4)))) + 134,
                    xt = -((px * Math.cos(Math.PI * -(1 / 4))) - (py * Math.sin(Math.PI * -(1 / 4)))) - 51,
                    yt = ((px * Math.sin(Math.PI * -(1 / 4))) + (py * Math.cos(Math.PI * -(1 / 4)))) - 20 + (40 * this.countSubmodules);
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(-135, 20, 30);
                this.text = PAPER.text(xt, yt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(-45, 20, 30);
            }      
            break;
        case 'inferior-derecha':
            if (MODULES[this.idModule].totalSubmodules >= 4) {
                if (this.countSubmodules < parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) {
                    var w = $(window).width() - 5,
                        h = $(window).height() - 10,
                        x = w - 90 - (40 * parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) + (40 * this.countSubmodules),
                        y = h - (100 - 40);
                    this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                    this.text = PAPER.text(x + 8, y + 30, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                    this.text.rotate(-90);
                } else {
                    var w = $(window).width() - 10,
                        h = $(window).height(),
                        x = w - (100 - 40);
                    if ((MODULES[this.idModule].totalSubmodules % 2) === 0) {
                        var y = h - 90 - (40 * parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0)))));
                    } else {
                        var y = h - 90 + 40 - (40 * parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0)))));
                    }                   
                    this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs()); 
                    this.text = PAPER.text(x + 30, y + 8, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                }
            } else if (MODULES[this.idModule].totalSubmodules === 3) {
                var w = $(window).width() - 5,
                    h = $(window).height() - 5,
                    px = w - 150,
                    py = h - 100,
                    x = -((px * Math.cos(Math.PI * -(7 / 4))) - (py * Math.sin(Math.PI * -(7 / 4)))) - 2 - (40 * this.countSubmodules),
                    y = -((px * Math.sin(Math.PI * -(7 / 4))) + (py * Math.cos(Math.PI * -(7 / 4)))) - 50,
                    xt = -((px * Math.cos(Math.PI * (1 / 4))) - (py * Math.sin(Math.PI * (1 / 4)))) + 40 - (40 * this.countSubmodules),
                    yt = ((px * Math.sin(Math.PI * (1 / 4))) + (py * Math.cos(Math.PI * (1 / 4)))) + 70;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(135, 20, 30);
                this.text = PAPER.text(yt, xt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 2) {
                var w = $(window).width() - 5,
                    h = $(window).height() - 5,
                    px = w - 150,
                    py = h - 100,
                    x = -((px * Math.cos(Math.PI * -(7 / 4))) - (py * Math.sin(Math.PI * -(7 / 4)))) - 22 - (40 * this.countSubmodules),
                    y = -((px * Math.sin(Math.PI * -(7 / 4))) + (py * Math.cos(Math.PI * -(7 / 4)))) - 50,
                    xt = -((px * Math.cos(Math.PI * (1 / 4))) - (py * Math.sin(Math.PI * (1 / 4)))) + 20 - (40 * this.countSubmodules),
                    yt = ((px * Math.sin(Math.PI * (1 / 4))) + (py * Math.cos(Math.PI * (1 / 4)))) + 70;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(135, 20, 30);
                this.text = PAPER.text(yt, xt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 1) {
                var w = $(window).width() - 5,
                    h = $(window).height() - 5,
                    px = w - 150,
                    py = h - 100,
                    x = -((px * Math.cos(Math.PI * -(7 / 4))) - (py * Math.sin(Math.PI * -(7 / 4)))) - 42 - (40 * this.countSubmodules),
                    y = -((px * Math.sin(Math.PI * -(7 / 4))) + (py * Math.cos(Math.PI * -(7 / 4)))) - 50,
                    xt = -((px * Math.cos(Math.PI * (1 / 4))) - (py * Math.sin(Math.PI * (1 / 4)))) + 0 - (40 * this.countSubmodules),
                    yt = ((px * Math.sin(Math.PI * (1 / 4))) + (py * Math.cos(Math.PI * (1 / 4)))) + 70;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(135, 20, 30);
                this.text = PAPER.text(yt, xt, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                this.text.rotate(45, 20, 30);
            }    
            break;
    }    
};
SUBMODULE.prototype.info = function (executive, activeTime, patientsAttended, average, max, min) {
    this.elInfo = $('<div></div>');
    this.executive = executive;
    this.activeTime = new Date(activeTime).getTime();
    this.patientsAttended = patientsAttended;
    this.average = new Date(average).getTime() - new Date().setHours(0, 0, 0);
    this.max = new Date(max).getTime() - new Date().setHours(0, 0, 0);
    this.min = new Date(min).getTime() - new Date().setHours(0, 0, 0);
    
    var elInfo = this.elInfo;
    $(this.el.node).popover({
        'container': 'body',
        'trigger': 'click',
        'html': true,
        'placement': 'auto',
        'content': elInfo
    });
};
SUBMODULE.prototype.popoverInfo = function () {
    this.interval = setInterval((function (t) {
        return function () {
            var activeTimeTime = new Date().getTime() - t.activeTime;  
            
            if (Math.floor(((activeTimeTime / 1000) / 60) / 60) < 10) {
                var activeTimeHours = '0'+ Math.floor(((activeTimeTime / 1000) / 60) / 60);
            } else {
                var activeTimeHours = Math.floor(((activeTimeTime / 1000) / 60) / 60);
            }       
            
            if (new Date(activeTimeTime).getMinutes() < 10) {
                var activeTimeMinutes = '0'+ new Date(activeTimeTime).getMinutes();
            } else {
                var activeTimeMinutes = new Date(activeTimeTime).getMinutes();
            }
            
            if (new Date(activeTimeTime).getSeconds() < 10) {
                var activeTimeSeconds = '0'+ new Date(activeTimeTime).getSeconds();
            } else {
                var activeTimeSeconds = new Date(activeTimeTime).getSeconds();
            }
            
            if (Math.floor(((t.average / 1000) / 60) / 60) < 10) {
                var averageHours = '0'+ Math.floor(((t.average / 1000) / 60) / 60);
            } else {
                var averageHours = Math.floor(((t.average / 1000) / 60) / 60);
            }
            
            if (new Date(t.average).getMinutes() < 10) {
                var averageMinutes = '0'+ new Date(t.average).getMinutes();
            } else {
                var averageMinutes = new Date(t.average).getMinutes();
            }
            
            if (new Date(t.average).getSeconds() < 10) {
                var averageSeconds = '0'+ new Date(t.average).getSeconds();
            } else {
                var averageSeconds = new Date(t.average).getSeconds();
            }
            
            if (Math.floor(((t.max / 1000) / 60) / 60) < 10) {
                var maxHours = '0'+ Math.floor(((t.max / 1000) / 60) / 60);
            } else {
                var maxHours = Math.floor(((t.max / 1000) / 60) / 60);
            }
            
            if (new Date(t.max).getMinutes() < 10) {
                var maxMinutes = '0'+ new Date(t.max).getMinutes();
            } else {
                var maxMinutes = new Date(t.max).getMinutes();
            }
            
            if (new Date(t.max).getSeconds() < 10) {
                var maxSeconds = '0'+ new Date(t.max).getSeconds();
            } else {
                var maxSeconds = new Date(t.max).getSeconds();
            }
            
            if (Math.floor(((t.min / 1000) / 60) / 60) < 10) {
                var minHours = '0'+ Math.floor(((t.min / 1000) / 60) / 60);
            } else {
                var minHours = Math.floor(((t.min / 1000) / 60) / 60);
            }
            
            if (new Date(t.min).getMinutes() < 10) {
                var minMinutes = '0'+ new Date(t.min).getMinutes();
            } else {
                var minMinutes = new Date(t.min).getMinutes();
            }
            
            if (new Date(t.min).getSeconds() < 10) {
                var minSeconds = '0'+ new Date(t.min).getSeconds();
            } else {
                var minSeconds = new Date(t.min).getSeconds();
            }
            
            var activeTime = activeTimeHours +':'+ activeTimeMinutes +':'+ activeTimeSeconds;
            var average = averageHours +':'+ averageMinutes +':'+ averageSeconds;
            var max = maxHours +':'+ maxMinutes +':'+ maxSeconds;
            var min = minHours +':'+ minMinutes +':'+ minSeconds;
            
            var content = '<u>Ejecutivo</u>: '+ t.executive +'<br />'
                            +'<u>Tiempo activo</u>: '+ activeTime +'<br />'
                            +'<u>Pacientes atendidos</u>: '+ t.patientsAttended +'<br />'
                            +'<u>Promedio</u>: '+ average +'<br />'                    
                            +'<u>Máximo</u>: '+ max +'<br />'
                            +'<u>Mínimo</u>: '+ min;
            t.elInfo.html(content);
        };        
    })(this), 1000);
};
SUBMODULE.prototype.setTimeOn = function (timeOn) {
    this.timeOn = timeOn;
    
    if (this.timeOn > this.max) {
        this.max = this.timeOn;
    } else if (this.timeOn < this.min) {
        this.min = this.timeOn;
    }
    
    this.average = (this.average * (this.patientsAttended - 1) + this.timeOn) / this.patientsAttended;
};