var MAKER = function () {
    this.countSubmodules = 0;
};
MAKER.prototype.module = function (name, id, type, pos, color, submodules, seats) {
    var m = new MODULE(name, id, type, pos, color, submodules, seats); 
    if (type === 'waiting-room') {
        MODULES['wr'] = m;
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
MAKER.prototype.patient = function () {
    var p = new PATIENT();
    PATIENTS[1] = p;
};
MAKER.prototype.goTo = function (idModule, idSubmodule) {
    if (idModule === null && idSubmodule === null) {
        PATIENTS[1].goToWaitingRoom();
    } else {
        PATIENTS[1].goTo(idModule, idSubmodule);
    }    
};