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
<script type="text/javascript">
$(document).ready(function() {
    $("a.popup_new").colorbox({
		iframe:true,
		width : 600,
		height : '90%',
		onClosed : function(){
			$("#form").submit();	
		}
	});
	
	$("input:text:first").select();
});
</script>
<title>
<?=$page_title?>
</title>
</head>

<body id="body" >
<?=$menuusuario?>
<?=validation_errors('<div class="alert">','<input type="button" id="close" value="Cerrar"></div>'); ?>
<?=($flash_error)?'<div class="alert" >'.$flash_error.'<input type="button" id="close" value="Cerrar"></div>':'';?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td colspan="2" class="titulo">Detalle Solicitud de Factura</td>
</tr>
<tr>
<td colspan="2">Detalle pago</td>
</tr>
</table>
<form method="post">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td width="125" height="40"><strong>Rut alumno</strong></td>
<td><input type="text" name="rut" size="11" value="<?=set_value('rut')?>" class="center" /> <input type="submit" value="Buscar" /> | <?=$nombres?> <?=$apellidos?></td>
</tr>
</table>
</form>
<form method="post" action="/caja/cancelar">
<input type="hidden" name="rut" value="<?=set_value('rut')?>" />
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="40">#</th>
<th width="80">Tipo</th>
<th>Actividad</th>
<th width="80">Inicio</th>
<th width="80">Termino</th>
<th width="100">Estado</th>
<th width="80">Valor</th>
<th width="80">A facturar</th>
<th width="80">&nbsp;</th>
<th width="80">Seleccion</th>
</tr><?
$c = 1;
if(count($actividades_apagar) < 1 ){
	?>
    <tr>
    <td colspan="10">NADA PARA LISTAR</td>
    </tr><?	
}else{
	foreach($actividades_apagar as $value){
		?>
		<tr class="row" title="ID : <?=$value['IDSeccion']?>">
		<td align="center"><strong><?=$c?></strong></td>
		<td><?=$value['tipo_actividad']?></td>
		<td><strong><?=$value['actividad']?></strong> /
        <?=($value['rut_otic'])?'OTIC : '.$value['rut_otic'].'<br />'.$value['razon_otic']:'';?> 
		<?=($value['rut_empresa'])?'Empresa : '.$value['rut_empresa'].'<br />'.$value['razon_empresa']:'';?> <strong><?=$value['solo_cuota']?></strong> <a href="/caja/comentario/<?=$value['IDPostulacionItem']?>" class="comment popup_short"><?=$value['comentarios']?> comentario(s)</a></td>
		<td align="center"><?=$value['FechaInicio']?></td>
		<td align="center"><?=$value['FechaTermino']?></td>
		<td ><?=$value['estado']?></td>
		<td align="right"><strong style="float:left;">$</strong><strong><?=number_format($value['ValorNuevo'],0,',','.')?></strong></td>
		<td align="right"><strong style="float:left;">$</strong><strong><?=number_format($value['Monto'],0,',','.')?></strong></td>
		<td align="center"><a href="/caja/pago/3811/<?=base64_encode($value['IDPostulacionItem'])?>" class="popup_new detalle_pago">Detalle pago</a></td>
		<td align="center"></td>
		</tr><?
		$c++;
	}
}
?>
</table>
</form>
</body>
</html>
