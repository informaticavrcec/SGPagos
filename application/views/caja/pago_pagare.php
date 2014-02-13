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
<script type="text/javascript" src="<?=base_url()?>assets/js/datepicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/caja.js"></script>
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
<form method="post" action="/caja/cantidaddoctos/1442/<?=$idpostulacionitem?>">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td colspan="2">&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td width="145" ><strong>Cantidad de cuotas</strong></td>
<td><select name="cant" onchange="submit();">
<optgroup label="Cantidad de cuotas"><?
foreach($this->config->item('total_pagare') as $value){
	?>
    <option value="<?=$value?>" <?=($value==$rango)?'selected="selected"':'';?>><?=$value?></option><?
}?>
</optgroup>
</select></td>
</tr>
</table>
</form>
<form method="post" id="form_validate" action="/caja/registrarpago/1442/<?=$idpostulacionitem?>">
<input type="hidden" name="cant" value="<?=$rango?>" /><?
foreach(range(1,$rango) as $key => $value){
	
	if($pagados[$key]['Fecha']){		
		$fecha = $pagados[$key]['Fecha'];
	}else{
		$fecha = date('d/m/Y');
	}
	
	if($key > 0){
		$dias = $key * 30;
		list($day,$mon,$year) = explode('/',$fecha);
        $nueva_fecha = date('d/m/Y',mktime(0,0,0,$mon,$day+$dias,$year));	
	}else{
		$nueva_fecha = date('d/m/Y');	
	}
			
	?>
    <fieldset>
    <legend><strong>PAGARE NRO. <?=($key+1)?></strong></legend> 
    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" >      
    <tr>
    <td><strong>Tipo de documento</strong></td>
    <td><select  name="tipo_documento[<?=$key?>]" class="obl tipo_documento">
    <option value=""></option>
    <optgroup label="Tipo de documento">
    <option value="0" selected="selected"  >Boleta</option>    
    </optgroup>
    </select></td>
    </tr>
	<tr>
	<td width="135" height="20"><strong>Legalizado</strong></td>
    <td>
    <input type="radio" name="legalizado[<?=$key?>]" <?=($pagados[$key]['bLegalizado'])?'checked="checked"':'';?> value="1" /> Si/ No 
    <input type="radio" name="legalizado[<?=$key?>]" <?=(!$pagados[$key]['bLegalizado'])?'checked="checked"':'';?> value="NULL" /></td>
	</tr>
	<tr>
	<td><strong>Numero pagare</strong></td>
    <td><input type="text" name="numero_pagare[<?=$key?>]" value="<?=$pagados[$key]['NumPagare']?>" style="width:97.5%;" class="obl pagare" /></td>
	</tr>
	<tr>
	<td><strong>Monto interes cuota</strong></td>
    <td><input type="text" name="interes[<?=$key?>]" value="<?=number_format($pagados[$key]['MontoInteresCuota'],0,',','.')?>" style="width:97.5%;" class="obl right" /></td>
	</tr>
	<tr>
	<td><strong>Fecha vcto.</strong></td>
    <td><input type="text" name="fecha[<?=$key?>]" id="calendar<?=$key?>" value="<?=(!$pagados[$key]['FechaVencimiento'])?$nueva_fecha:$pagados[$key]['FechaVencimiento']?>" class="center calendar" size="10" readonly="readonly"  /></td>
	</tr>    
    
	<tr>
	<td><strong>Monto cuota</strong></td>
    <td><input type="text" name="monto[]" value="<?=number_format($pagados[$key]['MontoCuota'],0,',','.')?>" style="width:97.5%;" class="obl right" maxlength="10" /> 
    <input type="hidden"  name="id_pago[<?=$key?>]" value="<?=$pagados[$key]['IDPagoPagare']?>"/></td>
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
