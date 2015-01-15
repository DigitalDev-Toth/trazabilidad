// valida que el rut sea correcto
function Valida_Rut( Objeto )
{
	var tmpstr = "";
	var intlargo = Objeto.value;
	Objeto.value = Objeto.value.toUpperCase();
	if (intlargo.length> 0)
	{
		crut = Objeto.value
		largo = crut.length;
		if ( largo <2 )
		{
			alert('rut invalido')
			Objeto.focus()
			return false;
		}
		for ( i=0; i <crut.length ; i++ )
		if ( crut.charAt(i) != ' ' && crut.charAt(i) != '.' && crut.charAt(i) != '-' )
		{
			tmpstr = tmpstr + crut.charAt(i);
		}
		rut = tmpstr;
		crut=tmpstr;
		largo = crut.length;
	
		if ( largo> 2 )
			rut = crut.substring(0, largo - 1);
		else
			rut = crut.charAt(0);
	
		dv = crut.charAt(largo-1);
	
		if ( rut == null || dv == null )
		return 0;
	
		var dvr = '0';
		suma = 0;
		mul  = 2;
	
		for (i= rut.length-1 ; i>= 0; i--)
		{
			suma = suma + rut.charAt(i) * mul;
			if (mul == 7)
				mul = 2;
			else
				mul++;
		}
	
		res = suma % 11;
		if (res==1)
			dvr = 'k';
		else if (res==0)
			dvr = '0';
		else
		{
			dvi = 11-res;
			dvr = dvi + "";
		}
		valor=Objeto.value;
		valor = valor.replace('.',''); 
		valor = valor.replace('.',''); 
		valor = valor.replace('.',''); 
		valor = valor.replace('-',''); 
		if (valor.length > 1){
			Objeto.value=insertapuntos_menu(valor.substring(0,valor.length - 1)) + '-' + valor.substring(valor.length - 1,valor.length);
		}
		if ( dvr != dv.toLowerCase() )
		{
			alert('El Rut Ingresado es Invalido');
			Objeto.focus()
			return false;
		}
		//alert(Objeto);
		//Objeto.focus()
		return true;
	}
}

// solo permite el ingreso del rut, ningun otro tipo de caracter
function solorut_menu(e){ 
	if(window.event) // IE 
	{
		if (((event.keyCode>47)&&(event.keyCode<58))||(event.keyCode==75)||(event.keyCode==107)||(event.keyCode==45)){
			event.returnValue=true;
		}else{
			event.returnValue=false;
		}
	}
	else if(e.which) // Netscape/Firefox/Opera 
	{ 
		keynum= e.which;
		if (((keynum>47)&&(keynum<58))||(keynum==75)||(keynum==107)||(keynum==8)||(keynum==45)){
			return true;

		}else{
			return false;

		}
	} 
}

//inserta los puntos al ingresar el rut
function insertapuntos_menu(strval){
	var A = new Array();
	var strtemp = strval;
	strtemp = new Number(strtemp);
	strtemp = new String(strtemp);
	if (strtemp.length > 3){
		for(var i = 0; strtemp.length > 3; i++){
			A[i] = Right_menu(strtemp,3);
			strtemp /= 1000;
			strtemp=new String(strtemp);
			if (strtemp.indexOf('.') != -1){
      	strtemp = strtemp.substr(0,strtemp.indexOf('.'));
      }
		}
		for(i-- ;i >= 0 ;i--){
			strtemp = strtemp + "." + A[i];
		}
	}
	return(strtemp);
}

function Right_menu(strvar,intcant){
	strtemp = "";
	intlargo = strvar.length -1;
	for(i = 1;i <= intcant; i++){
		strtemp = strtemp + strvar.charAt(intlargo);
		intlargo--;
	}
	strtmp2=""
	for(intlargo = strtemp.length -1; intlargo >=0; intlargo--){
		strtmp2 = strtmp2 + strtemp.charAt(intlargo);
	}
	return(strtmp2);
}

function llenar_menu( objeto ){
	valor=objeto.calendar_patient_id.value;
	objeto.calendar_patient.value=valor;
	crearFrame('http://digitaldev.gotdns.com/bioris/inc/modules/administration/patientForm.php?rut=',valor);
	//alert('valor='+valor)
	return true;
}
function crearFrame( ruta,valor ) {
    var testFrame = document.createElement("IFRAME");
    testFrame.id = "testFrame";
    testFrame.src = ruta+valor;
	testFrame.width = "100%";
	testFrame.height = "600";
	testFrame.scroll = "auto";
    var control = document.getElementById("testFrame")
    if (control==null) {
    document.body.appendChild(testFrame);
    }
} 
