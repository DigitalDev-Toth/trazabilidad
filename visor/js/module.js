var MODULE = function (name, id, type, pos, color, totalSubmodules) {
    this.id = id;
    this.submodules = {};
    this.totalSubmodules = totalSubmodules;
    this.pos = pos;
    this.color = null;
    this.el = null; // element in DOM for module
    this.type = type; // tothem, info, payment, billing, admission, box, waiting room
    this.name = name;
    this.color = color;
    this.setElem();
    this.attrs();
    this.submoduleWidth;
    this.submoduleHeight;
    this.moduleRound;
};
// modules attributes for all moudles except waiting room
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
        //'stroke': this.setColor(color, -0.5)
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
    this.submoduleWidth = 40;
    this.submoduleHeight = 90;
    this.moduleRound = 5;
    if (this.type === 'waiting-room') {
        var x = ($(window).width() / 2) - (400 / 2),
            y = ($(window).height() / 2) - (300 / 2);
        this.el = PAPER.rect(x, y, 400, 300, 10).attr(this.attrs(this.color));        
    } else {
        switch (this.pos) {
            case 'top':
                var w = (this.totalSubmodules * this.submoduleWidth) + 20,
                    x = ($(window).width() / 2) - (w / 2);
                this.el = PAPER.rect(x, 5, w, this.submoduleHeight, this.moduleRound).attr(this.attrs(this.color));
                text = PAPER.text(x + (w / 2), this.submoduleHeight - 4, this.name).attr(this.textAttrs(this.color));
                break;
            case 'left':
                var h = (this.totalSubmodules * this.submoduleWidth) + 20,
                    y = ($(window).height() / 2) - (h / 2);
                this.el = PAPER.rect(5, y, this.submoduleHeight, h, this.moduleRound).attr(this.attrs(this.color));
                text = PAPER.text(this.submoduleHeight - 4, y + (h / 2), this.name).attr(this.textAttrs(this.color));
                text.rotate(-90);
                break;
            case 'bot':
                var w = (this.totalSubmodules * this.submoduleWidth) + 20,
                    x = ($(window).width() / 2) - (w / 2),
                    y = $(window).height() - 95;
                this.el = PAPER.rect(x, y, w, this.submoduleHeight, this.moduleRound).attr(this.attrs(this.color));
                text = PAPER.text(x + (w / 2), y + 10, this.name).attr(this.textAttrs(this.color));
                break;
            case 'right':
                var h = (this.totalSubmodules * this.submoduleWidth) + 20,
                    x = $(window).width() - 95,
                    y = ($(window).height() / 2) - (h / 2);
                this.el = PAPER.rect(x, y, this.submoduleHeight, h, this.moduleRound).attr(this.attrs(this.color));
                text = PAPER.text(x + 10, y + (h / 2), this.name).attr(this.textAttrs(this.color));
                text.rotate(90);
                break;
            case 'top-left':
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 100 + (40 * (this.totalSubmodules / 2)),
                            h = 100 + (40 * (this.totalSubmodules / 2));
                    } else {
                        var totalH = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalW = totalH - 1,
                            w = 100 + (40 * totalW),
                            h = 100 + (40 * totalH);
                    }                    
                    var p = 'M5,5L'+ w +',5L'+ w +',100L100,100L100,'+ h +'L5,'+ h +'Z';
                } else {
                    var w = 200,
                        h = 200,
                        p = 'M5,5L'+ w +',5L5,'+ h +'Z';
                }
                this.el = PAPER.path(p).attr(this.attrs(this.color));
                text = PAPER.text((w / 2) - 4, (h / 2) - 4, this.name).attr(this.textAttrs(this.color));
                text.rotate(-45);
                break;
            case 'top-right':   
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 100 + (40 * (this.totalSubmodules / 2)) + 5,
                            h = 100 + (40 * (this.totalSubmodules / 2));
                    } else {
                        var totalW = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalH = totalW - 1,
                            w = 100 + (40 * totalW) + 5,
                            h = 100 + (40 * totalH);
                    }  
                    var bx = $(window).width() - 5,
                        fx = $(window).width() - w,
                        p = 'M'+ bx +',5L'+ fx +',5L'+ fx +',100L'+ (bx - 100) +',100L'+ (bx - 100) +','+ h +'L'+ bx +','+ h +'Z';
                } else {
                    var w = 200,
                        h = 200,
                        bx = $(window).width() - 5,
                        fx = $(window).width() - w,
                        p = 'M'+ bx +',5L'+ fx +',5L'+ bx +','+ h +'Z';
                }                
                this.el = PAPER.path(p).attr(this.attrs(this.color));
                break;
            case 'bot-left':
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 100 + (40 * (this.totalSubmodules / 2)),
                            h = 100 + (40 * (this.totalSubmodules / 2)) + 5;
                    } else {
                        var totalH = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalW = totalH - 1,
                            w = 100 + (40 * totalW),
                            h = 100 + (40 * totalH) + 5;
                    }  
                    var by = $(window).height() - 5,
                        fy = $(window).height() + 5,
                        p = 'M5,'+ by +'L'+ w +','+ by +'L'+ w +','+ (by - 100) +'L100,'+ (by - 100) +'L100,'+ (fy - h) +'L5,'+ (fy - h) +'Z';
                } else {
                    var w = 200,
                        h = 200,
                        by = $(window).height() - 5,
                        fy = $(window).height() - h,
                        p = 'M5,'+ by +'L'+ w +','+ by +'L5,'+ fy +'Z';
                }      
                this.el = PAPER.path(p).attr(this.attrs(this.color));
                break;
            case 'bot-right':
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 100 + (40 * (this.totalSubmodules / 2)) + 5,
                            h = 100 + (40 * (this.totalSubmodules / 2));
                    } else {
                        var totalW = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalH = totalW - 1,
                            w = 100 + (40 * totalW) + 5,
                            h = 100 + (40 * totalH);
                    }  
                    var bx = $(window).width() - 5,
                        by = $(window).height() - 5,
                        fx = $(window).width() - w,
                        fy = $(window).height() - h,
                        p = 'M'+ bx +','+ by +'L'+ fx +','+ by +'L'+ fx +','+ (by - 100) + 'L'+ (bx - 100) +','+ (by - 100) +'L'+ (bx - 100) +','+ fy +'L'+ bx +','+ fy +'Z';
                } else {
                    var w = 200,
                        h = 200,
                        bx = $(window).width() - 5,
                        by = $(window).height() - 5,
                        fx = $(window).width() - w,
                        fy = $(window).height() - h,
                        p = 'M'+ bx +','+ by +'L'+ fx +','+ by +'L'+ bx +','+ fy +'Z';
                }                    
                this.el = PAPER.path(p).attr(this.attrs(this.color));
                break;
        }
    }    
};