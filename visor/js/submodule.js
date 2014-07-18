var SUBMODULE = function (name, id, idModule, posModule, countSubmodules) {
    this.id = id;
    this.name = name;
    this.idModule = idModule;
    this.posModule = posModule;
    this.countSubmodules = countSubmodules;
    this.el = null; // element in DOM
    this.setElem();
};
SUBMODULE.prototype.setElem = function () {
    switch (this.posModule) {
        case 'top':
            var x = MODULES[this.idModule].el.attrs.x + (MODULES[this.idModule].submoduleWidth * this.countSubmodules)+10,
                y = MODULES[this.idModule].el.attrs.y + 4;
            this.el = PAPER.rect(x, y, 40, 60, 5).attr({
                'fill': 'none',
                'stroke': '#fff',
                'stroke-width': 2,
                'stroke-linejoin': 'round'
            });
            break;
        case 'left':
            var x = MODULES[this.idModule].el.attrs.x + 4,
                y = MODULES[this.idModule].el.attrs.y + (MODULES[this.idModule].submoduleWidth * this.countSubmodules)+10;
            this.el = PAPER.rect(x, y, 60, 40, 5).attr({
                'fill': 'none',
                'stroke': '#fff',
                'stroke-width': 2,
                'stroke-linejoin': 'round'
            });
            break;
        case 'bot':
            var x = MODULES[this.idModule].el.attrs.x + (MODULES[this.idModule].submoduleWidth * this.countSubmodules)+10,
                y = MODULES[this.idModule].el.attrs.y + (MODULES[this.idModule].submoduleHeight - 60)-4;
            this.el = PAPER.rect(x, y, 40, 60, 5).attr({
                'fill': 'none',
                'stroke': '#fff',
                'stroke-width': 2,
                'stroke-linejoin': 'round'
            });
            break;
        case 'right':
            var x = MODULES[this.idModule].el.attrs.x + (MODULES[this.idModule].submoduleHeight - 60)-4,
                y = MODULES[this.idModule].el.attrs.y + (MODULES[this.idModule].submoduleWidth * this.countSubmodules)+10;
            this.el = PAPER.rect(x, y, 60, 40, 5).attr({
                'fill': 'none',
                'stroke': '#fff',
                'stroke-width': 2,
                'stroke-linejoin': 'round'
            });
            break;
        case 'top-left':
            if (MODULES[this.idModule].totalSubmodules >= 4) {
                if (this.countSubmodules < parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))) {
                    var x = 5,
                        y = 110 + (40 * this.countSubmodules);
                    this.el = PAPER.rect(x, y, 60, 40, 5).attr({
                        'fill': 'none',
                        'stroke': '#fff',
                        'stroke-width': 2,
                        'stroke-linejoin': 'round'
                    });
                } else {
                    var x = 110 + (40 * (this.countSubmodules - (parseInt((MODULES[this.idModule].totalSubmodules / 2).toFixed(0))))),
                        y = 5;
                    this.el = PAPER.rect(x, y, 40, 60, 5).attr({
                        'fill': 'none',
                        'stroke': '#fff',
                        'stroke-width': 2,
                        'stroke-linejoin': 'round'
                    });                    
                }
            } else if (MODULES[this.idModule].totalSubmodules === 3) {
                var x = -30 + (40 * this.countSubmodules),
                    y = 60;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr({
                    'fill': 'none',
                    'stroke': '#fff',
                    'stroke-width': 2,
                    'stroke-linejoin': 'round'
                });
                this.el.rotate(-45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 2) {
                var x = -10 + (40 * this.countSubmodules),
                    y = 60;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr({
                    'fill': 'none',
                    'stroke': '#fff',
                    'stroke-width': 2,
                    'stroke-linejoin': 'round'
                });
                this.el.rotate(-45, 20, 30);
            } else if (MODULES[this.idModule].totalSubmodules === 1) {
                var x = 0 + (40 * this.countSubmodules),
                    y = 60;
                this.el = PAPER.rect(x, y, 40, 60, 5).attr({
                    'fill': 'none',
                    'stroke': '#fff',
                    'stroke-width': 2,
                    'stroke-linejoin': 'round'
                });
                this.el.rotate(-45, 20, 30);
            }          
            break;           
    }    
};