<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/general.css" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/colorbox.css" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/datepicker.css" />
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/caja.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/datepicker.js"></script>
<script type="text/javascript">
$(document).ready(function(){	
	<?
	foreach(range(0,($rango-1)) as $value){
		?>	
		$('#calendar<?=$value?>').DatePicker({
			format:'d/m/Y',
			date: $(this).val(),
			current:$('#calendar<?=$value?>').val() ,
			starts: 1,
			position: 'right',
			onBeforeShow: function(){			
				$('#calendar<?=$value?>').DatePickerSetDate($('#calendar<?=$value?>').val(), true);
			},
			onChange: function(formated, dates){
				$('#calendar<?=$value?>').val(formated);			
			}
		});<?
	}?>
	
});
</script>
<title>
<?=$page_title?>
</title>
</head>

<body>
<?=($this->session->flashdata('error'))?'<div class="alert"><div class="left">Tiene los siguientes errores :<br /><br />'.$this->session->flashdata('error').'</div><input type="button" id="close" value="Cerrar"></div>':'';?>
<form method="post" action="/caja/cantidaddoctos/3860/<?=$idpostulacionitem?>">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td colspan="2">&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td width="145" ><strong>Cantidad depositos</strong></td>
<td><select name="cant" onchange="submit();">
<optgroup label="Cantidad de despositos"><?
foreach($this->config->item('total_depositos') as $value){
	?>
    <option value="<?=$value?>" <?=($value==$rango)?'selected="selected"':'';?>><?=$value?></option><?
}?>
</optgroup>
</select></td>
</tr>
</table>
</form>
<form method="post" id="form_validate" action="/caja/registrarpago/3860/<?=$idpostulacionitem?>">
<input type="hidden" name="cant" value="<?=$rango?>" /><?
foreach(range(1,$rango) as $key => $value){		
	?>
    <fieldset>
    <legend><strong>DEPOSITO NRO. <?=($key+1)?></strong></legend>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" >        
    <tr>
    <td width="135"><strong>Tipo de documento</strong></td>
    <td><select  name="tipo_documento[<?=$key?>]" class="obl tipo_documento">
    <option value=""></option>
    <optgroup label="Tipo de documento">
    <option value="0" <?=($pagados[$key]['IDTipoDocumento']=='0')?'selected="selected"':'';?> >Boleta</option>
    <option value="1" <?=($pagados[$key]['IDTipoDocumento']=='1')?'selected="selected"':'';?> >Factura</option>
    </optgroup>
    </select></td>
    </tr>
	<tr>
	<td><strong>Nro. de comprobante</strong></td>
    <td><input type="text" name="comprobante[<?=$key?>]" value="<?=$pagados[$key]['NumeroComprobante']?>" style="width:97.5%;" class="obl ctacte" /></td>
	</tr>
	<tr>
	<td><strong>Fecha</strong></td>
    <td><input type="text" name="fecha[<?=$key?>]" id="calendar<?=$key?>" value="<?=(!$pagados[$key]['FechaDeposito'])?date('d/m/Y'):$pagados[$key]['FechaDeposito']?>" class="center calendar" size="10" readonly="readonly"  /></td>
	</tr>
	<tr>
	  <td><strong>Monto</strong></td>
	  <td><input type="text" name="monto[]" value="<?=number_format($pagados[$key]['Monto'],0,',','.')?>" style="width:97.5%;" class="obl right" maxlength="10" /> 
      <input type="hidden"  name="id_pago[<?=$key?>]" value="<?=$pagados[$key]['IDPagoDeposito']?>"/></td>
    </tr>
	</table>
	</fieldset><?	
}?>
<table width="100%" border="0" cellpadding="1" cellspacing="0" align="center" >
<tr>
<td colspan="2" height="40"> <input type="button" id="validate" value="Guardar" /></td>
</tr>
</table>
</form>
</body>
</html>
