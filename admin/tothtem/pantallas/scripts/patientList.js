function setCurrentNumber(){//Muestra el número actual que se está atendiendo
	$.post('phps/getCallTicket.php', {submodule: submodule}, function(data, textStatus, xhr) {
		if(data!=0){
			var jsonData = JSON.parse(data);
			var number = jsonData[0].ticket;

			if(number<10){
		    	number='00'+number;
		    }
		    if(number>=10 && number<100){
		      	number='0'+number;
		    }
		    $('#content').fadeOut("slow",function(){
		        $('#content').fadeIn("fast");
	            $('#content').text(number);
		    });
		}else{
			$('#content').text('S/N');
		}
    });
}

function getModule(idSubModule){//Obtiene el ID del submódulo actual
    var result = null;
    var scriptUrl = "phps/getModule.php?idSubModule=" + idSubModule;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
    });
    return result;
}

function insertLog(description,action,cometType,attentionNew,ticketId,module){//Inserción de log y escritura de comet
	var totalResult = getTicketData(ticketId);
	if(totalResult!=0){
		var jsonData = JSON.parse(totalResult);
		$.post('services/insertLogs.php', {rut: jsonData[0].rut,description:description,action:action,subModule:submodule,cometType:cometType,attentionNew:attentionNew,ticketId:ticketId,module:module} , function(data, textStatus, xhr) {
		    $.post('../../../visor/comet/backend.php',{msg: data},function(data, textStatus, xhr){
    			setCurrentNumber();
    			//HISTORIAL DE TICKETS ATENDIDOS EN FOOTER
			});
		});
	}
}

/*
	first state : waiting

			minus : previous 
			plus  : call
			play  : on_serve
			stop  : not_serve
			like  : served
			arrow : derived 

*/

function sendComet(type){//Genera la acción de los distintos botones a través de un alias

	if(type==='minus'){
		getNoServeTickets();
	}
	if(type==='plus'){
		myState = true;
		activeButtons('call');
		ticketAttention = firstTicketId;
		insertLog('Siguiente Ticket','cl','module','call',ticketAttention);
	}
	if(type==='isHere'){
		activeButtons('on_serve');
		insertLog('Ticket ha venido','to','module','on_serve',ticketAttention);
	}
	if(type==='notHere'){
		myState = false;
		insertLog('Ticket Ausente','lb','module','no_serve',ticketAttention);
	}
	if(type==='finished'){
		myState = false;
		activeButtons('call');
		insertLog('Ticket Ausente','lb','module','served',ticketAttention);
	}
	if(type==='redirect'){
		$('#modalDerived').modal('show');
	}
	if(type==='exception'){
		$('#modalException').modal('show');
	}

}

function derive(moduleTo){//Deriva el ticket al módulo seleccionado
	myState = false;
	activeButtons('call');
	insertLog('Ticket Derivado','to','module','derived',ticketAttention,moduleTo);//En este caso, insertlog recibirá el módulo al que se deriva
	$('#modalDerived').modal('hide');
}


function refreshTable(){ //Actualiza la tabla de pacientes en espera
    console.log(submodule,initNumber);
    var totalResult=getLast5Tickets(submodule,initNumber);
    if(totalResult==0){
        $('#contentTicket tr').has('td').remove();
        $('#contentTicket').append('<tr><td>No hay pacientes en espera...</td></tr>');
        if(myState==false){
        	activeButtons('onload');
        }
    }else{
        var ticketsTable = JSON.parse(totalResult);
        firstTicketId = ticketsTable[0]['ticketid'];
        var cant=Object.keys(ticketsTable).length;
        $('#contentTicket').fadeOut('slow', function() {
            $('#contentTicket tr').has('td').remove();
                for (var i=0;i<cant;i++) {
                    if(i==0){

                        $('#contentTicket').append('<tr class="info"><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td></tr>');
                    }else{
                        $('#contentTicket').append('<tr><td>'+ticketsTable[i]['ticket']+'</td><td>'+ticketsTable[i]['rut']+'</td><td>'+ticketsTable[i]['datetime'].split(' ')[1]+'</td>  </tr></tr>');  
                    }
                }
            $('#contentTicket').fadeIn('slow');
            if(myState==false){
            	activeButtons('next');
            }
        });
    }
}

