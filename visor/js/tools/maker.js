var MAKER = function () {
    this.countSubmodules = 0;
};
MAKER.prototype.module = function (name, id, type, pos, color, submodules, seats) {
    var m = new MODULE(name, id, type, pos, color, submodules, seats); 
    if (type === 'waiting-room') {
        MODULES['wr'] = m;
        MODULES['wr'].setSeatsPos();
        return m;
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
MAKER.prototype.patient = function (id) {
    var p = new PATIENT(id);
    PATIENTS[id] = p;
};
MAKER.prototype.goTo = function (id, idModule, idSubmodule) {
    if (idModule === null && idSubmodule === null) {
        PATIENTS[id].seat = this.findSeat();
        PATIENTS[id].goToWaitingRoom(id);
    } else {
        PATIENTS[id].goTo(idModule, idSubmodule);
    }    
};
MAKER.prototype.findSeat = function () {
    for (var i = 0; i < MODULES['wr'].seats; i++) {
        if (MODULES['wr'].seatsPos[i].patient === null) {
            return i;
        }
    }
};