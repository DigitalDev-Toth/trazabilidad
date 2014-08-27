<html>
<body>
<link href="style/login3.css" rel="stylesheet" type="text/css" />
<table id="subhome" border="1" cellspacing="0" cellpadding="0">
        <tr>
                <td>
                        <table border="0" cellspacing="0" cellpadding="0">
						<tr id="detail_main">
						<td valign="top" id="detail_main_left">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top" id="detail_title_menu">
									Productos DigitalDev:
									</td>
								</tr>
								<tr>
									<td valign="top"  id="detail_menu">
										<ul>
										<?php
																				
												echo '<li><a href="?page=detail&content='.$id_contenido[$i].'"><h2 style="background:url(images/2bioris.png) no-repeat; background-size: 50%">BIORIS</h2></a></li>';
											
										?>
											
										<?php
																				
												echo '<li><a href="?page=detail&content='.$id_contenido[$i].'"><h2 style="background:url(images/2dicomizator_small.png) no-repeat; background-size: 50%">SIGES</h2></a></li>';
											
										?>
											
										<?php
																				
												echo '<li><a href="?page=detail&content='.$id_contenido[$i].'"><h2 style="background:url(images/2report_small.png) no-repeat; background-size: 50%">REPORT GENERATOR</h2></a></li>';
											
										?>
										</ul>
									</td>
								</tr>
								<tr>
									<td valign="top">
										<div id="detail_menu_news">
										   <p>
												comentario2
										   </p>
										</div>
									</td>
								</tr>
							</table>
						</td>

					</tr>
					
                        </table>
                </td>
                <td>
				
                        <table border="0" cellspacing="0" cellpadding="0" >
						<tr>
                                        <td   align="left" valign="top" valign="top" id="subhome_main_right">
											<div id="intro"> Biopacs <br>&nbsp; </div>
										</td>
								</tr>
								<tr>
                                        <td align="left" valign="top" >
	
										<div id="intro2" border="1">

											<table border="0" align="center"  cellpadding="0" cellspacing="0" id="login">
												<tr><td colspan="3" id="titlelogin"><label>::Login de usuario</label></td></tr>
												<tr><td  class="tdu">Usuario</td>
												<td  id="user"><input name="user" type="text" id="user"/></td>
												</tr>
												<tr>
												<td class="tdp">Contrase&ntilde;a</td>
												<td id="pass"><input name="pass" type="password" id="pass" /></td>
												</tr>
												<tr>
												<td colspan="2" class="texto" id="sendLog" align="center"><input type="submit" name="submit" value="Iniciar Sesi&oacute;n" id="send"/></td>
												</tr>
											</table>
										</div>
										
										</td>
                                </tr>
						</table>
                </td>
        </tr>
</table>
</body>
</html>
