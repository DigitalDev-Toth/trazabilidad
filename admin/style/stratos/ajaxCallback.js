/*
 * Se realiza una solicitud AJAX mediante GET, indicando
 *   url
 *   callback_function: Funcion que se ejecutara al obtener los resultados
 *   div_salida: Referencia al elemento DOM (div / span) en el que se colocaran los resultados
 *   xml: Los resultados se trataran como un objeto de tipo arbol XML (1) o como texto plano (0)
 */

function makeHttpRequest(url, callback_function, nombreSalida, return_xml)
{
  var http_request = false;
  var refSalida = xGetElementById(nombreSalida);

  if (window.XMLHttpRequest) { // Mozilla, Safari,...
    http_request = new XMLHttpRequest();
    if (http_request.overrideMimeType) {
      // Si dejo esta linea, da errores, pues devuelve text/plain y no text/xml
      // http://developer.mozilla.org/en/docs/AJAX:Getting_Started
      // http_request.overrideMimeType('text/xml');
    }
  }
  else if (window.ActiveXObject) { // IE
    try {
      http_request = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
      try {
        http_request = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {}
    }
  }

  if (!http_request) {
    alert('Su navegador no soporta XMLHttpRequest.');
    return false;
  }

  http_request.onreadystatechange = function() {
    // Si se vuelve atras a esta pagina desde una posterior, este elemento esta sin inicializar
    // Problema ya resuelto ???
    if (refSalida) {
      //alert ("http_request.readyState: " + http_request.readyState);
      if (http_request.readyState == 1) {
//        div_salida.innerHTML = '&nbsp;Cargando...';
      } else 
      if (http_request.readyState == 4) {
        if (http_request.status == 200) {
          if (return_xml) {
            eval(callback_function + '(refSalida, http_request.responseXML)');
          }
          else {
            eval(callback_function + '(refSalida, http_request.responseText)');
          }
        }
        else {
          alert('Ha habido un problema al conectar.(URL: ' + url + ', Code: ' + http_request.status + ')');
        }
      }
    }
    else {
      alert('No hay refSalida');
    }
  }
/*
// PB en MSIE en respuestas no XML con caracteres no ASCII
// http://en.wikipedia.org/wiki/XmlHttpRequest#Accents_and_Non-ascii_characters_problem
// Resuelto si el PHP entrega los caracteres especiales codificados en HTML
    mydate = new Date();
    mytime = mydate.getTime();
    url = url+'&t='+mytime;
    alert(url);
*/
  http_request.open('GET', url, true);
  http_request.send(null);
}

// ******* ********* ********* ********* ********* ********* ********* *********

// http://cross-browser.com/
// Funcion xGetElementById obtenida de: X Library (GNU-LGPL)

function xGetElementById(e) {
  if(typeof(e)!='string') return e;
  if(document.getElementById) e=document.getElementById(e);
  else if(document.all) e=document.all[e]; // MSIE no sabe distinguir entre identificar por 'nombre' o por 'id' !!!
  else e=null;
  // if (e) {alert ("encontrado: " + e);}
  return e;
}

function setValorSalida(refSalida, valor) { 
//  contenedor = xGetElementById(div_salida); 
//  contenedor.innerHTML = valor; 
  refSalida.innerHTML = valor;
}

// ******* ********* ********* ********* ********* ********* ********* *********
