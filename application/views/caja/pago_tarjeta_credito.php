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
<title>
<?=$page_title?>
</title>
</head>

<body>
<?=($this->session->flashdata('error'))?'<div class="alert"><div class="left">Tiene los siguientes errores :<br /><br />'.$this->session->flashdata('error').'</div><input type="button" id="close" value="Cerrar"></div>':'';?>
<form method="post" action="/caja/cantidaddoctos/1441/<?=$idpostulacionitem?>">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td colspan="2">&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td width="135" ><strong>Cantidad de tarjetas</strong></td>
<td><select name="cant" onchange="submit();">
<optgroup label="Cantidad de tarjetas"><?
foreach($this->config->item('total_tarjeta_debito') as $value){
	?>
    <option value="<?=$value?>" <?=($value==$rango)?'selected="selected"':'';?>><?=$value?></option><?
}?>
</optgroup>
</select></td>
</tr>
</table>
</form>
<form method="post" id="form_validate" action="/caja/registrarpago/1441/<?=$idpostulacionitem?>">
<input type="hidden" name="cant" value="<?=$rango?>" /><?
foreach(range(1,$rango) as $key => $value){		
	?>
    <fieldset>
    <legend><strong>TARJETA DE CREDITO NRO. <?=($key+1)?></strong></legend> 
    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" >       
    <tr>
    <td><strong>Tipo de documento</strong></td>
    <td><select  name="tipo_documento[<?=$key?>]" class="obl tipo_documento">
    <option value=""></option>
    <optgroup label="Tipo de documento">
    <option value="0" <?=($pagados[$key]['IDTipoDocumento']=='0')?'selected="selected"':'';?> >Boleta</option>
    <option value="1" <?=($pagados[$key]['IDTipoDocumento']=='1')?'selected="selected"':'';?> >Factura</option>
    </optgroup>
    </select></td>
    </tr>
	<tr>
	<td width="135"><strong>Banco</strong></td>
    <td><select name="banco[<?=$key?>]" class="obl bco">
    <option value=""></option>
    <optgroup label="Bancos"><?
	foreach($bancos as $value){
		?>
        <option value="<?=$value['IDMetadatoDetalle']?>" <?=(($pagados[$key]['Banco'] == $value['IDMetadatoDetalle']) AND (count($pagados) > 0))?'selected="selected"':'';?> ><?=$value['dscrn']?></option><?
	}?>    
    </optgroup>
    </select></td>
	</tr>
    <tr>
	<td width="135"><strong>Tarjeta</strong></td>
    <td><select name="tarjeta[<?=$key?>]" class="obl tar">
    <option value=""></option>
    <optgroup label="Tarjetas"><?
	foreach($tarjetas as $value){
		?>
        <option value="<?=$value['IDMetadatoDetalle']?>" <?=(($pagados[$key]['IDTarjeta'] == $value['IDMetadatoDetalle']) AND (count($pagados) > 0))?'selected="selected"':'';?> ><?=$value['nmbre']?></option><?
	}?>    
    </optgroup>
    </select></td>
	</tr>
	<tr>
	<td><strong>Nro. Tarjeta</strong></td>
    <td>
    <input type="text" name="cuatro[<?=$key?>]" value="<?=$pagados[$key]['NumTarjeta4']?>" size="10" maxlength="4" class="obl cuatro center" />
    </td>
	</tr>
	<!--<tr>
	<td><strong>Vencimiento mes</strong></td>
    <td>
    <input type="text" name="mes[<?=$key?>]" value="<?=$pagados[$key]['VencimientoMM']?>" size="10" maxlength="2" class="obl mes center" />
    </td>
	</tr>
    <tr>
	<td><strong>Vencimiento año</strong></td>
    <td>
    <input type="text" name="anio[<?=$key?>]" value="<?=$pagados[$key]['VencimientoAA']?>" size="10" maxlength="2" class="obl anio center" />
    </td>
	</tr>-->
    <tr>
	<td><strong>Numero transacción</strong></td>
    <td>
    <input type="text" name="transaccion[<?=$key?>]" value="<?=$pagados[$key]['NumTransaccion']?>" style="width:97.5%;" class="obl num" />
    </td>
	</tr>
	<tr>
	  <td><strong>Monto</strong></td>
	  <td><input type="text" name="monto[]" value="<?=number_format($pagados[$key]['Monto'],0,',','.')?>" style="width:97.5%;" class="obl right" maxlength="10" /> 
      <input type="hidden"  name="id_pago[<?=$key?>]" value="<?=$pagados[$key]['IDPagoTarjeta']?>"/></td>
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
