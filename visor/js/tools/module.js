var MODULE = function (name, id, type, pos, color, shape, submodules, seats) {
    this.id = id;    
    if (type === 'module') {
        this.submodules = {};
        this.totalSubmodules = submodules.length;
    } else if (type === 'waiting-room') {
        this.submodules = null;
        this.maxSeats = seats; // max seats per module 
        this.seats = 60; // total seats per module
        this.seatsPos = [];
        this.textMaxSeats = null;
        this.textMsgMaxSeats = null;
    } else if (type === 'limb') {
        this.submodules = null;
        this.places = 24;
        this.placesPos = [];
    }
    this.pos = pos;
    this.color = color;
    this.shape = shape;
    this.el = null; // element in DOM for module
    this.text = null; // element in DOM for text module
    this.type = type;
    this.name = name;
    this.submoduleWidth = 40;
    this.submoduleHeight = 90;
    this.moduleRound = 5;    
    this.setElem();
};
// attributes for modules except waiting room and limb
MODULE.prototype.attrs = function (color) {
    return {
        'fill': color,
        'stroke': this.setColor(color, -0.3),
        'stroke-width': 3,
        'stroke-linejoin': 'round'
    };
};
MODULE.prototype.textAttrs = function (color) {
    return {
        'fill': this.setColor(color, -0.3),
        'font-size': '14px'        
    };
};
MODULE.prototype.setColor = function (hex, lum) {
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
MODULE.prototype.setElem = function () { // element in DOM for module
    if (this.type === 'waiting-room') {
        var x = ($(window).width() / 2) - (400 / 2),
            y = (($(window).height() - 100) / 2) - (200 / 2);
        this.el = PAPER.rect(x, y, 400, 200, 10).attr(this.attrs(this.color));
        this.textMaxSeats = PAPER.text(x + 12, y + 10, this.maxSeats).attr(this.textAttrs(this.color));
        this.textMsgMaxSeats = PAPER.text(x + 50, y + 10, 'Se ha sobrepasado la cantidad máxima de pacientes').attr({
            'fill': 'red',
            'font-size': '11px',
            'text-anchor': 'start',
            'fill-opacity': 0
        });
        this.text = PAPER.text(x + (400 / 2), y + (200 - 12), this.name).attr(this.textAttrs(this.color));        
    } else if (this.type === 'limb') {
        var x = ($(window).width() / 2) - (400 / 2),
            y = (($(window).height() + 200) / 2) - (100 / 2) + 3;
        this.el = PAPER.rect(x, y, 400, 100, 10).attr(this.attrs(this.color));
        this.text = PAPER.text(x + (400 / 2), y + (100 - 12), this.name).attr(this.textAttrs(this.color));     
    } else {
        switch (this.pos) {
            case 'superior':
                var w = (this.totalSubmodules * this.submoduleWidth) + 20,
                    x = ($(window).width() / 2) - (w / 2) ;
                this.el = PAPER.rect(x, 5, w, this.submoduleHeight, this.moduleRound).attr(this.attrs(this.color));
                this.text = PAPER.text(x + (w / 2), this.submoduleHeight + 15, this.name).attr(this.textAttrs(this.color));
                break;
            case 'izquierda':
                var h = (this.totalSubmodules * this.submoduleWidth) + 20,
                    y = ($(window).height() / 2) - (h / 2);
                this.el = PAPER.rect(5, y, this.submoduleHeight, h, this.moduleRound).attr(this.attrs(this.color));
                this.text = PAPER.text(this.submoduleHeight + 15, y + (h / 2), this.name).attr(this.textAttrs(this.color));
                this.text.rotate(-90);
                break;
            case 'inferior':
                var w = (this.totalSubmodules * this.submoduleWidth) + 20,
                    x = ($(window).width() / 2) - (w / 2),
                    y = $(window).height() - 95;
                this.el = PAPER.rect(x, y, w, this.submoduleHeight, this.moduleRound).attr(this.attrs(this.color));
                this.text = PAPER.text(x + (w / 2), y - 10, this.name).attr(this.textAttrs(this.color));
                break;
            case 'derecha':
                var h = (this.totalSubmodules * this.submoduleWidth) + 20,
                    x = $(window).width() - 95,
                    y = ($(window).height() / 2) - (h / 2);
                this.el = PAPER.rect(x, y, this.submoduleHeight, h, this.moduleRound).attr(this.attrs(this.color));
                this.text = PAPER.text(x - 10, y + (h / 2), this.name).attr(this.textAttrs(this.color));
                this.text.rotate(90);
                break;
            case 'superior-izquierda':
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 90 + 10 + (40 * (this.totalSubmodules / 2)),
                            h = 90 + 10 + (40 * (this.totalSubmodules / 2));
                    } else {
                        var totalH = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalW = totalH - 1,
                            w = 90 + 10 + (40 * totalW),
                            h = 90 + 10 + (40 * totalH);
                    }                    
                    var xt = 95,
                        yt = 100,
                        rt = 0,
                        p = 'M5,5L'+ w +',5L'+ w +',90L90,90L90,'+ h +'L5,'+ h +'Z',
                        textAttributes = {
                            'fill': this.setColor(this.color, -0.3),
                            'font-size': '14px',
                            'text-anchor': 'start'
                        };
                } else {
                    var w = 200,
                        h = 200,
                        xt = (w / 2) + 5,
                        yt = (h / 2) + 15,
                        rt = -45,
                        p = 'M5,5L'+ w +',5L5,'+ h +'Z',
                        textAttributes = this.textAttrs(this.color);
                }
                this.el = PAPER.path(p).attr(this.attrs(this.color));
                this.text = PAPER.text(xt, yt, this.name).attr(textAttributes);
                this.text.rotate(rt, xt, yt);
                break;
            case 'superior-derecha':                
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 90 + 10 + (40 * (this.totalSubmodules / 2)) + 5,
                            h = 90 + 10 + (40 * (this.totalSubmodules / 2));
                    } else {
                        var totalW = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalH = totalW - 1,
                            w = 90 + (40 * totalW) + 5,
                            h = 90 + (40 * totalH);
                    }  
                    var bx = $(window).width() - 5,
                        fx = $(window).width() - w,
                        xt = bx - 100,
                        yt = h - 85,
                        rt = 90,
                        p = 'M'+ bx +',5L'+ fx +',5L'+ fx +',90L'+ (bx - 90) +',90L'+ (bx - 90) +','+ h +'L'+ bx +','+ h +'Z',
                        textAttributes = {
                            'fill': this.setColor(this.color, -0.3),
                            'font-size': '14px',
                            'text-anchor': 'start'
                        };
                } else {
                    var w = 200,
                        h = 200,
                        bx = $(window).width() - 5,
                        fx = $(window).width() - w,
                        xt = bx - 105,
                        yt = h - 90,
                        rt = 45,
                        p = 'M'+ bx +',5L'+ fx +',5L'+ bx +','+ h +'Z',
                        textAttributes = this.textAttrs(this.color);
                        
                }                
                this.el = PAPER.path(p).attr(this.attrs(this.color));
                this.text = PAPER.text(xt, yt, this.name).attr(textAttributes);
                this.text.rotate(rt, xt, yt);
                break;
            case 'inferior-izquierda':
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 90 + 10 + (40 * (this.totalSubmodules / 2)),
                            h = 90 + 10 + (40 * (this.totalSubmodules / 2)) + 5;
                    } else {
                        var totalH = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalW = totalH - 1,
                            w = 90 + 10 + (40 * totalW),
                            h = 90 + 10 + (40 * totalH) + 5;
                    }  
                    var by = $(window).height() - 5,
                        fy = $(window).height() + 5,
                        xt = w - 85,
                        yt = by - 100,
                        rt = 0,
                        p = 'M5,'+ by +'L'+ w +','+ by +'L'+ w +','+ (by - 90) +'L90,'+ (by - 90) +'L90,'+ (fy - h) +'L5,'+ (fy - h) +'Z',
                        textAttributes = {
                            'fill': this.setColor(this.color, -0.3),
                            'font-size': '14px',
                            'text-anchor': 'start'
                        };
                } else {
                    var w = 200,
                        h = 200,
                        by = $(window).height() - 5,
                        fy = $(window).height() - h,
                        xt = w - 90,
                        yt = by - 105,
                        rt = 45,
                        p = 'M5,'+ by +'L'+ w +','+ by +'L5,'+ fy +'Z',
                        textAttributes = this.textAttrs(this.color);
                }      
                this.el = PAPER.path(p).attr(this.attrs(this.color));
                this.text = PAPER.text(xt, yt, this.name).attr(textAttributes);
                this.text.rotate(rt, xt, yt);
                break;
            case 'inferior-derecha':
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 90 + 10 + (40 * (this.totalSubmodules / 2)) + 5,
                            h = 90 + 10 + (40 * (this.totalSubmodules / 2));
                    } else {
                        var totalW = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalH = totalW - 1,
                            w = 90 + 10 + (40 * totalW) + 5,
                            h = 90 + 10 + (40 * totalH);
                    }  
                    var bx = $(window).width() - 5,
                        by = $(window).height() - 5,
                        fx = $(window).width() - w,
                        fy = $(window).height() - h,
                        xt = bx - 100,
                        yt = by - 95,
                        rt = -90,
                        p = 'M'+ bx +','+ by +'L'+ fx +','+ by +'L'+ fx +','+ (by - 90) + 'L'+ (bx - 90) +','+ (by - 90) +'L'+ (bx - 90) +','+ fy +'L'+ bx +','+ fy +'Z',
                        textAttributes = {
                            'fill': this.setColor(this.color, -0.3),
                            'font-size': '14px',
                            'text-anchor': 'start'
                        };
                    this.el = PAPER.path(p).attr(this.attrs(this.color));
                    this.text = PAPER.text(xt, yt, this.name).attr(textAttributes);
                    this.text.rotate(rt, xt, yt);
                } else {
                    var w = 200,
                        h = 200,
                        bx = $(window).width() - 5,
                        by = $(window).height() - 5,
                        fx = $(window).width() - w,
                        fy = $(window).height() - h,
                        xt = bx - 105,
                        yt = by - 105,
                        rt = -45,
                        p = 'M'+ bx +','+ by +'L'+ fx +','+ by +'L'+ bx +','+ fy +'Z',
                        textAttributes = this.textAttrs(this.color);
                    this.el = PAPER.path(p).attr(this.attrs(this.color));
                    this.text = PAPER.text(xt, yt, this.name).attr(textAttributes);
                    this.text.rotate(rt, xt, yt);
                }         
                break;
        }
    }  
};
MODULE.prototype.setSeatsPos = function () {
    var max = 12, // max seats per line
        space = 30; // space between seats
        
    for (var i = 0, j = 0, k = 0; i < this.seats; i++) {
        if (j < max) {
            var data = {
                x: MODULES['wr'].el.attrs.x + 35 + (space * j),
                y: MODULES['wr'].el.attrs.y + 30 + (space * k),
                patient: null
            };
            j++;
            this.seatsPos[i] = data; 
        } else {
            j = 0;
            k++;
            i--;
        }             
    }
};
MODULE.prototype.setPlacesPos = function () {
    var max = 12, // max places per line
        space = 30; // max places per zone
    
    for (var i = 0, j = 0, k = 0; i < this.places; i++) {
        if (j < max) {
            var data = {
                x: MODULES['lb'].el.attrs.x + 35 + (space * j),
                y: MODULES['lb'].el.attrs.y + 30 + (space * k),
                patient: null
            };
            j++;
            this.placesPos[i] = data;        
        } else {
            j = 0;
            k++;
            i--;
        }    
    }
};