<html>
<head>
<script src="../js/jquery-2.0.3.js" type="text/javascript"></script>
<script src="../js/jquery.qrcode.min.js" type="text/javascript"></script>
<script src="../js/jquery.urlshortener.min.js" type="text/javascript"></script>
</head>
<body>

<div id="printArea" align="center">
<img src="../img/logo.png" style="width: 128px;"><br>
<label>Codigo Qr</label>
<div id="qrcode"></div>
<label>Url de su examen:</label><br>
<label id="url"></label>
<div id="Date"></div>

</div>
</body>
</html>

<script type="text/javascript">
jQuery.urlShortener.settings.apiKey='AIzaSyBReANezMwoY-5Vdsk7rgrX3Ipqr57XO9E';
var Qr = decodeURIComponent("<?php echo rawurlencode($_GET['urlQr']); ?>");
jQuery.urlShortener({
longUrl: Qr,
success: function (shortUrl) {
     $('#qrcode').qrcode(shortUrl);
     $("#url").text(shortUrl);
    window.setTimeout(window.print(), 2000);
},
error: function(err){
    
}
});

var newDate=new Date();
var month=newDate.getMonth()+1;
var day=newDate.getDate()+1;
var year=newDate.getFullYear();
if (month < 10) { month = '0' + month;}
if (day < 10) { day = '0' + day; }

var Tdate=day+"/"+month+"/"+year;

$("#Date").text(Tdate);
$(document).ready(function () {
	//window.setTimeout(window.print(), 2000);
});

</script>

