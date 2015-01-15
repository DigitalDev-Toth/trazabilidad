

//npm install socket.io socket.io-client
var io = require('socket.io'),ioClient = require('socket.io-client'), pg = require('pg');
var socket = ioClient.connect("http://falp.biopacs.com:8000");
var conString = "postgres://postgres:justgoon@localhost/traza";


function query(sockete){
	var client = new pg.Client(conString);
	client.connect(function(err) {
	  	if(err) {
	    	console.log(err);	
	      	return ;
	    }
	    var date = getDate();
	  	//client.query("SELECT * FROM tickets t LEFT JOIN logs l ON l.id=t.logs WHERE attention='waiting' AND datetime >= '"+date+"' AND datetime < ('"+date+"'::date + '1 day'::interval)", function(err, result) {
	  	client.query("SELECT logs.id as logsid, logs.rut, logs.datetime, logs.zone, logs.module, tickets.attention, module.max_wait ,module.name,zone.name as zname FROM tickets, logs, module,zone WHERE logs.id = tickets.logs AND logs.module = module.id AND logs.zone = zone.id AND attention='waiting' AND datetime >= '"+date+"' AND datetime < ('"+date+"'::date + '1 day'::interval)", function(err, result) {
		    if(err) {
		    	console.log(err);	
		      	return ;
		    }
		    var filtred = filters(result);
		    sockete.send(JSON.stringify(filtred));
		    client.end();
		    return 'ok';
	  	});
	});
}

function filters(results){
	var toAlert = [];
	for (var i = 0; i < results.rows.length; i++) {
		var hour = results.rows[i].datetime.getHours();
		var minutes = results.rows[i].datetime.getMinutes();
		var remainminutes = getHour(hour,minutes,results.rows[i].max_wait);
		if(remainminutes >= results.rows[i].max_wait){
			results.rows[i].comet = "Alert";
			results.rows[i].max_wait = remainminutes;
			toAlert.push(results.rows[i]);
		}
	};
	return toAlert;
}

function getHour(Ihour,Iminutes,max){
	var today = new Date();
	var Fhour = today.getHours();
	var Fminutes = today.getMinutes();
	var remainHours = (parseInt(Fhour)-parseInt(Ihour))*60;
	var remainminutes = (parseInt(Fminutes)-parseInt(Iminutes))+remainHours;//in minutes
	return remainminutes;
	
}

function getDate(){
	var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; 
    var yyyy = today.getFullYear();
    if(dd<10) dd='0'+dd;
    if(mm<10) mm='0'+mm;
    return ( yyyy +'-'+ mm +'-'+ dd );
}

setInterval(function(){
	query(socket);
}, 15000);





