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
    if (submodules!=null) {
        totalSubmodules = Object.keys(submodules).length;
        for (var i = 0; i < totalSubmodules; i++) {
            sub = submodules[i];
            this.submodule(sub.name, i, sub.id, pos);
            console.log(pos);
        }
        this.countSubmodules = 0;
    }    
};
MAKER.prototype.submodule = function (name, id, idModule, posModule) {    
    var sm = new SUBMODULE(name, id, idModule, posModule, this.countSubmodules);
    //MODULES[idModule].submodules[id] = sm;
    this.countSubmodules++;
};