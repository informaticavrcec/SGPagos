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

<body id="body" onload="document.getElementById('rut').focus()">
<?=(validation_errors())?'<div class="alert">'.validation_errors('','').'<input type="button" id="close" value="Cerrar"></div>':''; ?>
<?=$menuusuario?>
<form method="post">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td colspan="2" class="titulo" >Administrador de pagos</td>
</tr>
<tr>
<td colspan="2">Listar actividades</td>
</tr>
<tr>
<td height="40" width="125"><strong>Rut</strong>  </td>
<td><input type="text" id="rut" class="center" name="rut" value="<?=set_value('rut')?>" size="11" /> <input type="submit" value="Buscar" /> | <?=$nombres?> <?=$apellidos?></td>
</tr>
</table>
</form>
<form method="post" action="/administrador/cancelar">
<input type="hidden" name="rut" value="<?=set_value('rut')?>" />
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="40">#</th>
<th width="80">Tipo</th>
<th>Actividad</th>
<th width="80">Inicio</th>
<th width="80">Termino</th>
<th width="100">Estado</th>
<th width="100">Valor</th>
<th width="100">Seleccion</th>
</tr><?
$c = 1;
if(count($actividades) < 1 ){
	?>
    <tr>
    <td colspan="8">NADA PARA LISTAR</td>
    </tr><?	
}else{
	foreach($actividades as $value){
		?>
		<tr class="row" title="ID : <?=$value['IDSeccion']?>">
		<td align="center"><strong><?=$c?></strong></td>
		<td><?=$value['tipo_actividad']?></td>
		<td><?=$value['actividad']?> <strong><?=$value['solo_cuota']?></strong> 
        <a href="/caja/comentario/<?=$value['IDPostulacionItem']?>" class="comment popup_short" title="Esta actividad tiene <?=$value['comentarios']?> comentario(s)"><?=$value['comentarios']?> <img height="12" src="<?=base_url()?>assets/images/comment.png" align="absmiddle" /></a> 
		<?=($value['plazo'] == 1)?'<span class="vencida">vencida</span>':'';?>
        <?=($value['cupos'] <= 0)?'<span class="sincupos">sin cupos</span>':'';?>
        
        </td>
		<td align="center"><?=$value['FechaInicio']?></td>
		<td align="center"><?=$value['FechaTermino']?></td>
		<td ><?=$value['estado']?></td>
		<td align="right"><strong style="float:left;">$</strong><strong><?=number_format($value['ValorNuevo'],0,',','.')?></strong></td>
		<td align="center"><input type="radio" name="a_pagar[]" onclick="submit()" value="<?=$value['IDPostulacionItem']?>" /></td>
		</tr><?
		$c++;
	}
}
?>
</table>
</form>
</body>
</html>
