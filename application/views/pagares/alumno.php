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
$(document).ready(function() {
    $("a.popup_new").colorbox({
		iframe:true,
		width : 500,
		height : 350,
		onClosed : function(){
			$("#form").submit();	
		}
	});
});
</script>

<title>
<?=$page_title?>
</title>
</head>

<body id="body" onload="document.getElementById('rut').focus()">
<?=$menuusuario?>
<?=($error)?'<div class="alert left">'.$error.'<input type="button" id="close" value="Cerrar"></div>':''; ?>
<?=(validation_errors())?'<div class="alert left">Tiene los siguientes errores:<br><ul>'.validation_errors('<li class="left">','</li>').'</li><input type="button" id="close" value="Cerrar"></div>':''; ?>
<form method="post" action="/pagare/poralumno" id="form">
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td colspan="2" class="titulo" >Registrar Pago Pagaré</td>
</tr>
<tr>
<td colspan="2">Listar pagos</td>
</tr>
<tr>
<td height="40" width="120"><strong>Rut</strong>  </td>
<td><input type="text" id="rut" name="rut" value="<?=set_value('rut')?>" class="center" size="11" /> <input type="submit" value="Buscar" /> | <?=$nombres?> <?=$apellidos?></td>
</tr>
</table>
</form>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="40">#</th>
<th width="150">Numero pagare</th>
<th width="80" >&nbsp;</th>
<th >Actividad</th>
<th width="80">Inicio</th>
<th width="80">Termino</th>
<th width="80">Estado</th>
<th width="80">Vencimiento</th>
<th width="80">Valor cuota</th>
<th width="80">Documento</th>
</tr>
<?
if(count($pagare) <  1){
	?>
    <tr>
    <td colspan="10">NADA PARA LISTAR</td>
    </tr><?
}else{
	$c = 1;
	foreach($pagare as $value){
		?>
		<tr class="row">
		<td align="center"><strong><?=$c?></strong></td>
		<td align="center"><?=$value['NumPagare']?></td>
		<td  align="center"><?=$value['nCuota']?> / <?=$value['NumCuotas']?></td>
		<td ><?=$value['Nombre_Curso']?></td>
		<td align="center"><?=$value['FechaInicio']?></td>
		<td align="center"><?=$value['FechaTermino']?></td>
		<td ><?=$value['estado']?></td>
		<td align="center"><?=$value['FechaVencimiento']?></td>
		<td align="right"><strong style="float:left">$</strong><strong><?=number_format($value['MontoCuota'],0,',','.')?></strong></td>
		<td align="center"><a href="/pagare/asignarboleta/<?=$value['IDPostulacionItem']?>/<?=$value['IDPagoPagare']?>" class="popup_new blue"><?=($value['nroboleta'])?($value['nroboleta']):'pagar';?></a>
        </td>
		</tr>
		<?
		$c++;
	}
}?>	
</table>
</body>
</html>
