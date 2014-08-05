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
            var json = $.parseJSON(response.msg);
            console.log(response, json);
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