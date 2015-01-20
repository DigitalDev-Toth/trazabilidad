var MODULE = function (name, id, type, dbType, pos, color, shape, waitingTime, submodules, seats) {
    this.id = id;    
    if (type === 'module') {
        this.dbType = parseInt(dbType);
        if (this.dbType === 1) {
            this.elTothtemInfo = null;
            this.totalTicketsIssued = null;
            this.ticketsTo = null;
            this.timeFirstTicket = null;
            this.timeLastTicket = null;
            this.ivTothtemInfo = null;
        } else {
            this.elInfo = null;
            this.attended = null;
            this.average = null;
            this.max = null;
            this.min = null;
            this.timeOn = null;
            this.ivInfo = null; 
        }
        this.submodules = {};
        this.totalSubmodules = submodules.length;
        this.totalSubmodulesInactive = 0;
        this.maxWaitingTime = waitingTime;
        this.beginWaitingTime = waitingTime * 60000 * 1.2;
        this.finalWaitingTime = waitingTime * 60000 * 1.5;
        this.shape = shape;
        this.submoduleWidth = 60;
        this.submoduleHeight = 100;
        this.moduleRound = 5;   
    } else if (type === 'waiting-room') {
        this.elwrInfo = null;
        this.wrAverage = null;
        this.wrMax = null;
        this.wrMin = null;
        this.maxSeats = seats; // max seats per module 
        this.seats = 60; // total seats per module
        this.seatsCount = 0;
        this.seatsPos = [];
        this.textMaxSeats = null;
        this.textMsgMaxSeats = null;
        this.timeOn = null;
        this.ivwrInfo = null;
    } else if (type === 'limb') {
//        this.places = 24;
//        this.placesPos = [];
    }
    this.color = color;
    this.pos = pos;
    this.el = null; // element in DOM for module
    this.text = null; // element in DOM for text module
    this.elTop = null;
    this.type = type;
    this.name = name;  
    this.setElem();
    if (type === 'module') {
        this.elTop.node.id = 'm'+ this.id;
    }
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
        'font-size': '16px',
        'font-weight': 'bold'
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
        var x = ($(window).width() / 2) - (500 / 2),
            y = (($(window).height() - 100) / 2) - (200 / 2);
        this.el = PAPER.rect(x, y, 500, 200, 10).attr(this.attrs(this.color));
        this.textMaxSeats = PAPER.text(x + 18, y + 10, this.maxSeats).attr(this.textAttrs(this.color));
        this.textMsgMaxSeats = PAPER.text(x + 50, y + 10, 'Se ha sobrepasado la cantidad máxima de pacientes').attr({
            'fill': 'red',
            'font-size': '12px',
            'text-anchor': 'start',
            'fill-opacity': 0
        });
        this.text = PAPER.text(x + (500 / 2), y + (200 - 12), this.name).attr(this.textAttrs(this.color));        
    } else if (this.type === 'limb') {
        var x = ($(window).width() / 2),
            y = (($(window).height() + 200) / 2) + 3;
        this.el = PAPER.circle(x, y, 30).attr(this.attrs(this.color));
        this.text = PAPER.text(x, y, this.name).attr(this.textAttrs(this.color));     
    } else {
        switch (this.pos) {
            case 'superior':
                var w = (this.totalSubmodules * this.submoduleWidth) + 20,
                    x = ($(window).width() / 2) - (w / 2) ;
                this.el = PAPER.rect(x, 5, w, this.submoduleHeight, this.moduleRound).attr(this.attrs(this.color));
                this.text = PAPER.text(x + (w / 2), this.submoduleHeight + 15, this.name).attr(this.textAttrs(this.color));
                this.elTop = PAPER.rect(x, 5, w, this.submoduleHeight, this.moduleRound).attr({'fill': 'red', 'fill-opacity': '0', 'stroke-width': '0'});
                break;
            case 'izquierda':
                var h = (this.totalSubmodules * this.submoduleWidth) + 20,
                    y = ($(window).height() / 2) - (h / 2);
                this.el = PAPER.rect(5, y, this.submoduleHeight, h, this.moduleRound).attr(this.attrs(this.color));
                this.text = PAPER.text(this.submoduleHeight + 15, y + (h / 2), this.name).attr(this.textAttrs(this.color));
                this.text.rotate(-90);
                this.elTop = PAPER.rect(5, y, this.submoduleHeight, h, this.moduleRound).attr({'fill': 'red', 'fill-opacity': '0', 'stroke-width': '0'});
                break;
            case 'inferior':
                var w = (this.totalSubmodules * this.submoduleWidth) + 20,
                    x = ($(window).width() / 2) - (w / 2),
                    y = $(window).height() - 95;
                this.el = PAPER.rect(x, y, w, this.submoduleHeight, this.moduleRound).attr(this.attrs(this.color));
                this.text = PAPER.text(x + (w / 2), y - 10, this.name).attr(this.textAttrs(this.color));
                this.elTop = PAPER.rect(x, y, w, this.submoduleHeight, this.moduleRound).attr({'fill': 'red', 'fill-opacity': '0', 'stroke-width': '0'});
                break;
            case 'derecha':
                var h = (this.totalSubmodules * this.submoduleWidth) + 20,
                    x = $(window).width() - 105,
                    y = ($(window).height() / 2) - (h / 2);
                this.el = PAPER.rect(x, y, this.submoduleHeight, h, this.moduleRound).attr(this.attrs(this.color));
                this.text = PAPER.text(x - 10, y + (h / 2), this.name).attr(this.textAttrs(this.color));
                this.text.rotate(90);
                this.elTop = PAPER.rect(x, y, this.submoduleHeight, h, this.moduleRound).attr({'fill': 'red', 'fill-opacity': '0', 'stroke-width': '0'});
                break;
            case 'superior-izquierda':
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 105 + 10 + (60 * (this.totalSubmodules / 2)),
                            h = 105 + 10 + (60 * (this.totalSubmodules / 2));
                    } else {
                        var totalH = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalW = totalH - 1,
                            w = 105 + 10 + (60 * totalW),
                            h = 105 + 10 + (60 * totalH);
                    }                    
                    var xt = 110,
                        yt = 115,
                        rt = 0,
                        p = 'M5,5L'+ w +',5L'+ w +',105L105,105L105,'+ h +'L5,'+ h +'Z',
                        textAttributes = {
                            'fill': this.setColor(this.color, -0.3),
                            'font-size': '16px',
                            'font-weight': 'bold',
                            'text-anchor': 'start'
                        };
                } else {
                    if (this.totalSubmodules === 1) {
                        var w = 180,
                            h = 180,
                            xt = (w / 2) + 10,
                            yt = (h / 2) + 10,
                            rt = -45,
                            p = 'M5,5L'+ w +',5L5,'+ h +'Z',
                            textAttributes = this.textAttrs(this.color);
                    } else if (this.totalSubmodules === 2) {
                        var w = 220,
                            h = 220,
                            xt = (w / 2) + 10,
                            yt = (h / 2) + 10,
                            rt = -45,
                            p = 'M5,5L'+ w +',5L5,'+ h +'Z',
                            textAttributes = this.textAttrs(this.color);
                    } else if (this.totalSubmodules === 3) {
                        var w = 260,
                            h = 260,
                            xt = (w / 2) + 10,
                            yt = (h / 2) + 10,
                            rt = -45,
                            p = 'M5,5L'+ w +',5L5,'+ h +'Z',
                            textAttributes = this.textAttrs(this.color);
                    }
                }
                this.el = PAPER.path(p).attr(this.attrs(this.color));
                this.text = PAPER.text(xt, yt, this.name).attr(textAttributes);
                this.text.rotate(rt, xt, yt);
                this.elTop = PAPER.path(p).attr({'fill': 'red', 'fill-opacity': '0', 'stroke-width': '0'});
                break;
            case 'superior-derecha':                
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 105 + 10 + (60 * (this.totalSubmodules / 2)) + 5,
                            h = 105 + 10 + (60 * (this.totalSubmodules / 2));
                    } else {
                        var totalW = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalH = totalW - 1,
                            w = 115 + (60 * totalW) + 5,
                            h = 115 + (60 * totalH);
                    }  
                    var bx = $(window).width() - 5,
                        fx = $(window).width() - w,
                        xt = bx - 117,
                        yt = 110,
                        rt = 90,
                        p = 'M'+ bx +',5L'+ fx +',5L'+ fx +',105L'+ (bx - 105) +',105L'+ (bx - 105) +','+ h +'L'+ bx +','+ h +'Z',
                        textAttributes = {
                            'fill': this.setColor(this.color, -0.3),
                            'font-size': '16px',
                            'font-weight': 'bold',
                            'text-anchor': 'start'
                        };
                } else {
                    if (this.totalSubmodules === 1) {
                        var w = 180,
                            h = 180,
                            bx = $(window).width() - 5,
                            fx = $(window).width() - w,
                            xt = bx - 95,
                            yt = h - 80,
                            rt = 45,
                            p = 'M'+ bx +',5L'+ fx +',5L'+ bx +','+ h +'Z',
                            textAttributes = this.textAttrs(this.color);
                    } else if (this.totalSubmodules === 2) {
                        var w = 220,
                            h = 220,
                            bx = $(window).width() - 5,
                            fx = $(window).width() - w,
                            xt = bx - 115,
                            yt = h - 100,
                            rt = 45,
                            p = 'M'+ bx +',5L'+ fx +',5L'+ bx +','+ h +'Z',
                            textAttributes = this.textAttrs(this.color);
                    } else if (this.totalSubmodules === 3) {
                        var w = 260,
                            h = 260,
                            bx = $(window).width() - 5,
                            fx = $(window).width() - w,
                            xt = bx - 135,
                            yt = h - 120,
                            rt = 45,
                            p = 'M'+ bx +',5L'+ fx +',5L'+ bx +','+ h +'Z',
                            textAttributes = this.textAttrs(this.color);
                    }                        
                }                
                this.el = PAPER.path(p).attr(this.attrs(this.color));
                this.text = PAPER.text(xt, yt, this.name).attr(textAttributes);
                this.text.rotate(rt, xt, yt);
                this.elTop = PAPER.path(p).attr({'fill': 'red', 'fill-opacity': '0', 'stroke-width': '0'});
                break;
            case 'inferior-izquierda':
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 105 + 10 + (60 * (this.totalSubmodules / 2)),
                            h = 110 + 10 + (60 * (this.totalSubmodules / 2)) + 5;
                    } else {
                        var totalH = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalW = totalH - 1,
                            w = 105 + 10 + (60 * totalW),
                            h = 105 + 10 + (60 * totalH) + 5;
                    }  
                    var by = $(window).height() - 5,
                        fy = $(window).height() + 5,
                        xt = 110,
                        yt = by - 117,
                        rt = 0,
                        p = 'M5,'+ by +'L'+ w +','+ by +'L'+ w +','+ (by - 105) +'L105,'+ (by - 105) +'L105,'+ (fy - h) +'L5,'+ (fy - h) +'Z',
                        textAttributes = {
                            'fill': this.setColor(this.color, -0.3),
                            'font-size': '16px',
                            'font-weight': 'bold',
                            'text-anchor': 'start'
                        };
                } else {
                    if (this.totalSubmodules === 1) {
                        var w = 180,
                            h = 180,
                            by = $(window).height() - 5,
                            fy = $(window).height() - h,
                            xt = w - 82,
                            yt = by - 97,
                            rt = 45,
                            p = 'M5,'+ by +'L'+ w +','+ by +'L5,'+ fy +'Z',
                            textAttributes = this.textAttrs(this.color);
                    } else if (this.totalSubmodules === 2) {
                        var w = 220,
                            h = 220,
                            by = $(window).height() - 5,
                            fy = $(window).height() - h,
                            xt = w - 102,
                            yt = by - 117,
                            rt = 45,
                            p = 'M5,'+ by +'L'+ w +','+ by +'L5,'+ fy +'Z',
                            textAttributes = this.textAttrs(this.color);
                    } else if (this.totalSubmodules === 3) {
                        var w = 260,
                            h = 260,
                            by = $(window).height() - 5,
                            fy = $(window).height() - h,
                            xt = w - 120,
                            yt = by - 135,
                            rt = 45,
                            p = 'M5,'+ by +'L'+ w +','+ by +'L5,'+ fy +'Z',
                            textAttributes = this.textAttrs(this.color);
                    }
                }      
                this.el = PAPER.path(p).attr(this.attrs(this.color));
                this.text = PAPER.text(xt, yt, this.name).attr(textAttributes);
                this.text.rotate(rt, xt, yt);
                this.elTop = PAPER.path(p).attr({'fill': 'red', 'fill-opacity': '0', 'stroke-width': '0'});
                break;
            case 'inferior-derecha':
                if (this.totalSubmodules >= 4) {
                    if ((this.totalSubmodules % 2) === 0) {
                        var w = 105 + 10 + (60 * (this.totalSubmodules / 2)) + 5,
                            h = 110 + 10 + (60 * (this.totalSubmodules / 2));
                    } else {
                        var totalW = parseInt((this.totalSubmodules / 2).toFixed(0)),
                            totalH = totalW - 1,
                            w = 105 + 10 + (60 * totalW) + 5,
                            h = 110 + 10 + (60 * totalH);
                    }  
                    var bx = $(window).width() - 5,
                        by = $(window).height() - 5,
                        fx = $(window).width() - w,
                        fy = $(window).height() - h,
                        xt = bx - 117,
                        yt = by - 110,
                        rt = -90,
                        p = 'M'+ bx +','+ by +'L'+ fx +','+ by +'L'+ fx +','+ (by - 105) + 'L'+ (bx - 105) +','+ (by - 105) +'L'+ (bx - 105) +','+ fy +'L'+ bx +','+ fy +'Z',
                        textAttributes = {
                            'fill': this.setColor(this.color, -0.3),
                            'font-size': '16px',
                            'font-weight': 'bold',
                            'text-anchor': 'start'
                        };
                    this.el = PAPER.path(p).attr(this.attrs(this.color));
                    this.text = PAPER.text(xt, yt, this.name).attr(textAttributes);
                    this.text.rotate(rt, xt, yt);
                    this.elTop = PAPER.path(p).attr({'fill': 'red', 'fill-opacity': '0', 'stroke-width': '0'});
                } else {
                    if (this.totalSubmodules === 1) {
                        var w = 180,
                            h = 180,
                            bx = $(window).width() - 5,
                            by = $(window).height() - 5,
                            fx = $(window).width() - w,
                            fy = $(window).height() - h,
                            xt = bx - 97,
                            yt = by - 97,
                            rt = -45,
                            p = 'M'+ bx +','+ by +'L'+ fx +','+ by +'L'+ bx +','+ fy +'Z',
                            textAttributes = this.textAttrs(this.color);                            
                    } else if (this.totalSubmodules === 2) {
                        var w = 220,
                            h = 220,
                            bx = $(window).width() - 5,
                            by = $(window).height() - 5,
                            fx = $(window).width() - w,
                            fy = $(window).height() - h,
                            xt = bx - 117,
                            yt = by - 117,
                            rt = -45,
                            p = 'M'+ bx +','+ by +'L'+ fx +','+ by +'L'+ bx +','+ fy +'Z',
                            textAttributes = this.textAttrs(this.color);   
                    } else if (this.totalSubmodules === 3) {
                        var w = 260,
                            h = 260,
                            bx = $(window).width() - 5,
                            by = $(window).height() - 5,
                            fx = $(window).width() - w,
                            fy = $(window).height() - h,
                            xt = bx - 137,
                            yt = by - 137,
                            rt = -45,
                            p = 'M'+ bx +','+ by +'L'+ fx +','+ by +'L'+ bx +','+ fy +'Z',
                            textAttributes = this.textAttrs(this.color);  
                    }
                    this.el = PAPER.path(p).attr(this.attrs(this.color));
                    this.text = PAPER.text(xt, yt, this.name).attr(textAttributes);
                    this.text.rotate(rt, xt, yt);
                    this.elTop = PAPER.path(p).attr({'fill': 'red', 'fill-opacity': '0', 'stroke-width': '0'});
                }         
                break;
        }
    }  
};
MODULE.prototype.setSeatsPos = function () {
    var max = 14, // max seats per line
        space = 33; // space between seats
        
    for (var i = 0, j = 0, k = 0; i < this.seats; i++) {
        if (j < max) {
            var data = {
                x: MODULES['wr'].el.attrs.x + 35 + (space * j),
                y: MODULES['wr'].el.attrs.y + 35 + (space * k),
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
//MODULE.prototype.setPlacesPos = function () {
//    var max = 12, // max places per line
//        space = 30; // max places per zone
//    
//    for (var i = 0, j = 0, k = 0; i < this.places; i++) {
//        if (j < max) {
//            var data = {
//                x: MODULES['lb'].el.attrs.x + 35 + (space * j),
//                y: MODULES['lb'].el.attrs.y + 30 + (space * k),
//                patient: null
//            };
//            j++;
//            this.placesPos[i] = data;        
//        } else {
//            j = 0;
//            k++;
//            i--;
//        }    
//    }
//};
MODULE.prototype.seatsCountAndTimeWaiting = function () {
    setInterval((function (t) {
        return function () {
            var count = 0;
            var array = [];
            for (var i = 0; i < t.seats; i++) {
                if (t.seatsPos[i].patient !== null) {                    
                    if ((new Date().getTime() - PATIENTS[t.seatsPos[i].patient].datetime) >= MODULES[PATIENTS[t.seatsPos[i].patient].idModule].beginWaitingTime && 
                        (new Date().getTime() - PATIENTS[t.seatsPos[i].patient].datetime) < MODULES[PATIENTS[t.seatsPos[i].patient].idModule].finalWaitingTime) {
                        PATIENTS[t.seatsPos[i].patient].el.animate({
                            'fill': 'yellow'
                        }, 1000);
                    } else if ((new Date().getTime() - PATIENTS[t.seatsPos[i].patient].datetime) >= MODULES[PATIENTS[t.seatsPos[i].patient].idModule].finalWaitingTime) {
                        PATIENTS[t.seatsPos[i].patient].el.animate({
                            'fill': 'red'
                        }, 1000);
                    }
                    count++;
                    array.push(new Date().getTime() - PATIENTS[t.seatsPos[i].patient].datetime);
                }                
            }
            t.seatsCount = count;
            t.wrMax = Math.max.apply(null, array);
            t.wrMin = Math.min.apply(null, array);
            var sum = 0;
            for (var i = 0; i < t.seatsCount; i++) {
                sum = sum + array[i];
            }
            t.wrAverage = sum / t.seatsCount;
            
            if (count >= t.maxSeats) {
                t.textMsgMaxSeats.attr({
                    'fill-opacity': 1
                });
            } else {
                t.textMsgMaxSeats.attr({
                    'fill-opacity': 0
                });
            }
        };        
    })(this), 1000);
};
MODULE.prototype.tothtemInfo = function (totalTicketsIssued, ticketsTo, timeFirstTicket, timeLastTicket) {
    this.elTothtemInfo = $('<div></div>');
    this.totalTicketsIssued = totalTicketsIssued;
    this.ticketsTo = ticketsTo;
    this.timeFirstTicket = new Date(timeFirstTicket).getTime();
    this.timeLastTicket = new Date(timeLastTicket).getTime();
    
    var elTothtemInfo = this.elTothtemInfo;
    this.elTop.toFront();
    $(this.elTop.node).tothtip(elTothtemInfo);
};
MODULE.prototype.tooltipTothtemInfo = function () {
    this.ivTothtemInfo = setInterval((function (t) {
        return function () {
            if (new Date(t.timeFirstTicket).getHours() < 10) {
                var timeFirstTicketHours = '0'+ new Date(t.timeFirstTicket).getHours();
            } else {
                var timeFirstTicketHours = new Date(t.timeFirstTicket).getHours();
            }
            if (new Date(t.timeFirstTicket).getMinutes() < 10) {
                var timeFirstTicketMinutes = '0'+ new Date(t.timeFirstTicket).getMinutes();
            } else {
                var timeFirstTicketMinutes = new Date(t.timeFirstTicket).getMinutes();
            }
            if (new Date(t.timeFirstTicket).getSeconds() < 10) {
                var timeFirstTicketSeconds = '0'+ new Date(t.timeFirstTicket).getSeconds();
            } else {
                var timeFirstTicketSeconds = new Date(t.timeFirstTicket).getSeconds();
            }
            
            if (new Date(t.timeLastTicket).getHours() < 10) {
                var timeLastTicketHours = '0'+ new Date(t.timeLastTicket).getHours();
            } else {
                var timeLastTicketHours = new Date(t.timeLastTicket).getHours();
            }
            if (new Date(t.timeLastTicket).getMinutes() < 10) {
                var timeLastTicketMinutes = '0'+ new Date(t.timeLastTicket).getMinutes();
            } else {
                var timeLastTicketMinutes = new Date(t.timeLastTicket).getMinutes();
            }
            if (new Date(t.timeLastTicket).getSeconds() < 10) {
                var timeLastTicketSeconds = '0'+ new Date(t.timeLastTicket).getSeconds();
            } else {
                var timeLastTicketSeconds = new Date(t.timeLastTicket).getSeconds();
            }
            
            var timeFirstTicket = timeFirstTicketHours +':'+ timeFirstTicketMinutes +':'+ timeFirstTicketSeconds;
            var timeLastTicket = timeLastTicketHours +':'+ timeLastTicketMinutes +':'+ timeLastTicketSeconds;
            
            var content = '<u>Total tickets atendidos</u>: '+ t.totalTicketsIssued +'<br />';
            var ticketsTo = t.ticketsTo;
            $.each(ticketsTo, function(index, value) {
                content += '<u>Tickets a '+ MODULES[parseInt(index)].name +'</u>: '+ value +'<br />';
            });            
            content += '<u>Hora primer ticket</u>: '+ timeFirstTicket +'<br />';
            content += '<u>Hora último ticket</u>: '+ timeLastTicket +'<br />';                    
            t.elTothtemInfo.html(content);
        };
    })(this), 1000);
};
MODULE.prototype.info = function (attended, average, max, min) {
    this.elInfo = $('<div></div>');
    this.attended = attended;
    this.average = new Date(average).getTime() - new Date(average).setHours(0, 0, 0);
    this.max = new Date(max).getTime() - new Date(max).setHours(0, 0, 0);
    this.min = new Date(min).getTime() - new Date(min).setHours(0, 0, 0);
    
    var elInfo = this.elInfo;
    $(this.elTop.node).tothtip(elInfo);
};
MODULE.prototype.tooltipInfo = function () {
    this.ivInfo = setInterval((function (t) {
        return function () {            
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

            var average = averageHours +':'+ averageMinutes +':'+ averageSeconds;
            var max = maxHours +':'+ maxMinutes +':'+ maxSeconds;
            var min = minHours +':'+ minMinutes +':'+ minSeconds;

            var content = '<u>Pacientes atendidos</u>: '+ t.attended +'<br />'
                            +'<u>Promedio</u>: '+ average +'<br />'                    
                            +'<u>Máximo</u>: '+ max +'<br />'
                            +'<u>Mínimo</u>: '+ min;
            t.elInfo.html(content);           
        };
    })(this), 1000);
};
MODULE.prototype.setTimeOn = function (timeOn) {
    this.timeOn = timeOn;
    
    if (this.timeOn > this.max) {
        this.max = this.timeOn;
    } else if (this.timeOn < this.min) {
        this.min = this.timeOn;
    }
    
    this.average = ((this.average * (this.attended - 1)) + this.timeOn) / this.attended;
};
MODULE.prototype.wrInfo = function () {
    var total = PAPER.text(this.el.attrs.x + 20, this.el.attrs.y - 15, 'Total').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    var totalBBox = total.getBBox();
    PAPER.path('M'+ totalBBox.x +' '+ (totalBBox.y + totalBBox.height) +'L'+ (totalBBox.x + totalBBox.width) +' '+ (totalBBox.y + totalBBox.height)).attr({
        'fill': '#333',
        'stroke': '#333',
        'stroke-width': 1,
        'stroke-linejoin': 'round'
    });
    PAPER.text(this.el.attrs.x + 20 + 16, this.el.attrs.y - 15, ':').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    this.totalData = PAPER.text(this.el.attrs.x + 20 + 25, this.el.attrs.y - 15, this.seatsCount).attr({
        'fill': '#333',
        'font-size': '12px'
    });
    PAPER.text(this.el.attrs.x + 20 + 35, this.el.attrs.y - 15, ' - ').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    var average = PAPER.text(this.el.attrs.x + 20 + 68, this.el.attrs.y - 15, 'Promedio').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    var averageBBox = average.getBBox();
    PAPER.path('M'+ averageBBox.x +' '+ (averageBBox.y + averageBBox.height) +'L'+ (averageBBox.x + averageBBox.width) +' '+ (averageBBox.y + averageBBox.height)).attr({
        'fill': '#333',
        'stroke': '#333',
        'stroke-width': 1,
        'stroke-linejoin': 'round'
    });
    PAPER.text(this.el.attrs.x + 20 + 96, this.el.attrs.y - 15, ':').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    this.averageData = PAPER.text(this.el.attrs.x + 20 + 125, this.el.attrs.y - 15, '00:00:00').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    PAPER.text(this.el.attrs.x + 20 + 155, this.el.attrs.y - 15, ' - ').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    var max = PAPER.text(this.el.attrs.x + 20 + 183, this.el.attrs.y - 15, 'Máximo').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    var maxBBox = max.getBBox();
    PAPER.path('M'+ maxBBox.x +' '+ (maxBBox.y + maxBBox.height) +'L'+ (maxBBox.x + maxBBox.width) +' '+ (maxBBox.y + maxBBox.height)).attr({
        'fill': '#333',
        'stroke': '#333',
        'stroke-width': 1,
        'stroke-linejoin': 'round'
    });
    PAPER.text(this.el.attrs.x + 20 + 205, this.el.attrs.y - 15, ':').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    this.maxData = PAPER.text(this.el.attrs.x + 20 + 232, this.el.attrs.y - 15, '00:00:00').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    PAPER.text(this.el.attrs.x + 20 + 260, this.el.attrs.y - 15, ' - ').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    var min = PAPER.text(this.el.attrs.x + 20 + 285, this.el.attrs.y - 15, 'Mínimo').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    var minBBox = min.getBBox();
    PAPER.path('M'+ minBBox.x +' '+ (minBBox.y + minBBox.height) +'L'+ (minBBox.x + minBBox.width) +' '+ (minBBox.y + minBBox.height)).attr({
        'fill': '#333',
        'stroke': '#333',
        'stroke-width': 1,
        'stroke-linejoin': 'round'
    });
    PAPER.text(this.el.attrs.x + 20 + 307, this.el.attrs.y - 15, ':').attr({
        'fill': '#333',
        'font-size': '12px'
    });
    this.minData = PAPER.text(this.el.attrs.x + 20 + 335, this.el.attrs.y - 15, '00:00:00').attr({
        'fill': '#333',
        'font-size': '12px'
    });
};
MODULE.prototype.wrElem = function () {
    this.ivwrInfo = setInterval((function (t) {
        return function () {
            if (t.seatsCount > 0) {
                if (Math.floor(((t.wrAverage / 1000) / 60) / 60) < 10) {
                    var averageHours = '0'+ Math.floor(((t.wrAverage / 1000) / 60) / 60);
                } else {
                    var averageHours = Math.floor(((t.wrAverage / 1000) / 60) / 60);
                }

                if (new Date(t.wrAverage).getMinutes() < 10) {
                    var averageMinutes = '0'+ new Date(t.wrAverage).getMinutes();
                } else {
                    var averageMinutes = new Date(t.wrAverage).getMinutes();
                }

                if (new Date(t.wrAverage).getSeconds() < 10) {
                    var averageSeconds = '0'+ new Date(t.wrAverage).getSeconds();
                } else {
                    var averageSeconds = new Date(t.wrAverage).getSeconds();
                }

                if (Math.floor(((t.wrMax / 1000) / 60) / 60) < 10) {
                    var maxHours = '0'+ Math.floor(((t.wrMax / 1000) / 60) / 60);
                } else {
                    var maxHours = Math.floor(((t.wrMax / 1000) / 60) / 60);
                }            
                if (new Date(t.wrMax).getMinutes() < 10) {
                    var maxMinutes = '0'+ new Date(t.wrMax).getMinutes();
                } else {
                    var maxMinutes = new Date(t.wrMax).getMinutes();
                }            
                if (new Date(t.wrMax).getSeconds() < 10) {
                    var maxSeconds = '0'+ new Date(t.wrMax).getSeconds();
                } else {
                    var maxSeconds = new Date(t.wrMax).getSeconds();
                }

                if (Math.floor(((t.wrMin / 1000) / 60) / 60) < 10) {
                    var minHours = '0'+ Math.floor(((t.wrMin / 1000) / 60) / 60);
                } else {
                    var minHours = Math.floor(((t.wrMin / 1000) / 60) / 60);
                }            
                if (new Date(t.wrMin).getMinutes() < 10) {
                    var minMinutes = '0'+ new Date(t.wrMin).getMinutes();
                } else {
                    var minMinutes = new Date(t.wrMin).getMinutes();
                }            
                if (new Date(t.wrMin).getSeconds() < 10) {
                    var minSeconds = '0'+ new Date(t.wrMin).getSeconds();
                } else {
                    var minSeconds = new Date(t.wrMin).getSeconds();
                }
                
                var average = averageHours +':'+ averageMinutes +':'+ averageSeconds;
                var max = maxHours +':'+ maxMinutes +':'+ maxSeconds;
                var min = minHours +':'+ minMinutes +':'+ minSeconds;
            } else {
                var average = '00:00:00';
                var max = '00:00:00';
                var min = '00:00:00';
            }     
            
            t.totalData.attr('text', t.seatsCount);
            t.averageData.attr('text', average);
            t.maxData.attr('text', max);
            t.minData.attr('text', min);
        };
    })(this), 1000);
};