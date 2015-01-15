
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>TothTem</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/stylish-portfolio.css" rel="stylesheet">
    <link href="css/loader.css" rel="stylesheet">
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <script src="js/jquery-2.0.3.js" type="text/javascript"></script>
    <script src="http://localhost:8000/socket.io/socket.io.js"></script>
    <script src="js/validarut.js" type="text/javascript"></script>
    <script src="js/bootbox.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery.xml2json.js"></script>
    <script src="js/jquery.xdomainajax.js"></script>

<script>

    var rut=14166781;
    /*$.post('http://201.238.201.37:84/Service.asmx/traeDatosPaciente',{intTipoDoc: 1,strNroDoc: rut},function(data, textStatus, xhr){
        console.log(data);
    });*/

    /*var url='http://201.238.201.37:84/Service.asmx/traeDatosPaciente';
    var request = new XMLHttpRequest();
    var params = "intTipoDoc=1&strNroDoc=14166781";
    request.open('POST', url, true);
    request.onreadystatechange = function() {if (request.readyState==4) alert("It worked!");};
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.setRequestHeader("Content-length", params.length);
    request.setRequestHeader("Connection", "close");
    request.send(params);*/
    var xmlSource="http://201.238.201.37:84/Service.asmx/traeDatosPaciente?intTipoDoc=1&strNroDoc=14166781";
// find some demo xml - DuckDuckGo is great for this
    //var xmlSource = "http://api.duckduckgo.com/?q=StackOverflow&format=xml"

// build the yql query. Could be just a string - I think join makes easier reading
    var yqlURL = [
        "http://query.yahooapis.com/v1/public/yql",
        "?q=" + encodeURIComponent("select * from xml where url='" + xmlSource + "'"),
        "&format=xml&callback=?"
    ].join("");

// Now do the AJAX heavy lifting        
    $.getJSON(yqlURL, function(data){
        console.log(data);
        xmlContent = $(data.results[0]);
        var Abstract = $(xmlContent).find("Abstract").text();
        console.log(Abstract);
    });
    $.ajax({
        type: "POST",
        url:xmlSource,
        //crossDomain: true,
        //dataType: 'jsonp',
        success: function(datos){
            console.log(datos);
        },
        error: function (obj, error, objError){
            console.log(error);
        }
    });
    /*function xmlLoader(){
        $.ajax({
            //url: 'http://201.238.201.37:84/Service.asmx/traeDatosPaciente?intTipoDoc=1&strNroDoc=14166781',
            url: 'http://examples.oreilly.com/9780596002527/examples/first.xml',
            dataType: "xml",
            type: 'GET',
            success: function(res) {
                var myXML = res.responseText;
                // This is the part xml2Json comes in.
                var JSONConvertedXML = $.xml2json(myXML);
                console.log(res);
                console.log(myXML);
                console.log(JSONConvertedXML);
                /*$('#myXMLList').empty();
                for(var i = 0; i < JSONConvertedXML.book.character.length; i++){
                    $('#myXMLList').append('<li><a href="#">'+JSONConvertedXML.book.character[i].name+'</a></li>');
                }
                $('#myXMLList').listview('refresh');
                $.mobile.hidePageLoadingMsg();*/
       /*     }
        });
    }*/

   /* $( document ).delegate("#home", "pageshow", function() {
        console.log('hola');
        $.mobile.showPageLoadingMsg();
        xmlLoader();
    });*/

</script>
<body> 
    <div data-role="page" id="home">
    <div data-role="header">
        <h1>Sample Cross Domain XML</h1>
    </div>
    <div data-role="content">
        <ul data-role="listview" data-theme="c" id="myXMLList">
        </ul>
    </div>
    <div data-role="footer">
        <a href="www.isgoodstuff.com" data-role="button">isGoodStuff.com</a>
    </div>
</body>

</html>
