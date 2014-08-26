
<!DOCTYPE html>
<html lang="en">
<head>
	<script src="../js/jquery-1.10.2.js"></script>
</head>
<body>
 
<script type="text/javascript">
$( document ).ready(function() {
	sendComet();
});
var initNumber=0;
function sendComet(){
	var newticket = "<?php echo $newticket; ?>";
	var modality = "<?php echo $modality; ?>";
	var date_t = "<?php echo $date_t; ?>";
	var hour_start = "<?php echo $hour_start; ?>";
	var hour_end = "<?php echo $hour_end; ?>";
	var rut = "<?php echo $rut; ?>";
	var ticketData=JSON.stringify({newticket: newticket, modality: modality, date_t: date_t, hour_start: hour_start, hour_end: hour_end, rut: rut});
	comet.doRequest(ticketData);
}



// comet implementation
var Comet = function (data_url) {
  this.timestamp = 0;
  this.url = data_url;
  console.debug(this.url)
  this.noerror = true;

  this.connect = function() {
    var self = this;
    $.ajax({
      type : 'get',
      url : this.url,
      dataType : 'json', 
      data : {'timestamp' : self.timestamp},
      success : function(response) {
        self.timestamp = response.timestamp;
        self.handleResponse(response);
        self.noerror = true;          
      },
      complete : function(response) {
        // send a new ajax request when this request is finished
        if (!self.noerror) {
          // if a connection problem occurs, try to reconnect each 5 seconds
          setTimeout(function(){ comet.connect(); }, 5000);           
        }else {
          // persistent connection
          self.connect(); 
        }

        self.noerror = false; 
      }
    });
  }

  this.disconnect = function() {}

  this.handleResponse = function(response) {
  	//var currentNumber=response.msg;
  }

  this.doRequest = function(request) {
      $.ajax({
        type : 'get',
        url : this.url,
        data : {'msg' : request}
      });
  }

}

var comet = new Comet('backend.php');
comet.connect();
</script>
</body>
</html>