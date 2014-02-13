<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/general.css" media="screen" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/general-print.css" media="print" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/colorbox.css" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/datepicker.css" />
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/datepicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/caja.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	$('#desde').DatePicker({
		format:'d/m/Y',
		date: $('#desde').val(),
		current: $('#desde').val(),
		starts: 1,
		position: 'right',
		onBeforeShow: function(){
			$('#desde').DatePickerSetDate($('#desde').val(), true);
		},
		onChange: function(formated, dates){
			$('#desde').val(formated);			
		}
	});
	
	$('#hasta').DatePicker({
		format:'d/m/Y',
		date: $('#hasta').val(),
		current: $('#hasta').val(),
		starts: 1,
		position: 'right',
		onBeforeShow: function(){
			$('#hasta').DatePickerSetDate($('#hasta').val(), true);
		},
		onChange: function(formated, dates){
			$('#hasta').val(formated);			
		}
	});
	
});
</script>
<title>
<?=$page_title?>
</title>
</head>

<body id="body">
<div class="noprint">
<?=$menuusuario?>
<?=(validation_errors())?'<div class="alert left">Tiene los siguientes errores:<br><ul>'.validation_errors('<li class="left">','</li>').'</li><input type="button" id="close" value="Cerrar"></div>':''; ?>
</div>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
  <tr>
    <td colspan="2" class="titulo">Informe de Caja <?=($seleccion3=='1')?'Facturas':'';?> <?=($seleccion3=='0')?'Boletas':'';?> </td>
  </tr>
</table>
<form method="post" action="/caja/informe">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr class="noprint">
      <td height="20">Generar informe</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="20">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="125" height="20"><strong>Asistente</strong></td>
      <td><select name="cajero" >
          <option value=""></option>
          <optgroup label="Cajeros"><?
		  foreach($cajeros as $value){
			  ?>
              <option value="<?=$value['id_usuario']?>" <?=($seleccion == $value['id_usuario'])?'selected="selected"':'';?> >
          <?=$value['NombreApellido']?></option><?
			  if($seleccion == $value['id_usuario']){
				  $asistente = $value['NombreApellido'];
			  }
		  }?>
          </optgroup>
        </select> <span class="only-print"><?=$asistente?></span></td>
    </tr>
    <tr>
      <td height="20"><strong>Desde / Hasta</strong></td>
      <td><input type="text" name="desde" id="desde" size="10" value="<?=$desde?>" readonly="readonly" class="center"  />
        
        <input type="text" name="hasta" id="hasta" size="10" value="<?=$hasta?>" readonly="readonly" class="center"  />
        <span class="only-print"><?=$desde?> - <?=$hasta?></span>
        </td>
    </tr>
    <tr>
      <td height="20"><strong>Informe</strong></td>
      <td><select name="pago" >
          <option value="10000" <?=($seleccion2 == 10000)?'selected="selected"':'';?> >Resumen general</option>
          <optgroup label="Formas de pago"><?
		  foreach($formas_pago as $value){
			  ?>
              <option value="<?=$value['IDMetadatoDetalle']?>" <?=($seleccion2 == $value['IDMetadatoDetalle'])?'selected="selected"':'';?> ><?=$value['nmbre']?></option><?
			  if($seleccion2 == $value['IDMetadatoDetalle']){
				  $informe = $value['nmbre'];
			  }
			  if($seleccion2 == 10000){
				  $informe = 'Resumen general';
			  }
		  }?>
          </optgroup>
        </select> <span class="only-print"><?=$informe?></span></td>
    </tr>
    <tr class="noprint">
      <td><strong>Documento</strong></td>
      <td><select  name="tipo_documento" >
          <option value=""></option>
          <optgroup label="Tipo de documento">
          <option value="0" <?=($seleccion3==='0')?'selected="selected"':'';?> >Boleta</option>
          <option value="1" <?=($seleccion3=='1')?'selected="selected"':'';?> >Factura</option>
          </optgroup>
        </select></td>
    </tr>
    <tr>
      <td height="23"><strong>Fecha emisión</strong></td>
      <td><?=date('d/m/Y H:i:s')?></td>
    </tr>
    <tr >
    <td colspan="2" height="40"><input type="submit" value="Generar reporte" /> <input type="button" value="Imprimir" onclick="window.print() ;" /></td>
    </tr>
  </table>
</form>

</body>
</html>
