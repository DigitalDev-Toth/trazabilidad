var MAKER = function () {
    this.countSubmodules = 0;
};
MAKER.prototype.module = function (name, id, type, pos, color, shape, submodules, seats) {
    var m = new MODULE(name, id, type, pos, color, shape, submodules, seats); 
    if (type === 'waiting-room') {
        MODULES['wr'] = m;
        MODULES['wr'].setSeatsPos();
        setInterval(function () {
            var count = 0;
            for (var i = 0; i < MODULES['wr'].seats; i++) {
                if (MODULES['wr'].seatsPos[i].patient !== null) {
                    if ((new Date().getTime() - PATIENTS[MODULES['wr'].seatsPos[i].patient].datetime) >= 600000 && 
                        (new Date().getTime() - PATIENTS[MODULES['wr'].seatsPos[i].patient].datetime) < 1200000) {
                        PATIENTS[MODULES['wr'].seatsPos[i].patient].el.animate({
                            'fill': 'yellow'
                        }, 1000);
                    } else if ((new Date().getTime() - PATIENTS[MODULES['wr'].seatsPos[i].patient].datetime) >= 1200000 &&
                        (new Date().getTime() - PATIENTS[MODULES['wr'].seatsPos[i].patient].datetime) < 1800000) {
                        PATIENTS[MODULES['wr'].seatsPos[i].patient].el.animate({
                            'fill': 'orange'
                        }, 1000);
                    } else if ((new Date().getTime() - PATIENTS[MODULES['wr'].seatsPos[i].patient].datetime) >= 1800000) {
                        PATIENTS[MODULES['wr'].seatsPos[i].patient].el.animate({
                            'fill': 'red'
                        }, 1000);
                    }
                    count++;
                }                
            }
            if (count >= MODULES['wr'].maxSeats) {
                MODULES['wr'].textMsgMaxSeats.attr({
                    'fill-opacity': 1
                });
            } else {
                MODULES['wr'].textMsgMaxSeats.attr({
                    'fill-opacity': 0
                });
            }
        }, 1000);
    } else if (type === 'limb') { 
        MODULES['lb'] = m;
        MODULES['lb'].setPlacesPos();
    } else {
        MODULES[id] = m;
    }
    if (submodules !== null) {
        var totalSubmodules = submodules.length;
        for (var i = 0; i < totalSubmodules; i++) {
            var sub = submodules[i];
            this.submodule(sub.name, sub.id, id, pos);
        }
        this.countSubmodules = 0;
    }    
};
MAKER.prototype.submodule = function (name, id, idModule, posModule) {    
    var sm = new SUBMODULE(name, id, idModule, posModule, this.countSubmodules);
    MODULES[idModule].submodules[id] = sm;
    this.countSubmodules++;
};
MAKER.prototype.patient = function (rut, ticket, datetime, attention, idModule, idSubmodule) {
    var p = new PATIENT(rut, ticket, datetime, idModule, true);
    PATIENTS[rut] = p;
    
    if (attention === 'waiting') {
        PATIENTS[rut].shape = MODULES[idModule].shape;
        PATIENTS[rut].seat = this.findSeat();
        PATIENTS[rut].goToWaitingRoom(rut, true);
    } else if (attention === 'limb') { 
        PATIENTS[rut].shape = 'circulo';
        PATIENTS[rut].place = this.findPlace();
        PATIENTS[rut].goToLimb(rut, true);
    } else if (attention === 'on_serve') {
        PATIENTS[rut].shape = MODULES[idModule].shape;
        PATIENTS[rut].goTo(idModule, idSubmodule, true);
    }
};
MAKER.prototype.goTo = function (comet, rut, action, ticket, datetime, idModule, idSubmodule) {   
    switch (action) {
        case 'in':
            if (comet === 'tothtem') {
                if (PATIENTS[rut] !== undefined) {
                    PATIENTS[rut].ticket = ticket;
                } else {
                    var p = new PATIENT(rut, ticket, datetime, idModule, false);
                    PATIENTS[rut] = p;     
                }
            } 
            PATIENTS[rut].goTo(idModule, idSubmodule, false);
            break;
        case 'to':
            PATIENTS[rut].shape = MODULES[idModule].shape;
            PATIENTS[rut].seat = this.findSeat();
            PATIENTS[rut].goToWaitingRoom(rut, false);
            break;
        case 'lb':
            PATIENTS[rut].shape = 'circulo';
            PATIENTS[rut].place = this.findPlace();
            PATIENTS[rut].goToLimb(rut, false);
            break;
    }  
};
MAKER.prototype.findSeat = function () {
    for (var i = 0; i < MODULES['wr'].seats; i++) {
        if (MODULES['wr'].seatsPos[i].patient === null) {
            return i;
        }
    }
};
MAKER.prototype.findPlace = function () {
    for (var i = 0; i < MODULES['lb'].places; i++) {
        if (MODULES['lb'].placesPos[i].patient === null) {
            return i;
        }
    }
};