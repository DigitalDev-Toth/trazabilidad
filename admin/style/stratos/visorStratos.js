// ******* ********* ********* ********* ********* ********* ********* *********

var menuVisible = true;

function getWindowHeight()
{
	var windowHeight=0;
	if (typeof(window.innerHeight)=='number')
		windowHeight=window.innerHeight;
	else if (document.documentElement&&document.documentElement.clientHeight)
		windowHeight=document.documentElement.clientHeight;
	else if (document.body&&document.body.clientHeight)
		windowHeight=document.body.clientHeight;

	return windowHeight;
}

// ******* ********* ********* *********

function getWindowWidth()
{
	var windowWidth=0;
	if (typeof(window.innerWidth)=='number')
		windowWidth=window.innerWidth;
	else if (document.documentElement&&document.documentElement.clientWidth)
		windowWidth=document.documentElement.clientWidth;
	else if (document.body&&document.body.clientWidth)
		windowWidth=document.body.clientWidth;

	return windowWidth;
}

// ******* ********* ********* ********* ********* ********* ********* *********

function setSize() {
  // alert('ajustando...');
  var ref_izq = xGetElementById('col_izq');
  var ref_der = xGetElementById('col_der');
  var ref_ctr = xGetElementById('col_ctr');
  // var ref_visor = xGetElementById('bloque_visor');
  
  var ancho_izq = 0;
  if (ref_izq.style.display != 'none') {
    var ancho_izq = 200;
  }
  var ancho_ctr = 32;
  var ww = getWindowWidth();
  var ancho_der = ww - ancho_izq - ancho_ctr;
  var wh = getWindowHeight();

  ref_izq.style.width = '' + ancho_izq + 'px';
  ref_izq.style.height = '' + wh + 'px';
  ref_ctr.style.width = '' + ancho_ctr + 'px';
  ref_ctr.style.height = '' + wh + 'px';
  ref_der.style.width = '' + ancho_der + 'px';
  ref_der.style.height = '' + wh + 'px';
}

// ******* ********* ********* ********* ********* ********* ********* *********

function mostrarOcultar(id) {
  // xHide('menu_web');
  ref = xGetElementById(id);

  if (ref.style.display != 'none') {
  	ref.style.display = 'none';
  }
  else {
  	ref.style.display = 'block';
  }
}

// ******* ********* ********* ********* ********* ********* ********* *********

// Se muestran u ocultan las instancias pertenecientes a una serie
function mostrarOcultarInstancias_(study_iuid, series_iuid) {
  var idSerie = 'series_' + series_iuid;
  var ref = xGetElementById(idSerie);
  var ref_i1 = xGetElementById('i1_' + idSerie); // Referencia a la primera instancia de la serie

  // Se obtiene el contenido asociado si no se ha obtenido previamente
  if (ref.innerHTML == '') {
    var url = 'ajMiniaturas.php?study_iuid=' + study_iuid + '&serie_iuid=' + series_iuid;
    makeHttpRequest(url, 'setValorSalida', idSerie, false);
  }

  // Se muestra u oculta el contenido en funcion del estado
  if (ref.style.display != 'none') {
  	ref.style.display = 'none';
  	ref_i1.style.display = 'block';
  }
  else {
  	ref.style.display = 'block';
  	ref_i1.style.display = 'none';
  }
}

// ******* ********* ********* ********* ********* ********* ********* *********
// Se muestran u ocultan las instancias pertenecientes a una serie
function mostrarOcultarInstancias(idSeries) {
  var ref = xGetElementById(idSeries);
  // alert("mostrarOcultarInstancias: " + idSeries + "\nref: " + ref);
  
  // Se obtiene el contenido asociado si no se ha obtenido previamente
  if (ref.innerHTML == '') {
    ref.innerHTML = eval(idSeries);
  }

  // Se muestra u oculta el contenido en funcion del estado
  if (ref.style.display != 'none') {
  	ref.style.display = 'none';
  }
  else {
  	ref.style.display = 'block';
  }
}

// ******* ********* ********* ********* ********* ********* ********* *********

// Se muestran u ocultan las series pertenecientes a un estudio
function mostrarOcultarSeries(idStudy) {
  var ref = xGetElementById(idStudy);

  // Se muestra u oculta el contenido en funcion del estado
  if (ref.style.display != 'none') {
  	ref.style.display = 'none';
  }
  else {
  	ref.style.display = 'block';
  }
}

// ******* ********* ********* ********* ********* ********* ********* *********

function mostrarOcultarMiniaturas() {
  var ref_izq = xGetElementById('col_izq');
  if (ref_izq.style.display != 'none') {
    ref_izq.style.display = 'none';
  }
  else {
    ref_izq.style.display = 'block';
  }

//  var ref_ctr = xGetElementById('col_ctr');
  setSize();
}

// ******* ********* ********* ********* ********* ********* ********* *********

function maximizarIE() {
  window.moveTo(0,0);
  window.resizeTo(screen.width,screen.height - 28);
  window.focus()
}

// ******* ********* ********* ********* ********* ********* ********* *********
