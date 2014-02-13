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

<body id="body" onload="document.getElementById('rut').focus();">
<?=$menuusuario?>
<?=validation_errors('<div class="alert">','<input type="button" id="close" value="Cerrar"></div>'); ?>
<?=($flash_error)?'<div class="alert" >'.$flash_error.'<input type="button" id="close" value="Cerrar"></div>':'';?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td class="titulo" >Sistema caja</td>
</tr>
<tr>
<td >Listar actividades</td>
</tr>
</table>
<form method="post" >
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td width="125" height="40" ><strong>Rut alumno</strong></td>
<td>
<input type="text" name="rut" id="rut" value="<?=set_value('rut')?>" maxlength="10" size="11" style="text-align:center;" />
<input type="submit" value="Buscar" /> | <?=$nombres?> <?=$apellidos?>
</td>
</tr>

</table>
</form>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" >
<tr>
<td width="125"><strong>Buscar actividad</strong></td>
<td><input type="text" id="buscar_act" size="80" placeholder="Buscar por actividad" /></td>
</tr>
<tr>
<td colspan="2"><strong>ACTIVIDADES DISPONIBLES PARA CANCELAR</strong> | Listando <strong><?=count($actividades_apagar)?></strong> registros</td>
</tr>
</table>
<form method="post" action="/caja/cancelar">
<input type="hidden" name="rut" value="<?=set_value('rut')?>" />
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="40">#</th>
<th width="80">Tipo</th>
<th>Actividad</th>
<th width="80">Inicio</th>
<th width="80">Termino</th>
<th width="80">Estado</th>
<th width="100">Valor Inicial</th>
<th width="100">Valor Final</th>
<th width="100">Seleccion</th>
</tr><?
$c = 1;
if(count($actividades_apagar) < 1 ){
	?>
    <tr>
    <td colspan="9">NADA PARA LISTAR</td>
    </tr><?	
}else{
	foreach($actividades_apagar as $value){
		?>
		<tr class="row" title="ID : <?=$value['IDSeccion']?>">
		<td align="center"><strong><?=$c?></strong></td>
		<td><?=$value['tipo_actividad']?></td>
		<td><?=$value['actividad']?> <strong><?=$value['solo_cuota']?></strong> 
        <a href="/caja/comentario/<?=$value['IDPostulacionItem']?>" class="comment popup_short" title="Esta actividad tiene <?=$value['comentarios']?> comentario(s)"><?=$value['comentarios']?> <img height="12" src="<?=base_url()?>assets/images/comment.png" align="absmiddle" /></a> 
		<?=($value['plazo'] == 1)?'<span class="vencida">vencida</span>':'';?>
        <a href="/caja/descuento/<?=$value['IDPostulacionItem']?>/<?=$value['IDSeccion']?>" class="descuento popup">descuentos</a>
        </td>
		<td align="center"><?=$value['FechaInicio']?></td>
		<td align="center"><?=$value['FechaTermino']?></td>
		<td ><?=$value['estado']?></td>
		<td align="right"><strong style="float:left;">$</strong><strong><?=number_format($value['ValorSeccion'],0,',','.')?></strong></td>
		<td align="right"><strong style="float:left;">$</strong><strong><?=number_format($value['ValorNuevo'],0,',','.')?></strong></td>
		<td align="center"><? 
		if($value['cupos'] <= 0){
			echo 'Sin cupos';
		}else{
			?>
            <input type="radio" name="a_pagar[]" onclick="submit()" value="<?=$value['IDPostulacionItem']?>" /><?
		}?></td>
		</tr><?
		$c++;
	}
}
?>
</table>
</form>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" >
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td ><strong>ACTIVIDADES POSTULANDO</strong> | Listando <strong><?=count($postulando)?></strong> registros</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="40">#</th>
<th width="80">Tipo</th>
<th>Actividad</th>
<th width="80">Inicio</th>
<th width="80">Termino</th>
<th width="100">Estado</th>
<th width="100">Valor</th>
<th width="100"></th>
</tr><?
$c = 1;
if(count($postulando) < 1 ){
	?>
    <tr bgcolor="#F4F4F4" >
    <td colspan="8">NADA PARA LISTAR</td>
    </tr><?	
}else{
	$a_pagar = array(3,12);
	foreach($postulando as $value){
		?>
		<tr class="row2">
		<td align="center"><strong><?=$c?></strong></td>
		<td><?=$value['tipo_actividad']?></td>
		<td><?=$value['actividad']?> <strong><?=$value['solo_cuota']?></strong> <a href="/caja/comentario/<?=$value['IDPostulacionItem']?>" class="comment popup_short" title="Esta actividad tiene <?=$value['comentarios']?> comentario(s)"><?=$value['comentarios']?> <img height="12" src="<?=base_url()?>assets/images/comment.png" align="absmiddle" /></a></td>
		<td align="center"><?=$value['FechaInicio']?></td>
		<td align="center"><?=$value['FechaTermino']?></td>
		<td ><?=$value['estado']?></td>
		<td align="right"><strong style="float:left;">$</strong><strong><?=number_format($value['ValorNuevo'],0,',','.')?></strong></td>
		<td align="center">-</td>
		</tr><?
		$c++;
	}
}?>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" >
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td ><strong>OTRAS ACTIVIDADES</strong> | Listando <strong><?=count($otras_actividades)?></strong> registros</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="40">#</th>
<th width="80">Tipo</th>
<th>Actividad</th>
<th width="80">Inicio</th>
<th width="80">Termino</th>
<th width="100">Estado</th>
<th width="100">Valor</th>
<th width="100" ></th>
</tr><?
$c = 1;
if(count($otras_actividades) < 1 ){
	?>
    <tr bgcolor="#F4F4F4" >
    <td colspan="8">NADA PARA LISTAR</td>
    </tr><?	
}else{
	$a_pagar = array(3,12,4);
	foreach($otras_actividades as $value){
		?>
		<tr class="row2" title="ID : <?=$value['IDSeccion']?>" >
		<td align="center"><strong><?=$c?></strong></td>
		<td><?=$value['tipo_actividad']?></td>
		<td><?=$value['actividad']?> <strong><?=$value['solo_cuota']?></strong> <a href="/caja/comentario/<?=$value['IDPostulacionItem']?>" class="comment popup_short" title="Esta actividad tiene <?=$value['comentarios']?> comentario(s)"><?=$value['comentarios']?> <img height="12" src="<?=base_url()?>assets/images/comment.png" align="absmiddle" /></a></td>
		<td align="center"><?=$value['FechaInicio']?></td>
		<td align="center"><?=$value['FechaTermino']?></td>
		<td ><?=$value['estado']?></td>
		<td align="right"><strong style="float:left;">$</strong><strong><?=number_format($value['ValorNuevo'],0,',','.')?></strong></td>
		<td align="center"><?
		if(in_array($value['bEstado'],$a_pagar)){
			?>
			<a href="/caja/detallepago/<?=$value['IDPostulacionItem']?>" class="popup blue ">detalle</a><?			
		}?></td>
		</tr><?
		$c++;
	}
}?>
</table>
</body>
</html>