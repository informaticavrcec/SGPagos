<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/general.css" />
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/caja.js"></script>
<title><?=$page_title?></title>
</head>

<body>
<?=($error)?'<div class="alert">'.$error.'<input type="button" id="close" value="Cerrar"></div>':'';?>
<?=($this->session->flashdata('error_desc'))?'<div class="alert left">Tiene los siguientes errores:<br><ul>'.$this->session->flashdata('error_desc').'</li><input type="button" id="close" value="Cerrar"></div>':''; ?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td class="titulo" >Descuentos</td>
</tr>
<tr>
<td>Listado general</td>
</tr>
<tr>
<td height="40"><strong>DESCUENTOS GENERALES</strong></td>
</tr>
</table>
<form method="post" action="/caja/descuento/<?=$idpostulacionitem?>/<?=$id_seccion?>">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
  <th width="40">&nbsp;</th>
<th width="80">Valor</th>
<th width="30">&nbsp;</th>
<th>Detalle</th>
</tr><?
foreach($descuentos as $value){
	?>
    <tr class="row">
      <td align="center"><input type="radio" onclick="submit()" name="descuento" value="<?=$value['id_descuento']?>" /></td>
    <td align="center"><?=number_format($value['valor'],2,',','.')?> <?=($value['tipo'])?'%':'';?></td>
    <td align="center"><?=($value['sino'])?'<input type="checkbox" checked="checked" disabled="disabled">':'<input type="checkbox" disabled="disabled">'?></td>
    <td><strong><?=$value['nombre']?></strong>  <?=$value['comentario']?></td>
    </tr><?
}?>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td height="40"><strong>DESCUENTOS SECCIÓN</strong></td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result" >
<tr>
  <th width="40">&nbsp;</th>
<th width="80">Valor</th>
<th width="30">&nbsp;</th>
<th>Detalle</th>
</tr><?
foreach($descuentos_seccion as $value){
	?>
    <tr class="row">
      <td align="center"><input type="radio" onclick="submit()" name="descuento" value="<?=$value['id_descuento']?>" /></td>
    <td align="center"><?=number_format($value['valor'],2,',','.')?> <?=($value['tipo'])?'%':'';?></td>
    <td align="center"><?=($value['sino'])?'<input type="checkbox" checked="checked" disabled="disabled">':'<input type="checkbox" disabled="disabled">'?></td>
    <td><strong><?=$value['nombre']?></strong>  <?=$value['comentario']?></td>
    </tr><?
}?>
</table>
</form>
<form method="post" action="/caja/descuentolibre/<?=$idpostulacionitem?>/<?=$id_seccion?>">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td height="40" colspan="2"><strong>DESCUENTOS LIBRES</strong></td>
</tr>
<tr>
<td width="135"><strong>Descuento</strong> (sin signo) </td>
<td><input type="text" name="monto" value="" size="10" /> <select name="tipo">
<optgroup label="Tipo">
<option value="0">Pesos</option>
<option value="1">Porcentaje</option>
</optgroup>
</select> <input type="text" value="" name="motivo" placeholder="Escriba aqui el motivo" size="90" /></td>
</tr>
<tr>
<td height="40" ><input type="submit" value="Agregar descuento" /> </td>
</table>
<input type="hidden" name="libre" value="libre" />
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result" >
<tr>
  <th width="40">#</th>
<th width="80">Valor</th>
<th width="30">&nbsp;</th>
<th width="80">Antes</th>
<th width="80">Despues</th>
<th>Motivo</th>
</tr><?
$c = 1 ;
foreach($descuentos_libres as $value){
	?>
    <tr class="row" title="<?=$value['NombreApellido']?> / <?=$value['creado']?>">
    <td align="center"><strong><?=$c?></strong></td>
    <td align="center"><?=number_format($value['valor'],2,',','.')?></td>
    <td align="center"><?=($value['tipo']=='0')?'$':'%'?></td>
    <td align="center"><?=number_format($value['antes'],2,',','.')?></td>
    <td align="center"><?=number_format($value['despues'],2,',','.')?></td>
    <td><?=$value['motivo']?></td>
    </tr><?
	$c++;
}?>
</table>
</form>
</body>
</html>
