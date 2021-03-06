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
        console.log(response);
        if (indexComet !== 0) {
            var data = $.parseJSON(response.msg);
            if (data.zone === zone && data.comet === 'module') {
                if(data.action == 'in'){
                    changeNumber(data,0);
                }
                if(data.action == 'lb'){
                    changeNumber(data,0);   
                }
                if(data.action == 'to'){
                    changeNumber(data,0);   
                }
            }
            if(data.comet == 'submodule' && (data.state == 'activo' || data.state == 'inactivo' ) ){
                reloadDisplay(data);
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

var comet = new Comet('../../../visor/comet/backend.php');
comet.connect();