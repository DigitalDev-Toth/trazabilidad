var indexComet = 0;
var Comet = function (data_url) {
    this.timestamp = 0;
    this.url = data_url;
    this.noerror = true;

    this.connect = function() {
        var self = this;

        $.ajax({
            type: 'get',
            url: this.url,
            dataType: 'json',
            data: {
                'timestamp': self.timestamp
            },
            success: function (response) {
                self.timestamp = response.timestamp;
                self.handleResponse(response);
                self.noerror = true;
            },
            complete: function (response) {
                if (!self.noerror) {
                    setTimeout(function () {
                        comet.connect();
                    }, 1000);
                } else {
                    self.connect();
                }
                self.noerror = false;
            }
        });
    };

    this.disconnect = function () {};

    this.handleResponse = function(response) {
        if (indexComet !== 0) {
            var data = $.parseJSON(response.msg);
        	console.log(response.msg);
<<<<<<< HEAD
            if(response.msg[0]=='{'){
                if (data.comet === 'tothtem' || data.comet === 'module') {
                    MAKE.goTo(data.comet, data.rut, data.action, data.newticket, data.datetime, data.module, data.submodule);

                } else if (data.comet === 'submodule') {
=======
            if (data.comet === 'tothtem' || data.comet === 'module') {
                MAKE.goTo(data.comet, data.rut, data.action, data.newticket, data.datetime, data.module, data.submodule);
            } else if (data.comet === 'submodule') {
                if(SUBMODULES[data.id]!=undefined) {
>>>>>>> juan
                    if(data.state==='activo') {
                        MODULES[SUBMODULES[data.id]].submodules[data.id].setActive();
                    } else if(data.state==='inactivo') {
                        MODULES[SUBMODULES[data.id]].submodules[data.id].setInactive();
                    } else if(data.state==='blink') {
                        MODULES[SUBMODULES[data.id]].submodules[data.id].blink(0);
                    }
                }
            }else if(response.msg[0]=='['){
                for(var i=0;i<data.length;i++){
                    if (data[i].comet === 'tothtem' || data[i].comet === 'module') {
                        MAKE.goTo(data[i].comet, data[i].rut, data[i].action, data[i].newticket, data[i].datetime, data[i].module, data[i].submodule);

                    } else if (data[i].comet === 'submodule') {
                        if(data[i].state==='activo') {
                            MODULES[SUBMODULES[data[i].id]].submodules[data[i].id].setActive();
                        } else if(data[i].state==='inactivo') {
                            MODULES[SUBMODULES[data[i].id]].submodules[data[i].id].setInactive();
                        } else if(data[i].state==='blink') {
                            MODULES[SUBMODULES[data[i].id]].submodules[data[i].id].blink(0);
                        }
                    }
                }
            }

        } else {
            indexComet = 1;
        }
    };

    this.doRequest = function (request) {
        $.ajax({
            type: 'get',
            url: this.url,
            data: {
                'msg': request
            }
        });
    };
};

var comet = new Comet('comet/backend.php');
comet.connect();