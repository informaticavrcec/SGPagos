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

<body id="body">
<?=$menuusuario?>
<?=validation_errors('<div class="alert">','<input type="button" id="close" value="Cerrar"></div>'); ?>
<?=($this->session->flashdata('error'))?'<div class="alert" >'.$this->session->flashdata('error').'<input type="button" id="close" value="Cerrar"></div>':'';?>
<form method="get" action="/caja/cancelar/<?=$idpostulacionitem?>">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td class="titulo" >Impresión documentos</td>
</tr>
<tr>
<td height="20">Detalle</td>
</tr>
<tr>
<td height="20">&nbsp;</td>
</tr>
</table>
</form>
<?
if($suma_boletas > 0){
	?>
    <table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result" >
    <tr>
    <th align="left" colspan="3">BOLETA</th>
    </tr>
    <tr>
    <th width="40">#</th>
    <th>Tipo de pago</th>
    <th width="80">Monto</th>
    </tr><?
	$c = 1;
	foreach($boletas as $value){
		if($value['c'] > 0){
			?>
			<tr class="row">
			<td align="center"><strong><?=$c?></strong></td>
			<td><?=$value['tipo']?></td>
			<td align="right"><strong style="float:left">$</strong><strong><?=number_format($value['c'],0,',','.')?></strong></td>    
			</tr><?
			$c++;
		}
	}?>
    <tr>
    <td colspan="2"><strong>TOTALES FINALES</strong></td>
    <td align="right" class="row2"><strong style="float:left">$</strong><strong><?=number_format($suma_boletas,0,',','.')?></strong></td>
    </tr>
    </table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
    <td height="40"><a href="/asignarboletasfacturas/boleta/<?=base64_decode($idpostulacionitem)?>" class="btn_normal popup_short" >Asignar boleta</a></td>
    </tr>
    </table><?	
}
if($suma_facturas > 0){
	?>
    <table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result" >
    <tr>
    <th align="left" colspan="3">FACTURA</th>
    </tr>
    <tr>
    <th width="40">#</th>
    <th>Tipo de pago</th>
    <th width="80">Monto</th>
    </tr><?	
	$c = 1;
	foreach($facturas as $value){
		if($value['c'] > 0){
			?>
			<tr class="row">
			<td align="center"><strong><?=$c?></strong></td>
			<td><?=$value['tipo']?></td>
			<td align="right"><strong style="float:left">$</strong><strong><?=number_format($value['c'],0,',','.')?></strong></td>    
			</tr><?
			$c++;
		}
	}?>
    <tr>
    <td colspan="2"><strong>TOTALES FINALES</strong></td>
    <td align="right" class="row2"><strong style="float:left">$</strong><strong><?=number_format($suma_facturas,0,',','.')?></strong></td>
    </tr>
    </table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
    <td height="40"><a href="/asignarboletasfacturas/factura/<?=base64_decode($idpostulacionitem)?>" class="btn_normal popup_short" >Asignar factura</a></td>
    </tr>
    </table><?
}?>
</body>
</html>
