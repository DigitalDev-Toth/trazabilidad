var SUBMODULE = function (name, id, idModule, posModule, countSubmodules) {
    this.id = id;
    this.name = name;
    this.idModule = idModule;
    this.posModule = posModule;
    this.countSubmodules = countSubmodules;
    this.el = null; // element in DOM
    this.setElem();
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
        case 'top':
            var x = MODULES[this.idModule].el.attrs.x + (MODULES[this.idModule].submoduleWidth * this.countSubmodules) + 10,
                y = MODULES[this.idModule].el.attrs.y + 4;
            this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
            break;
        case 'left':
            var x = MODULES[this.idModule].el.attrs.x + 4,
                y = MODULES[this.idModule].el.attrs.y + (MODULES[this.idModule].submoduleWidth * this.countSubmodules) + 10;
            this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs());
            break;
        case 'bot':
            var x = MODULES[this.idModule].el.attrs.x + (MODULES[this.idModule].submoduleWidth * this.countSubmodules) + 10,
                y = MODULES[this.idModule].el.attrs.y + (MODULES[this.idModule].submoduleHeight - 60)-4;
            this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
            break;
        case 'right':
            var x = MODULES[this.idModule].el.attrs.x + (MODULES[this.idModule].submoduleHeight - 60)-4,
                y = MODULES[this.idModule].el.attrs.y + (MODULES[this.idModule].submoduleWidth * this.countSubmodules) + 10;
            this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs());
            break;
        case 'top-left':
            if (MODULES[this.idModule].totalSubmodules >= 4) {
                if (this.countSubmodules < parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) {
                    var x = 5,
                        y = 100 + (40 * this.countSubmodules);
                    this.el = PAPER.rect(x, y, 60, 40, 5).attr(this.attrs());
                } else {
                    var x = 100 + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))))),
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
            } else if (MODULES[this.idModule].totalSubmodules === 1) {
                var x = 10 + (40 * this.countSubmodules),
                    y = 60;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr(this.attrs());
                this.el.rotate(-45, 20, 30);
            }          
            break;     
        case 'top-right':
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
        case 'bot-left':
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
        case 'bot-right':
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