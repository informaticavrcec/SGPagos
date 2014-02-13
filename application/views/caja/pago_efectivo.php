<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/general.css" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/colorbox.css" />
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/caja.js"></script>
<title>
<?=$page_title?>
</title>
</head>

<body onload="document.getElementById('monto').focus();">
<?=($this->session->flashdata('error'))?'<div class="alert"><div class="left">Tiene los siguientes errores :<br /><br />'.$this->session->flashdata('error').'</div><input type="button" id="close" value="Cerrar"></div>':'';?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td>&nbsp;</td>
</tr>
</table>

<form method="post" action="/caja/registrarpago/<?=$tipopago?>/<?=$idpostulacionitem?>">
<fieldset>
    <legend><strong>EFECTIVO</strong></legend>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td width="150"><strong>Monto</strong></td>
<td><input type="text" name="monto" id="monto" value="<?=number_format($monto,0,',','.')?>" class="right" style="width:99%" maxlength="10" /></td>
</tr>
<tr>
<td><strong>Tipo documento</strong></td>
<td><select  name="tipo_documento">
<option value=""></option>
<optgroup label="Tipo de documento">
<option value="0" <?=($tipo_documento=='0')?'selected="selected"':'';?> >Boleta</option>
<option value="1" <?=($tipo_documento=='1')?'selected="selected"':'';?> >Factura</option>
</optgroup>
</select></td>
</tr>
<tr>
<td height="40"><input type="submit" value="Guardar" /></td>
</tr>
</table>
</fieldset>
</form>
</body>
</html>