function getLast5Tickets(idModule,last){//Devuelve los últimos 5 pacientes en espera
	if(last==null) last=0;
    var result = null;
    var scriptUrl = "phps/lastTickets.php?submodule="+idModule+"&last="+last;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
    });
    return result;
}

function getNoServeTickets(){//Devuelve y muestra los últimos 10 tickets que no fueron atendidos
    var scriptUrl = "phps/noServeLastTickets.php?submodule="+submodule+"&type=no_serve";
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            console.log(data);
            $('#modalNoServeContent').html('');
         
            console.log(jsonData);
            if(data!=0){
            	var jsonData = JSON.parse(data);
            	$('#modalNoServeContent').append('<tr><th>Ticket</th><th>Paciente</th><th>Tiempo</th><th></th></tr>');
            	for(i=0;i<jsonData.length;i++){
            		$('#modalNoServeContent').append('<tr><td>'+jsonData[i]['ticket']+'</td><td>'+jsonData[i]['rut']+'</td><td>'+jsonData[i]['datetime']+'</td><td><button type="button" class="btn btn-primary" onclick="firstTicketId='+jsonData[i]['ticketid']+'; sendComet(&quot;plus&quot;); $(&quot;#modalNoServe&quot;).modal(&quot;hide&quot;);">Usar</button></td></div>');
            	}
            }else{
            	$('#modalNoServeContent').append('<tr ><th class="text-center">No hay pacientes </th></tr>');
            }

    
            $('#modalNoServe').modal('show');
        } 
    });
}

function getExceptions(){//Obtiene el total de los pacientes en espera del módulo
    var scriptUrl = "phps/noServeLastTickets.php?submodule="+submodule+"&type=exception";
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            console.log(data);
            $('#modalNoServeContent').html('');
            var jsonData = JSON.parse(data);

            if(jsonData.length!=0){
            	$('#modalNoServeContent').append('<tr><th>Ticket</th><th>Paciente</th><th>Tiempo</th><th></th></tr>');
            	for(i=0;i<jsonData.length;i++){
            		$('#modalNoServeContent').append('<tr><td>'+jsonData[i]['ticket']+'</td><td>'+jsonData[i]['rut']+'</td><td>'+jsonData[i]['datetime']+'</td><td><button type="button" class="btn btn-primary" onclick="firstTicketId='+jsonData[i]['ticketid']+'; sendComet(&quot;plus&quot;); $(&quot;#modalNoServe&quot;).modal(&quot;hide&quot;);">Usar</button></td></div>');
            	}
            }else{
            	$('#modalNoServeContent').append('<tr><th>No hay pacientes </th></tr>');
            }

    
            $('#modalNoServe').modal('show');
        } 
    });
}


function getTicketData(ticketid){//Recoge la información del ticket solicitado (ticket y log)
    var scriptUrl = "phps/getTicketData.php?id="+ticketid;
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(data) {
            result = data;
        } 
    });
    return result;
}


function activeButtons(type){//Activa o inactiva botones
	if(type=='onload'){
		$('#buttons :input').attr('disabled', true);
		$('#minusButton').attr('disabled', false);
		$('#exceptionButton').attr('disabled', false);
		$('#redirectButton').attr('disabled', false);
    }
	if(type=='next'){
		$('#plusButton').attr('disabled', false);
      	$('#minusButton').attr('disabled', false);
		$('#isHereButton').attr('disabled', true);
      	$('#notHereButton').attr('disabled', true);
      	$('#finishedButton').attr('disabled', true);
      	$('#redirectButton').attr('disabled', false);
      	$('#exceptionButton').attr('disabled', false);
    }
	if(type=='call'){
		$('#plusButton').attr('disabled', true);
      	$('#minusButton').attr('disabled', true);
		$('#isHereButton').attr('disabled', false);
      	$('#notHereButton').attr('disabled', false);
      	$('#finishedButton').attr('disabled', true);
      	$('#redirectButton').attr('disabled', true);
      	$('#exceptionButton').attr('disabled', true);
    }
	if(type=='on_serve'){
		$('#plusButton').attr('disabled', true);
      	$('#minusButton').attr('disabled', true);
		$('#isHereButton').attr('disabled', true);
      	$('#notHereButton').attr('disabled', true);
      	$('#finishedButton').attr('disabled', false);
      	$('#redirectButton').attr('disabled', false);
      	$('#exceptionButton').attr('disabled', true);
    }
		
}