var SUBMODULE = function (name, id, idModule, posModule, countSubmodules) {
    this.id = id;
    this.name = name;
    this.idModule = idModule;
    this.posModule = posModule;
    this.countSubmodules = countSubmodules;
    this.el = null; // element in DOM
    this.text = null;
    this.setElem();
};
SUBMODULE.prototype.textAttrs = function (color) {
    return {
        'fill': this.setColor(color, -0.15),
        'font-size': '10px'
        //'stroke': this.setColor(color, -0.5)
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
    return {
        'fill': 'none',
        'stroke': '#fff',
        'stroke-width': 2,
        'stroke-linejoin': 'round'
    };
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
                    var x = 5,
                        y = 110 + (40 * this.countSubmodules);
                    this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs());
                } else {
                    var x = 110 + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))))),
                        y = 5;
                    this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());                    
                }
            } else if (MODULES[this.idModule].totalSubmodules === 3) {
                var x = -30 + (40 * this.countSubmodules),
                    y = 60;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(-45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 2) {
                var x = -10 + (40 * this.countSubmodules),
                    y = 60;
                    this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                    this.el.rotate(-45, 20, 30);
                    this.text = PAPER.text(x + 50, y + 30, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                    this.text.rotate(45);
            } else if (MODULES[this.idModule].totalSubmodules === 1) {
                var x = 0 + (40 * this.countSubmodules),
                    y = 60;
                    this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                    this.el.rotate(-45, 20, 30);
                    this.text = PAPER.text(x + 52, y + 20, this.name).attr(this.textAttrs(MODULES[this.idModule].color));
                    this.text.rotate(45);
            }          
            break;   
        case 'superior-derecha':
            if (MODULES[this.idModule].totalSubmodules >= 4) {
                if (this.countSubmodules < parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) {
                    var w = $(window).width() - 5,
                        x = w - 100 - (40 * parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) + (40 * this.countSubmodules),
                        y = 5;
                    this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                } else {
                    var w = $(window).width() - 5,
                        x = w - (100 - 40),
                        y = 100 + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0)))));
                    this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs());   
                }
            } else if (MODULES[this.idModule].totalSubmodules === 3) {
                var w = $(window).width() - 5,
                    x = 1280 + (40 * this.countSubmodules),
                    y = -1270;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 2) {
                var x = 1300 + (40 * this.countSubmodules),
                    y = -1270;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 1) {
                var x = 1320 + (40 * this.countSubmodules),
                    y = -1270;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(45, 20, 30);
            }     
            break;
        case 'inferior-izquierda':
            if (MODULES[this.idModule].totalSubmodules >= 4) {
                if (this.countSubmodules < parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) {
                    var h = $(window).height(),
                        x = 5,
                        y = h - 100 - (40 * parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) + (40 * this.countSubmodules);
                    this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs());
                } else {
                    var h = $(window).height() - 5,
                        x = 100 + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))))),
                        y = h - (100 - 40);
                    this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());                    
                }
            } else if (MODULES[this.idModule].totalSubmodules === 3) {
                var x = -710 + (40 * this.countSubmodules),
                    y = -605;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(-135, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 2) {
                var x = -690 + (40 * this.countSubmodules),
                    y = -605;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(-135, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 1) {
                var x = -675 + (40 * this.countSubmodules),
                    y = -605;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(-135, 20, 30);
            }      
            break;
        case 'inferior-derecha':
            if (MODULES[this.idModule].totalSubmodules >= 4) {
                if (this.countSubmodules < parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) {
                    var w = $(window).width() - 5,
                        h = $(window).height() - 5,
                        x = w - 100 - (40 * parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) + (40 * this.countSubmodules),
                        y = h - (100 - 40);
                    this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                } else {
                    var w = $(window).width() - 5,
                        h = $(window).height(),
                        x = w - (100 - 40);
                    if ((MODULES[this.idModule].totalSubmodules % 2) === 0) {
                        var y = h - 100 - (40 * parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0)))));
                    } else {
                        var y = h - 100 + 40 - (40 * parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0)))));
                    }                   
                    this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs());   
                }
            } else if (MODULES[this.idModule].totalSubmodules === 3) {
                var w = $(window).width() - 5,
                    x = -700 + (40 * this.countSubmodules),
                    y = -1935;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(135, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 2) {
                var x = -680 + (40 * this.countSubmodules),
                    y = -1935;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(135, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 1) {
                var x = -660 + (40 * this.countSubmodules),
                    y = -1935;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(135, 20, 30);
            }    
            break;
    }    
};