var MAKER = function () {
    this.countSubmodules = 0;
};
MAKER.prototype.module = function (name, id, type, pos, color, shape, submodules, seats) {
    var m = new MODULE(name, id, type, pos, color, shape, submodules, seats); 
    if (type === 'waiting-room') {
        MODULES['wr'] = m;
        MODULES['wr'].setSeatsPos();
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
MAKER.prototype.patient = function (rut, ticket, attention, idModule, idSubmodule) {
    var p = new PATIENT(rut, ticket, idModule, true);
    PATIENTS[rut] = p;
    
    if (attention === 'waiting') {
        PATIENTS[rut].shape = MODULES[idModule].shape;
        PATIENTS[rut].seat = this.findSeat();
        PATIENTS[rut].goToWaitingRoom(rut, true);
    } else if (attention === 'on_serve') {
        PATIENTS[rut].shape = MODULES[idModule].shape;
        PATIENTS[rut].goTo(idModule, idSubmodule, true);
    }
};
MAKER.prototype.goTo = function (comet, rut, action, ticket, idModule, idSubmodule) {   
    switch (action) {
        case 'in':
            if (comet === 'tothtem') {
                if (PATIENTS[rut] !== undefined) {
                    PATIENTS[rut].ticket = ticket;
                } else {
                    var p = new PATIENT(rut, ticket, idModule, false);
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
    }  
};
MAKER.prototype.findSeat = function () {
    for (var i = 0; i < MODULES['wr'].seats; i++) {
        if (MODULES['wr'].seatsPos[i].patient === null) {
            return i;
        }
    }
};