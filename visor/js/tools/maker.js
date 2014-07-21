var MAKER = function () {
    this.countSubmodules = 0;
};
MAKER.prototype.module = function (name, id, type, pos, color, totalSubmodules) {
    var m = new MODULE(name, id, type, pos, color, totalSubmodules); 
    if (type === 'waiting-room') {
        MODULES['wr'] = m;
    } else {
        MODULES[id] = m;
    }
    
    if (totalSubmodules > 0) {
        for (var i = 0; i < totalSubmodules; i++) {
            this.submodule('Informaciones 1', i, id, pos);
        }
        this.countSubmodules = 0;
    }    
};
MAKER.prototype.submodule = function (name, id, idModule, posModule) {    
    var sm = new SUBMODULE(name, id, idModule, posModule, this.countSubmodules);
    MODULES[idModule].submodules[id] = sm;
    this.countSubmodules++;
};