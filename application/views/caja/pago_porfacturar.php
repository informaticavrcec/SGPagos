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
	
	$('#calendar1').DatePicker({
		format:'d/m/Y',
		date: $(this).val(),
		current:$('#calendar1').val() ,
		starts: 1,
		position: 'right',
		onBeforeShow: function(){			
			$('#calendar1').DatePickerSetDate($('#calendar1').val(), true);
		},
		onChange: function(formated, dates){
			$('#calendar1').val(formated);			
		}
	});
	
	$('#calendar2').DatePicker({
		format:'d/m/Y',
		date: $(this).val(),
		current:$('#calendar2').val() ,
		starts: 1,
		position: 'right',
		onBeforeShow: function(){			
			$('#calendar2').DatePickerSetDate($('#calendar2').val(), true);
		},
		onChange: function(formated, dates){
			$('#calendar2').val(formated);			
		}
	});
	
	var have = '<?=$tiene_otic?>';
	if(have){
		$("#otic").show();
	}
	
	$("input:radio").click(function(){
		
		if($(this).val() == 's'){
			$("#otic").show();
			$("#otic input,#otic select,#otic textarea").removeAttr("disabled");	
		}else{
			$("#otic").hide();
			$("#otic input,#otic select,#otic textarea").attr("disabled",true);
		}
		
	});
	
});
</script>
<title>
<?=$page_title?>
</title>
</head>

<body>
<?=($this->session->flashdata('error'))?'<div class="alert"><div class="left">Tiene los siguientes errores :<br /><br />'.$this->session->flashdata('error').'</div><input type="button" id="close" value="Cerrar"></div>':'';?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td>&nbsp;</td>
</tr>
</table>
<fieldset>
<legend><strong>PAGO POR FACTURAR EMPRESA</strong></legend>
<form method="post" action="/caja/registrarpago/3811/<?=$idpostulacionitem?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td height="20" width="150"><strong>Tipo documento</strong></td>
<td><input type="hidden" name="tipo_documento_empresa" value="1" /> 
<?=($facturar_empresa->IDTipoDocumento == 1)?'Factura':'';?></td>
</tr>
<tr>
<td><strong>Descripción</strong></td>
<td><textarea name="descripcion_empresa" style="width:97%" rows="4"><?=$facturar_empresa->Descripcion?></textarea></td>
</tr>
<tr>
<td ><strong>Nro Factura</strong></td>
<td><input type="text" name="factura_empresa" value="<?=$facturar_empresa->NumFactura?>" class="right" style="width:97.5%" maxlength="10" /></td>
</tr>
<tr>
<td><strong>Fecha pago</strong></td>
<td><input type="text" name="fecha_empresa" id="calendar1" value="<?=(!$facturar_empresa->FechaPago)?date('d/m/Y'):$facturar_empresa->FechaPago;?>" class="center calendar" size="10" readonly="readonly"  /></td>
</tr>
<tr>
<td ><strong>Empresa</strong></td>
<td><select name="empresa" class="obl" style="width:97.5%">
<option value=""></option>
<optgroup label="Empresas"><?
foreach($empresas as $value){
    ?>
    <option value="<?=$value['IDEmpRegistrada']?>" <?=($facturar_empresa->IDEmpresa == $value['IDEmpRegistrada'])?'selected="selected"':'';?> ><?=$value['RSocial']?></option><?
}?>    
</optgroup>
</select></td>
</tr>
<tr>
<td ><strong>Numero Orden compra</strong></td>
<td><input type="text" name="orden_empresa" value="<?=$facturar_empresa->NOC_Empresa?>" class="right" style="width:97.5%" maxlength="10" /></td>
</tr>
<tr>
<td ><strong>Monto</strong></td>
<td><input type="text" name="monto_empresa" value="<?=number_format($facturar_empresa->Monto,0,',','.')?>" class="right" style="width:97.5%" maxlength="10" /></td>
</tr>
<tr>
<td height="20" ><strong>¿ Con OTIC ?</strong></td>
<td>
Si <input type="radio" name="conotic" value="s" <?=($tiene_otic=='s')?'checked="checked"':'';?> /> /
<input type="radio" name="conotic" value="n" <?=(!$tiene_otic)?'checked="checked"':'';?> /> No</td>
</tr>
</table>
</fieldset>

<!--OTIC-->

<div id="otic" style="display:none" >
<fieldset>
<legend><strong>PAGO POR FACTURAR OTIC</strong></legend>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td height="20"><strong>Tipo documento</strong></td>
<td><input type="hidden" name="tipo_documento_otic" value="1" /> 
<?=($facturar_otic->IDTipoDocumento == 1)?'Factura':'';?></td>
</tr>
<tr>
<td><strong>Descripción</strong></td>
<td><textarea name="descripcion_otic" style="width:97%" rows="4"><?=$facturar_otic->Descripcion?></textarea></td>
</tr>
<tr>
<td width="150"><strong>Nro Factura</strong></td>
<td><input type="text" name="factura_otic" value="<?=$facturar_otic->NumFactura?>" class="right" style="width:97.5%" maxlength="10" /></td>
</tr>
<tr>
<td><strong>Fecha</strong></td>
<td><input type="text" name="fecha_otic" id="calendar2" value="<?=(!$facturar_otic->FechaPago)?date('d/m/Y'):$facturar_otic->FechaPago;?>" class="center calendar" size="10" readonly="readonly"  /></td>
</tr>
<tr>
<td width="135"><strong>OTIC</strong></td>
<td><select name="otic" class="obl" style="width:97.5%">
<option value=""></option>
<optgroup label="Otics"><?
foreach($otics as $value){
    ?>
    <option value="<?=$value['IDOtic']?>" <?=($facturar_otic->IDOTIC == $value['IDOtic'])?'selected="selected"':'';?> ><?=$value['Nombre']?></option><?
}?>    
</optgroup>
</select></td>
</tr>
<tr>
<td width="150"><strong>Numero Orden compra</strong></td>
<td><input type="text" name="orden otic" value="<?=$facturar_otic->NOC_Otic?>" class="right" style="width:97.5%" maxlength="10" /></td>
</tr>
<tr>
<td width="150"><strong>Monto</strong></td>
<td><input type="text" name="monto_otic" value="<?=number_format($facturar_otic->Monto,0,',','.')?>" class="right" style="width:97.5%" maxlength="10" /></td>
</tr>
</table>
</fieldset>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td height="40"><input type="submit" value="Guardar" /></td>
</tr>
</table>
<input type="hidden" name="id_empresa" value="<?=$facturar_empresa->IDPagoPorFacturar?>" />
<input type="hidden" name="id_otic" value="<?=$facturar_otic->IDPagoPorFacturar?>" />
</form>
</body>
</html>
