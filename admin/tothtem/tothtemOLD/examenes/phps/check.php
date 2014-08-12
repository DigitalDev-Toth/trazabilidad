<?php
//Verify reCaptcha

//get IP address of the user
require_once('recaptchalib.php');
 $privatekey = "6LeUWfUSAAAAAKEKZ5Lsw0a3_SxLwnagh7kL8Mhm";
 $resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
 
 if (!$resp->is_valid) {
      //ERROR EN EL CAPTCHA
      echo 0;
 }else{
      //CAPTCHA CORRECTO
      echo 1;
 }

?>