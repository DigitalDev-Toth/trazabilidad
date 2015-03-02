<?php
/*
Archivos que usan esta función:

Tótem & Pantalla de Ejecutivo
- admin/tothtem/tothtem/scripts/getPatientName
- admin/tothtem/tothtem/scripts/getTicket
- admin/tothtem/tothtem/scripts/insertLogs

Visor
- services/getPatients.php

Bitácora
- admin/inc/services/getBitacora.php
*/
function getPatientName($rut){

    $sqlPatient="SELECT * FROM patients WHERE rut='$rut'";
    $dbPatient = NEW DB();

    $patientInternal = $dbPatient->doSql($sqlPatient);


    if($patientInternal){
        $patientData[] = array(
            'rut' =>  $rut,
            'name' =>  $patientInternal['name'],
            'lastname' =>  $patientInternal['lastname'],
            'birthdate' =>  $patientInternal['birthdate'],
            'gender' =>  $patientInternal['gender'],
            'address' =>  $patientInternal['address']
        );
        return $patientData;

    }else{
        if(substr($rut,-2,-1)=='-'){
            $type=1;
        }else{
            $type=3;
        }

        if($type==1){
            $rutA = str_replace(".","", $rut);
            $rutA = str_replace("-","", $rutA);
            $rutA = substr_replace($rutA ,"",-1);
        }

        exec('curl --data "intTipoDoc='.$type.'&strNroDoc='.$rutA.'" http://201.238.201.37:84/Service.asmx/traeDatosPaciente', $out, $err);

        /*
        27 - Nombres
        28 - Apellido Paterno
        29 - Apellido Materno
        30 - Fecha de nacimiento
        31 - Edad
        32 - Género
        33 - Tipo de Identificador
        34 - Identificador (DNI o RUT sin verificador)
        */

        $patientData[] = array(
            'rut' =>  $rut,
            'name' =>  $out[27],
            'lastname' =>  $out[28].' '.$out[29],
            'birthdate' =>  $out[30],
            'gender' =>  $out[32],
            'address' =>  ' -'
        );

        if($out[27]){//Si existe un registro válido que devuelve el webservice, se almacenará en la BD de traza
            $birthdate=str_replace(' ','',$out[30]);
            $birthdate=explode('/',$birthdate);
            $birthdate[2]=str_replace('<','',$birthdate[2]);
            $birthdate[0]=str_replace('<NACIMIENTO>','',$birthdate[0]);
            $birthdate=$birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];

            $name=explode('<NOMBRES>',$out[27]);
            $name=str_replace('</NOMBRES>','',$name[1]);
            $lastnameP=explode('<AP_PATERNO>',$out[28]);
            $lastnameP=str_replace('</AP_PATERNO>','',$lastnameP[1]);
            $lastnameM=explode('<AP_MATERNO>',$out[29]);
            $lastnameM=str_replace('</AP_MATERNO>','',$lastnameM[1]);
            $gender=explode('<SEXO>',$out[32]);
            $gender=str_replace('</SEXO>','',$gender[1]);
            $lastname=$lastnameP.' '.$lastnameM;


            $dbPatient->doSql("INSERT INTO patients(rut,name,lastname,birthdate,gender,address) VALUES('$rut','$name','$lastname','$birthdate','$gender','-')");
        }
        
        return $patientData;
    }
}
?>