
<link href="style/subhome.css" rel="stylesheet" type="text/css" />
<table id="subhome" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="3" valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td id="subhome_nav" colspan="2">
						<ul>
							<li id="visit"><a href="?page=home">Home</a> </li>
							<li>Algo aqui</li>
						</ul>
					</td>
				</tr>
				<tr id="subhome_main">
					<td valign="top" id="subhome_main_left">
					<div id="fondo" style="background:#f5f5f5 url(admin/uploads/image/home.png) no-repeat;">&nbsp;</div></td>
					<td valign="top" id="subhome_main_right">
					<div id="intro">Algoooo doss2</div>
					<div id="texto">Algooo tresss</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
	<?php
	if($path!="Soporte"){
		$k = 0;
		for($i=0;$i<4;$i++)
		{
			for($j=0;$j<3;$j++)
			{
				if($j==0){$padding="0px 0px 0px 7px";}
				if($j==1){$padding="0px 0px 0px 0px";}
				if($j==2){$padding="0px 0px 0px 0px";}
				if($id_contenido[$k]!=NULL) 
				{
					if($contenido=='about')
					{
					
						echo '<td valign="top" style="margin-left:10px;">
							    <div id="subhome_section_nos">
								    <div id="subhome_section_header_nos">
									   <h1 style="background: url(admin/uploads/image/'.$imagen_contenido[$k].') top right no-repeat;"><p>'.$titulo_contenido[$k].'</p></h1>
								    </div>
							        <div  id="content_nos">'.$introduccion_contenido[$k].'</div>
								    <div id="subhome_section_footer_nos"></div>
							    </div>
						      </td>';
					}
					else if($contenido=='briefcase') 
					{ 
						if($j==0){$padding="0px 0px 0px 25px";}
						if($j==1){$padding="0px 0px 0px 0px";}
						if($j==2){$padding="0px 0px 0px 0px";}
					
						echo '<td valign="top" style="padding:'.$padding.'; height:100%;">
								<div id="subhome_briefcase">
									<div id="briefcase_images" style="background: url(admin/uploads/image/'.$imagen_contenido[$k].') center no-repeat;"></div>
									<div id="briefcase_header"><p>'.$titulo_contenido[$k].'</p></div>
									<p id="briefcase_content">'.$introduccion_contenido[$k].'</p>
									<div id="briefcase_footer"></div>
								</div>
							  </td>';
						
						
					}
					else if($contenido!='briefcase' && $contenido!='about'){ 
						$vermas='<a href="?page=detail&content='.$id_contenido[$k].'" class="ver_mas">Ver m&aacute;s</a>'; 
						echo '<td valign="top" style="padding:'.$padding.';">
							<div id="subhome_section">
								<div id="subhome_section_header">
									<h1 style="background: url(admin/uploads/image/'.$imagen_contenido[$k].') top right no-repeat; margin-right:10px;"><p>'.$titulo_contenido[$k].'</p></h1>
								</div>
								<p  id="content">'.$introduccion_contenido[$k].$vermas.'</p>
								<div id="subhome_section_footer"></div>
							</div>
						  </td>';
					}
				}
				$k++;
			}
			if(count($id_contenido)<7)
			{
				$i=2;
			}
			if($id_contenido[$k+1]==NULL) { break; }
		echo '</tr><tr>';
		}
	
	}?>
	
	
	</tr>
</table>
