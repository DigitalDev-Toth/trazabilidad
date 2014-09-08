<?
if(isset($_GET['selectMany'])) { $this->control("separator"); $this->control("use"); }
		echo '<table id="drawControls" align="'.$this->controls['align'].'"><tr>';
		foreach($this->controls['schema'] as $id=>$data)
		{
			$item = $data['control'];
			$link = $data['link'];
			if ($item != NULL)
			{
				echo '<td>';
				if ($item=='insert')
				{
					echo '<INPUT TYPE="image" src="../images/insert.png" NAME="insert" VALUE="Insertar" onclick="this.form.action='."'".$link."'".';this.form.submit();" />';
				}
				if ($item=='insertMany')
				{
					echo '<INPUT TYPE="image" src="../images/insert.png" NAME="insertMany" VALUE="Insertar Muchos" title="'.$tip.'" onclick="PopupCenter('."'".$link."'".', '."'insertMany'".',650, 500);" />';
				}
				if ($item=='delete')
				{
					echo '<INPUT TYPE="image" src="../images/delete.png" VALUE="Borrar" onclick="isMarked(this.form, '."'".$link."'".', '."'delete'".');" />';
				}
				if ($item=='update')
				{
					echo '<INPUT TYPE="image" src="../images/update.png" VALUE="Modificar" onclick="isMarked(getElementById('."'form_main'".'), '."'".$link."'".', '."'update'".');" />';
				}
				if ($item=='use')
				{
					echo '<INPUT TYPE="image" src="../images/ok.png" VALUE="Usar" onclick="selectMany(this.form);" />';
				}
				if ($item=='back')
				{
					echo '<INPUT TYPE="image" src="../images/back.png" NAME="back" VALUE="Atras" onclick="this.form.action='."'".$link."'".';this.form.submit();" />';
				}
				if ($item=='refresh')
				{
					echo '<INPUT TYPE="image" src="../images/refresh.png" NAME="refresh" VALUE="recargar" onclick="window.location.reload();" />';
				}
				if ($item=='selectAll')
				{
					echo '<input type="image" src="../images/selectAll.png" name="selectAll" value="Todos!" onclick="mark(1,this.form); return false;" />';
				}
				if ($item=='selectNone')
				{
					echo '<input type="image" src="../images/selectNone.png" name="selectNone" value="Ninguno!" onclick="mark(0,this.form); return false;" />';
				}
				if ($item=='selectInvert')
				{
					echo '<input type="image" src="../images/selectInvert.png" name="selectInvert" value="Invertido" onclick="mark(-1,this.form); return false;" />';
				}
				if ($item=='separator')
				{
					echo '<img src="../images/separator.png" />';
				}
				if ($item=='dicom')
				{
					echo '<INPUT TYPE="image" src="../images/dicom.png" VALUE="Dicom" onclick="isMarked(this.form, '."'".$link."'".', '."'delete'".');" />';
				}
				if ($item=='png')
				{
					echo '<INPUT TYPE="image" src="../images/pngFull.png" VALUE="Png Full" onclick="this.form.action='."'".$link."'".';this.form.submit();" />';
				}
				if ($item=='resize')
				{
					echo '<INPUT TYPE="image" src="../images/png.png" VALUE="Png Redimensionado" onclick="this.form.action='."'".$link."'".';this.form.submit();" />';
				}
				if ($item=='unviewed')
				{
					echo '<INPUT TYPE="image" src="../images/unviewed.png" VALUE="No Visto" onclick="isMarked(this.form, '."'".$link."'".', '."'delete'".');" />';
				}
				if ($item=='viewed')
				{
					echo '<INPUT TYPE="image" src="../images/viewed.png" VALUE="Visto" onclick="isMarked(this.form, '."'".$link."'".', '."'delete'".');" />';
				}
				if ($item=='reported')
				{
					echo '<INPUT TYPE="image" src="../images/reported.png" VALUE="Dictado" onclick="isMarked(this.form, '."'".$link."'".', '."'delete'".');" />';
				}
				if ($item=='writed')
				{
					echo '<INPUT TYPE="image" src="../images/writed.png" VALUE="Tipeado" onclick="isMarked(this.form, '."'".$link."'".', '."'delete'".');" />';
				}
				if ($item=='send')
				{
					echo '<INPUT TYPE="image" src="../images/send.png" VALUE="Enviar" onclick="PopupCenter('."'$link&aetpk='+nodes.value+'&data='+data(study), 'Nodes', 500, 200);".'"'.'/>';
				}
				if ($item=='nodes')
				{
					$aeNodes = new DB("ae", "pk", "pacsdb");
					echo $aeNodes->fillComboDB($aeNodes->doSql("SELECT * FROM ae WHERE aet not in('BIOPACS', 'CDRECORD') AND ae_desc is not NULL"), "ae_desc", "pk", "nodes", $aeNodes->actualResults, NULL);
				}
				if ($item=='activateSubModule')
				{
					echo '<INPUT TYPE="image" src="../images/reported.png" VALUE="activateSubModule" onclick="PopupCenter2('."'".$link."'".',this.form, '."'activateSubModule'".',300, 300);" />';
					//echo '<INPUT TYPE="image" src="../images/iconMenu/ex_despachado.png" VALUE="despachado" onclick="isMarked(this.form, '."'".$link."'".', '."'delete'".');" />';
				}
				if ($item=='desactivateSubModule')
				{
					echo '<INPUT TYPE="image" src="../images/unviewed.png" VALUE="desactivateSubModule" onclick="PopupCenter2('."'".$link."'".',this.form, '."'desactivateSubModule'".',300, 300);" />';
					//echo '<INPUT TYPE="image" src="../images/iconMenu/ex_despachado.png" VALUE="despachado" onclick="isMarked(this.form, '."'".$link."'".', '."'delete'".');" />';
				}
				echo '</td>';
			}
		}
		if($this->controls['labels'])
		{
			echo '</tr><tr>';
			foreach($this->controls['schema'] as $id=>$data)
			{
				$item = $data['control'];
				$link = $data['link'];
				if ($item != NULL)
				{
					echo '<td>';
					if ($item=='insert')
					{
						echo 'Insertar';
					}
					if ($item=='insertMany')
					{
						echo 'Insertar Muchos';
					}
					if ($item=='delete')
					{
						echo 'Eliminar';
					}
					if ($item=='update')
					{
						echo 'Modificar';
					}
					if ($item=='use')
					{
						echo 'Usar';
					}
					if ($item=='back')
					{
						echo 'Atras';
					}
					if ($item=='refresh')
					{
						echo 'Recargar';
					}
					if ($item=='selectAll')
					{
						echo 'Todos!';
					}
					if ($item=='selectNone')
					{
						echo 'Ninguno!';
					}
					if ($item=='selectInvert')
					{
						echo 'Invertido!';
					}
					if ($item=='separator')
					{
						echo '';
					}
					if ($item=='dicom')
					{
						echo 'Dicom';
					}
					if ($item=='png')
					{
						echo 'Png Full';
					}
					if ($item=='resize')
					{
						echo 'Png Redi.';
					}
					if ($item=='unviewed')
					{
						echo 'No Visto';
					}
					if ($item=='viewed')
					{
						echo 'Visto';
					}
					if ($item=='reported')
					{
						echo 'Dictado';
					}
					if ($item=='writed')
					{
						echo 'Tipeado';
					}
					if ($item=='send')
					{
						echo '';
					}
					if ($item=='nodes')
					{
						echo 'Enviar a Nodo';
					}
					if ($item=='activateSubModule')
					{
						echo 'Activar';
					}
					if ($item=='desactivateSubModule')
					{
						echo 'Desactivar';
					}

					
					echo '</td>';
				}
			}		
		}
		echo '</tr></table>';
?>
