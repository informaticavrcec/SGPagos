<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/general.css" />
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/caja.js"></script>
<title>Untitled Document</title>
</head>

<body>
<?=validation_errors('<div class="alert">','<input type="button" id="close" value="Cerrar"></div>'); ?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td class="titulo" >Detalle comentarios</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</table>
<form  method="post" action="/caja/comentario">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td height="20"><strong>Agregar comentario</strong></td>
</tr>
<tr>
<td><textarea name="comentario" style="width:521px;" rows="5"></textarea></td>
</tr>
<tr>
<td height="40"><input type="submit" value="Agregar comentario" /> <input type="hidden" name="idpostulacionitem" value="<?=$idpostulacionitem?>" /></td>
</tr>
</table>
</form>
<?
foreach($comentarios  as $value){
	?>
    <table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
	<tr>
	<th align="left"><?=$value['fecha_creacion']?> | <?=$value['NombreApellido']?></th>
	</tr>
	<tr>
	<td><?=$value['observacion']?></td>
	</tr>
	</table>
	<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" >
	<tr>
    <td>&nbsp;</td>
    </tr>
	</table><?
}?>
</body>
</html>
