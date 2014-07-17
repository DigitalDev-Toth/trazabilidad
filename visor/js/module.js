var MODULE = function (name, id, type, pos, color, totalSubmodules) {
    this.id = id;
    this.submodules = {};
    this.totalSubmodules = totalSubmodules;
    this.pos = pos;
    this.color = null;
    this.el = null; // element in DOM for module
    this.type = type; // tothem, info, payment, billing, admission, box, waiting room
    this.name = name;
    this.setColor();
    this.setElem();
};
MODULE.prototype.setColor = function () {
    switch (this.type) {
        case 'tothem': // tothem
            this.color = '#489248';
            break;
        case 'info': // informaciones
            this.color = '#5A807E';
            break;
        case 'payment': // caja
            this.color = '#83698A';
            break;
        case 'billing': // facturación
            this.color = '#644C4C';
            break;
        case 'admission': // admisión
            this.color = '#2C665D';
        case 'box': // box médico
            this.color = '#518CAD';
            break;
        case 'waiting-room': // sala de espera
            this.color = '#818878';
            break;
    }
};
MODULE.prototype.setElem = function () { // element in DOM for module
    if (this.type === 'waiting-room') {
        var x = ($(window).width() / 2) - (400 / 2),
            y = ($(window).height() / 2) - (400 / 2);
        this.el = PAPER.rect(x, y, 400, 400).attr({
            'fill': this.color,
            'stroke': this.color,
            'stroke-width': 10,
            'stroke-linejoin': 'round'
        });        
    } else {
        switch (this.pos) {
            case 'top':
//                var w = $(window).width() / 4,
                var w = this.totalSubmodules * 40,
                    x = ($(window).width() / 2) - (w / 2);
                this.el = PAPER.rect(x, 5, w, 100).attr({
                    'fill': this.color,
                    'stroke': this.color,
                    'stroke-width': 10,
                    'stroke-linejoin': 'round'
                });
                break;
            case 'left':
                var h = this.totalSubmodules * 40,
                    y = ($(window).height() / 2) - (h / 2);
                this.el = PAPER.rect(5, y, 100, h).attr({
                    'fill': this.color,
                    'stroke': this.color,
                    'stroke-width': 10,
                    'stroke-linejoin': 'round'
                });
                break;
            case 'bot':
                var w = this.totalSubmodules * 40,
                    x = ($(window).width() / 2) - (w / 2),
                    y = $(window).height() - 105;
                this.el = PAPER.rect(x, y, w, 100).attr({
                    'fill': this.color,
                    'stroke': this.color,
                    'stroke-width': 10,
                    'stroke-linejoin': 'round'
                });
                break;
            case 'right':
                var h = this.totalSubmodules * 40,
                    x = $(window).width() - 105,
                    y = ($(window).height() / 2) - (h / 2);
                this.el = PAPER.rect(x, y, 100, h).attr({
                    'fill': this.color,
                    'stroke': this.color,
                    'stroke-width': 10,
                    'stroke-linejoin': 'round'
                });
                break;
            case 'top-left':
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 110 + (40 * (this.totalSubmodules / 2)),
                            h = 110 + (40 * (this.totalSubmodules / 2));
                    } else {
                        var totalH = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalW = totalH - 1,
                            w = 110 + (40 * totalW),
                            h = 110 + (40 * totalH);
                    }                    
                    var p = 'M5,5L'+ w +',5L'+ w +',100L100,100L100,'+ h +'L5,'+ h +'Z';
                } else {
                    var w = 200,
                        h = 200,
                        p = 'M5,5L'+ w +',5L5,'+ h +'Z';
                }
                this.el = PAPER.path(p).attr({
                    'fill': this.color,
                    'stroke': this.color,
                    'stroke-width': 10,
                    'stroke-linejoin': 'round'
                });
                break;
            case 'top-right':                
                var w = $(window).width() / 4,
                    h = $(window).height() / 4,
                    x = $(window).width() - w,
                    fx = $(window).width() - 5,
                    p = 'M'+ x +',5L'+ fx +',5 L'+ fx +','+ h +'L'+ (fx - 100) +','+ h +'L'+ (fx - 100) +',100L'+ x +',100Z'; // path
                this.el = PAPER.path(p).attr({
                    'fill': this.color,
                    'stroke': this.color,
                    'stroke-width': 10,
                    'stroke-linejoin': 'round'
                });
                break;
            case 'bot-left':
                var w = $(window).width() / 4,
                    h = $(window).height() / 4,
                    y = $(window).height() - h,
                    fy = $(window).height() - 5,
                    p = 'M5,'+ y +'L5,'+ fy +'L'+ w +','+ fy +'L'+ w +','+ (fy - 100) +'L100,'+ (fy - 100) +'L100,'+ y +'Z';
                this.el = PAPER.path(p).attr({
                    'fill': this.color,
                    'stroke': this.color,
                    'stroke-width': 10,
                    'stroke-linejoin': 'round'
                });
                break;
            case 'bot-right':
                var w = $(window).width() / 4,
                    h = $(window).height() / 4,
                    x = $(window).width() - w,
                    y = $(window).height() - 5,
                    by = $(window).height() - h,
                    fx = $(window).width() - 5,                    
                    p = 'M'+ x +','+ y +'L'+ fx +','+ y +'L'+ fx +','+ by +'L'+ (fx - 100) +','+ by +'L'+ (fx - 100) +','+ (y - 100) +'L'+ x +','+ (y - 100) +'Z';
                this.el = PAPER.path(p).attr({
                    'fill': this.color,
                    'stroke': this.color,
                    'stroke-width': 10,
                    'stroke-linejoin': 'round'
                });
                break;
        }
    }    
};