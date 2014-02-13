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
<script type="text/javascript">
$(document).ready(function(){
	$("a.popup_short2").colorbox({
		iframe:true,
		width : 500,
		height : 350,
		onClosed : function(){
			$("form").submit();	
		}
	});
});
</script>
<title>
<?=$page_title?>
</title>
</head>

<body id="body" onload="document.getElementById('rut').focus();">
<?=$menuusuario?>
<?=validation_errors('<div class="alert">','<input type="button" id="close" value="Cerrar"></div>'); ?>
<?=($flash_error)?'<div class="alert" >'.$flash_error.'<input type="button" id="close" value="Cerrar"></div>':'';?>
<form method="post">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td class="titulo" colspan="2" >Asignar boletas / Facturas</td>
</tr>
<tr>
<td colspan="2">Listar actividades</td>
</tr>
<tr>
<td height="40" width="125"><strong>Rut</strong>  </td>
<td><input type="text" id="rut" name="rut" value="<?=set_value('rut')?>" size="11" class="center" /> <input type="submit" value="Buscar" /> | <?=$nombres?> <?=$apellidos?></td>
</tr>
</table>
</form>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="40">#</th>
<th>Nombre actividad</th>
<th width="80">Estado</th>
<th width="80">Inicio</th>
<th width="80">Termino</th>
<th width="80">Monto</th>
<th width="80">Boleta</th>
<th width="80">Factura</th>
</tr><?
if(count($actividades) < 1){
	?>
    <tr>
    <td colspan="9">NADA PARA LISTAR</td>
    </tr><?
}else{
	$c = 1;
	foreach($actividades as $value){
		?>
        <tr class="row" title="ID: <?=$value['IDSeccion']?>">
        <td align="center"><strong><?=$c?></strong></td>
        <td><?=$value['Nombre_Curso']?></td>
        <td><?=$value['estado']?></td>
        <td align="center"><?=$value['FechaInicio']?></td>
        <td align="center"><?=$value['FechaTermino']?></td>
        <td align="right"><strong style="float:left">$</strong><strong><?=number_format($value['ValorNuevo'],0,',','.')?></strong></td>
        <td align="center"><?=$value['boleta']?></td>
        <td align="center"><?=$value['factura']?></td>
        </tr><?
		$c++;
	}
}?>	
</table>
</body>
</html>
