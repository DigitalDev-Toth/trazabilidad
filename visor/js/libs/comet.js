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
                    }, 5000);
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
            //console.log(data);
            if (data.comet === 'tothtem' || data.comet === 'module') {
                MAKE.goTo(data.comet, data.rut, data.action, data.newticket, data.datetime, data.module, data.submodule);
                if(data.action==='cl') {
                    MODULES[SUBMODULES[data.id]].submodules[data.id].blink(0);
                    console.log(data);
                }
            } else if (data.comet === 'submodule') {
                console.log(data);
                if(data.state==='activo') {
                    MODULES[SUBMODULES[data.id]].submodules[data.id].setActive();
                } else if(data.state==='inactivo') {
                    MODULES[SUBMODULES[data.id]].submodules[data.id].setInactive();
                } else if(data.state==='blink') {
                    MODULES[SUBMODULES[data.id]].submodules[data.id].blink(0);
                }
            }
            //console.log(PATIENTS);
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