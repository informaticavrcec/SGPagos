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

<body>
<?=validation_errors('<div class="alert">','<input type="button" id="close" value="Cerrar"></div>'); ?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td class="titulo" >Asignar Factura</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</table>
<form method="post" action="/asignarboletasfacturas/factura/<?=$idpostulacionitem?>">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td  align="right"><strong>Numero</strong></td>
</tr>
<tr>
<td align="right"><input type="text" name="numero_factura" style="font-size:18px;width:98%;background-color:#FFC;" class="right" value="<?=($numero_factura)?$numero_factura:set_value('numero_factura');?>"  /></td>
</tr>
<tr>
<td align="right"><strong>Monto</strong></td>
</tr>
<tr>
<td align="right" style="font-size:18px;" class="blue" ><span style="float:left">$</span><?=number_format($total_facturas,0,',','.')?></td>
</tr>
<tr>
<td height="40" align="right"><input type="submit" value="Guardar" /> <?
if($numero_boleta){
	?>
    <input type="button" value="Imprimir" /><?
}?></td>
</tr>
<tr>
<td align="center"><?=($message)?'<span class="text_alert">'.$message.'</span>':'Verifique monto(s) antes de asignar numero.'; ?></td>
</tr>
</table>
</body>
</html>
