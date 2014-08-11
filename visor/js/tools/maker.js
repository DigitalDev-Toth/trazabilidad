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
MAKER.prototype.goTo = function (comet, rut, action, attentionNum, idModule, idSubmodule) {
//    for (var i in MODULES) {
//        if (MODULES[i].submodules !== null) {
//            if (MODULES[i].submodules[idSubmodule] !== undefined) {
//                var idModule = parseInt(i);
//            }
//        }        
//    }    
    switch (action) {
        case 'in':
            if (comet === 'tothtem') {
                if (PATIENTS[rut] !== undefined) {
                    PATIENTS[rut].attentionNum = attentionNum;
                } else {
                    var p = new PATIENT(rut, attentionNum, idModule);
                    PATIENTS[rut] = p;     
                }
            } 
            PATIENTS[rut].goTo(idModule, idSubmodule);
            break;
        case 'to':
            PATIENTS[rut].displayAttentionNum(rut);
            PATIENTS[rut].shape = MODULES[idModule].shape;
            PATIENTS[rut].seat = this.findSeat();
            PATIENTS[rut].goToWaitingRoom(rut);
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