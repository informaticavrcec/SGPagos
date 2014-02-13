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
$(document).ready(function() {
	<?=$datepicker1;?>
	<?=$datepicker2;?>
});
</script>
<title>
<?=$page_title?>
</title>
</head>

<body>
<?=($this->session->flashdata('error'))?'<div class="alert"><div class="left">Tiene los siguientes errores :<br /><br />'.$this->session->flashdata('error').'</div><input type="button" id="close" value="Cerrar"></div>':'';?>
<form method="post" action="/caja/cantidaddoctos/1439/<?=$idpostulacionitem?>">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td colspan="2">&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td width="145" ><strong>Cantidad de cheques</strong></td>
<td><select name="cant" onchange="submit();">
<optgroup label="Cantidad de cheques"><?
foreach($this->config->item('total_cheques_dia') as $value){
	?>
    <option value="<?=$value?>" <?=($value==$rango)?'selected="selected"':'';?>><?=$value?></option><?
}?>
</optgroup>
</select></td>
</tr>
</table>
</form>
<form method="post" id="form_validate" action="/caja/registrarpago/1439/<?=$idpostulacionitem?>">
<input type="hidden" name="cant_cheques" value="<?=$rango?>" /><?
foreach(range(1,$rango) as $key => $value){
	
	if(count($pagados) < ($key+1)){
		$pagados[$key]['Banco'] = NULL;		
		$pagados[$key]['IDPagoCheque'] = NULL;
		$pagados[$key]['IDTipoDocumento'] = NULL;
		$pagados[$key]['CuentaCorriente'] = NULL;
		$pagados[$key]['NSerie'] = NULL;
		$pagados[$key]['Monto'] = NULL;
	}		
	?>
    <fieldset>
    <legend><strong>CHEQUE DÍA NRO. <?=($key+1)?></strong></legend>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" >         
    <tr>
    <td><strong>Tipo de documento</strong></td>
    <td><select  name="tipo_documento[]" class="obl tipo_documento">
    <option value=""></option>
    <optgroup label="Tipo de documento">
    <option value="0" <?=($pagados[$key]['IDTipoDocumento']=='0')?'selected="selected"':'';?> >Boleta</option>
    <option value="1" <?=($pagados[$key]['IDTipoDocumento']=='1')?'selected="selected"':'';?> >Factura</option>
    </optgroup>
    </select></td>
    </tr>
	<tr>
	<td width="135"><strong>Banco</strong></td>
    <td><select name="banco[]" class="obl bco">
    <option value=""></option>
    <optgroup label="Bancos"><?
	foreach($bancos as $value){
		?>
        <option value="<?=$value['IDMetadatoDetalle']?>" <?=(($pagados[$key]['Banco'] == $value['IDMetadatoDetalle']) AND (count($pagados) > 0))?'selected="selected"':'';?> ><?=$value['dscrn']?></option><?
	}?>    
    </optgroup>
    </select></td>
	</tr>
	<!--<tr>
	<td><strong>Cuenta corriente</strong></td>
    <td><input type="text" name="ctacte[]" value="<?=$pagados[$key]['CuentaCorriente']?>" style="width:97.5%;" class="ctacte" /> sacar</td>
	</tr>-->
	<tr>
	<td><strong>Nro de serie</strong></td>
    <td><input type="text" name="serie[]" value="<?=$pagados[$key]['NSerie']?>" style="width:97.5%;" /></td>
	</tr>
	<tr>
	<td><strong>Fecha</strong></td>
    <td><input type="text" id="calendar<?=($key+1)?>" name="fecha[]" value="<?=(!$pagados[$key]['Fecha'])?date('d/m/Y'):$pagados[$key]['Fecha']?>" class="center" size="10" readonly="readonly"  /></td>
	</tr>
	<tr>
	<td><strong>Monto</strong></td>
    <td><input type="text" name="monto[]" value="<?=number_format($pagados[$key]['Monto'],0,',','.')?>" style="width:97.5%;" class="obl right" maxlength="10" /> 
    <input type="hidden"  name="id_pago[<?=$key?>]" value="<?=$pagados[$key]['IDPagoCheque']?>"/></td>
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
