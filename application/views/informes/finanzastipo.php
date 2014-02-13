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
<?=(validation_errors())?'<div class="alert left">Tiene los siguientes errores:<br><ul>'.validation_errors('<li>','</li>').'</li><input type="button" id="close" value="Cerrar"></div>':''; ?>
</div>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
  <tr>
    <td colspan="2" class="titulo">Informe Finanzas </td>
  </tr>
</table>
<form method="post" action="/finanzas/informe">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr class="noprint">
      <td width="125" height="20">Generar informe</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="20">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="125" height="20"><strong>Matriculador</strong></td>
      <td><select name="cajero" id="cajero" >
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
      <td><select name="informe" id="informe" >         
          <optgroup label="Informes">
          <option value="informeboletas" <?=($informe == 'informeboletas' )?'selected="selected"':'';?> >Boletas</option>	
          <option value="chequesafecha" <?=($informe == 'chequesafecha' )?'selected="selected"':'';?> >Cheques a fecha</option>	  
          </optgroup>
        </select> <span class="only-print"><?=$informe?></span></td>
    </tr>
    <tr >
      <td colspan="2" height="40"><input type="submit" value="Generar reporte" /> <a href="<?=$link?>" class="btn_excel">Exportar EXCEL</a> </td>
    </tr>
  </table>
</form>

</body>
</html>
