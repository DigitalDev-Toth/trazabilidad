<?php

/*$infoFile = file_get_contents("info");
$lines = explode("\n", $infoFile);
foreach ($lines as $line) {
$exp = explode("=", $line);
$info[$exp[0]] = $exp[1];
}
$systemStr = implode(' + ', explode(',', $info['systems']));
$systems = explode(', ', $info['systems']);
foreach ($systems as $system) {
if($system) {
$systemImages .= '<img src="images/'.strtolower($system).'.png" />';
}
}*/
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
	<title>Login TOTH :: Trazabilidad <?php
echo $info['client'];?></title>
	<link rel="SHORTCUT ICON" href="images/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="login/css/login.css">
	<script src="login/js/jquery-2.0.3.js"></script>
	<script src="login/js/bootstrap.min.js"></script>
  	<script src="login/js/jquery-ui.js"></script>
  	<script src='login/js/jquery.base64.js'></script>
  	<script src="login/js/rotate3Di.js" type="text/javascript"></script>
    <script src="login/js/3d-falling-leaves.js" type="text/javascript"></script>
</head>
<script type="text/javascript">
	$(document).ready(function() {
		$('#logo').fadeIn(500, function() {
			$('#hr').animate({
				width: '100%'
			}, 2500);
			$('#systemText').fadeIn(1000, function() {
				$('#formContent').fadeIn(500, function() {
					$('#clientLogo img').fadeIn(1000);
				});
			});
		});
		$('#username img').click(function() {
			$('#userInput').focus();
		});
		$('#password img').click(function() {
			$('#passInput').focus();
		});
		$('.center-height').css({'height': $(window).height()});
		$(window).on('resize', function () {
			$('.center-height').css({'height': $(window).height()});
			$('#logo').fadeIn(500, function() {
				$('#hr').animate({
					width: '100%'
				}, 2500);
				$('#systemText').fadeIn(1000, function() {
					$('#formContent').fadeIn(500, function() {
						$('#clientLogo img').fadeIn(1000);
					});
				});
			});
		});
		$('#submit').click(function() {
			var user = $('#userInput').val();
			var pass = $.base64('encode', $('#passInput').val());
			if(user && pass) {
				$.post("inc/sessionAjax.php", { username: user, password: pass }, function(data) {
					if(data==0) {
						var actualMsg = $('#systemText').html();
						error(0);
						changeText("Error en el nombre de Usuario o contraseña");
						setTimeout("changeText('"+actualMsg+"')", 3000);
					}else if(data == 1){
						$(location).attr('href','index.php');
					}else if(data==10) {

					}else {
						data = data.split('-sub');
						submodule = data[0];
						$(location).attr('href','tothtem/pantallas/index.php?id='+submodule);
					}

				});
			}
		});
		$('#submitMobile').click(function() {
			var user = $('#userInputMobile').val();
			var pass = $.base64('encode', $('#passInputMobile').val());
			if(user && pass) {
				$.post("inc/sessionAjax.php", { username: user, password: pass }, function(data) {
					if(data=='ok') {
						$(location).attr('href','index.php');
						//console.log("ok");
					}
					else {
						var actualMsg = $('#systemTextMobile').html();
						error(0);
						changeText(data);
						setTimeout("changeText('"+actualMsg+"')", 3000);
					}
				});
			}
		});
	});
	function error(times){
		if(times<2) {
			var speed = 200;
			var bgcolor = $('body').css('backgroundColor');
			$('body').animate({
				backgroundColor: 'red'
			}, speed, function() {
				$('body').animate({
					backgroundColor: bgcolor
				}, speed, function() {
						times++;
						error(times);
				});
			});
		}
	}
	function changeText(msg) {
		var speed = 150;
		$('#systemText').fadeOut(speed, function() {
			$('#systemText').html(msg);
			$('#systemText').fadeIn(speed);
		});
	}
</script>
<body>
<div id="modules"><b>Modulos:</b> <?php
echo $info['modules'];?></div>
<div id="shine"></div>
<div id="pc" class="container-fluid">
	<div class="row-fluid">
		<div class="col-md-12">
			<form name="login" method="post" onsubmit="return false;">
				<div class="centered center-block v-center center-height">
					<div id="loginForm">
						<img id="logo" src="images/logo.png" />
						<div id="hr"><hr></div>
						<div id="systemText">Bienvenido al sistema <?php
echo $systemStr;?>!</div>
						<div id="formContent">
							<div class="box clientLogo" id="clientLogo"><img src="images/client.png"></div>
							<div class="box username" id="username"><img src="images/user.png"><input type="text" name="username" id="userInput" placeholder="Usuario" required/></div>
							<div class="box password" id="password"><img src="images/pass.png"><input type="password" name="password" id="passInput" placeholder="Contrase&ntilde;a" required/></div>
							<input type="submit" class="box submit" id="submit" value="Iniciar Sesi&oacute;n"/>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="mobile" class="container-fluid">
	<div class="row-fluid">
		<div class="col-xs-12">
		<form name="loginMobile" method="post" onsubmit="return false;">
			<div class="centered center-block v-center center-height">
				<div id="loginForm">
					<img id="logoMobile" src="images/logo.png" />
					<div class="box clientLogo" id="clientLogo"><img src="images/client.png"></div>
					<div class="container-hr"><div id="hrMobile"><hr></div></div>
					<div id="systemTextMobile">Bienvenido al sistema BioRis!</div>
					<div id="formContentMobile">
						<div class="box username" id="username"><img src="images/user.png"><input type="text" name="username" id="userInputMobile" placeholder="Usuario" required/></div>
						<div class="box password" id="password"><img src="images/pass.png"><input type="password" name="password" id="passInputMobile" placeholder="Contrase&ntilde;a" required/></div>
						<input type="submit" class="box submit center-block" id="submitMobile" value="Iniciar Sesi&oacute;n"/>
					</div>
				</div>
			</div>
		</form>
		</div>
	</div>
</div>
<script> jQuery(document).octoberLeaves() </script>
</body>
</html>