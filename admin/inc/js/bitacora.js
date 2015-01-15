
function showBitacora(id,type) {


    $.post('../services/getBitacoraViewer.php', {rut: id}, function (dataPatient, status) {
        var d = $.parseJSON(dataPatient);
        if (d.length === 1) {
            var contentPatient = '<div class="col-md-12">';
            contentPatient += '<table class="table table-bordered table-condensed">';
            contentPatient += '    <tr>';
            contentPatient += '        <th colspan="6" class="text-center bg-primary">Resultados</th>';
            contentPatient += '    </tr>';
            contentPatient += '    <tr>';
            contentPatient += '        <th>RUT/DNI: </th>';
            contentPatient += '        <td>'+ d[0]["rut"] +'</td>';
            contentPatient += '        <th>N° Ficha:</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '        <th>Estado Actual</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '    </tr>';
            contentPatient += '    <tr>';
            contentPatient += '        <th>Nombre: </th>';
            contentPatient += '        <td>'+ d[0]["name"] +' '+ d[0]["lastname"] +'</td>';
            contentPatient += '        <th>N° P. Tratamiento:</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '        <th>Maximo T. de espera:</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '    </tr>';
            contentPatient += '    <tr>';
            contentPatient += '        <th>Fecha de Nacimiento: </th>';
            contentPatient += '        <td>'+ d[0]["birthdate"] +'</td>';
            contentPatient += '        <th>N° Presupuesto:</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '        <th>T. espera cumulado</th>';
            contentPatient += '        <td>1</td>';
            contentPatient += '    </tr>';
            contentPatient += '</table>';
            contentPatient += '</div>';
        } else {
            var contentPatient = '<div class="col-md-12 text-center">Sin resultados</div>';
        }
        
        $.post('../services/getLogDataViewer.php', {rut: id}, function (dataLogs, status) {
            if (dataLogs !== 0) {
                var d = $.parseJSON(dataLogs);
                var contentLogs = '<div class="col-md-12">';
                contentLogs += '<table id="dataLogs" class="table table-striped table-bordered table-condensed">';
                contentLogs += '    <thead>';
                contentLogs += '        <tr>';
                contentLogs += '            <th>Fecha</th>';
                contentLogs += '            <th>Hora</th>';
                contentLogs += '            <th>Descripción</th>';
                contentLogs += '            <th>Zona</th>';
                contentLogs += '            <th>Módulo</th>';
                contentLogs += '            <th>Submódulo</th>';
                contentLogs += '            <th>Usuario</th>';
                contentLogs += '            <th>Hora inicio de espera</th>';
                contentLogs += '            <th>Hora inicio de atención</th>';
                contentLogs += '            <th>Hora fin de atención</th>';
                contentLogs += '            <th>Total espera</th>';
                contentLogs += '            <th>Total atención</th>';
                contentLogs += '        </tr>';
                contentLogs += '    </thead>';
                contentLogs += '    <tbody>';
                for (var i = 0; i < d.length; i++) {
                    contentLogs += '        <tr>';
                    contentLogs += '            <td>'+ d[i].date +' &nbsp;</td>';
                    contentLogs += '            <td>'+ d[i].time +'</td>';
                    contentLogs += '            <td>'+ d[i].description +'</td>';
                    contentLogs += '            <td>'+ d[i].zone +'</td>';
                    contentLogs += '            <td>'+ d[i].module +'</td>';
                    contentLogs += '            <td>'+ d[i].submodule +'</td>';
                    contentLogs += '            <td>'+ d[i].username +'</td>';
                    contentLogs += '            <td>'+ d[i].waitingStart +'</td>';
                    contentLogs += '            <td>'+ d[i].attentionStart +'</td>';
                    contentLogs += '            <td>'+ d[i].attentionFinish +'</td>';
                    contentLogs += '            <td>'+ d[i].waitingTime +'</td>';
                    contentLogs += '            <td>'+ d[i].attentionTime +'</td>';
                    contentLogs += '        </tr>';
                }
                contentLogs += '    </tbody>';
                contentLogs += '</table>';
                contentLogs += '</div>';
            } else {
                var contentLogs = '<div class="col-md-12">';
                contentLogs += '<table id="dataLogs" class="table table-striped table-bordered table-condensed">';
                contentLogs += '    <thead>';
                contentLogs += '        <tr>';
                contentLogs += '            <th>Fecha</th>';
                contentLogs += '            <th>Hora</th>';
                contentLogs += '            <th>Descripción</th>';
                contentLogs += '            <th>Zona</th>';
                contentLogs += '            <th>Módulo</th>';
                contentLogs += '            <th>Submódulo</th>';
                contentLogs += '            <th>Usuario</th>';
                contentLogs += '            <th>Hora inicio de espera</th>';
                contentLogs += '            <th>Hora inicio de atención</th>';
                contentLogs += '            <th>Hora fin de atención</th>';
                contentLogs += '            <th>Total espera</th>';
                contentLogs += '            <th>Total atención</th>';
                contentLogs += '        </tr>';
                contentLogs += '    </thead>';
                contentLogs += '    <tfoot>';
                contentLogs += '        <tr>';
                contentLogs += '            <th>Fecha</th>';
                contentLogs += '            <th>Hora</th>';
                contentLogs += '            <th>Descripción</th>';
                contentLogs += '            <th>Zona</th>';
                contentLogs += '            <th>Módulo</th>';
                contentLogs += '            <th>Submódulo</th>';
                contentLogs += '            <th>Usuario</th>';
                contentLogs += '            <th>Hora inicio de espera</th>';
                contentLogs += '            <th>Hora inicio de atención</th>';
                contentLogs += '            <th>Hora fin de atención</th>';
                contentLogs += '            <th>Total espera</th>';
                contentLogs += '            <th>Total atención</th>';
                contentLogs += '        </tr>';
                contentLogs += '    </tfoot>';
                contentLogs += '    <tbody>';
                contentLogs += '        <tr>';
                contentLogs += '            <td>Sin resultados</td>';
                contentLogs += '        </tr>';
                contentLogs += '    </tbody>';
                contentLogs += '</table>';
                contentLogs += '</div>';
            }            
            
            var content = contentPatient;
            content += contentLogs;

            $('#bitacoraContent').html(content);
            $('#dataLogs').addClass('table table-bordered table-hover table-condensed');
            
            $('#dataLogs').dataTable({
                "tableTools": {
                    "sSwfPath": "inc/js/datatablesN/vendor/copy_csv_xls_pdf.swf"
                },
                "language": {
                    "url": "inc/js/datatablesN/vendor/languaje.lang"
                }

            });

            $('#showBitacora').modal('show');
        });    
    });   
};