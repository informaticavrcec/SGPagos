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
<script type="text/javascript" src="<?=base_url()?>assets/js/datepicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/caja.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	$('#desde').DatePicker({
		format:'d/m/Y',
		date: $('#desde').val(),
		current: $('#desde').val(),
		starts: 1,
		position: 'right',
		onBeforeShow: function(){
			$('#desde').DatePickerSetDate($('#desde').val(), true);
		},
		onChange: function(formated, dates){
			$('#desde').val(formated);			
		}
	});
	
	$('#hasta').DatePicker({
		format:'d/m/Y',
		date: $('#hasta').val(),
		current: $('#hasta').val(),
		starts: 1,
		position: 'right',
		onBeforeShow: function(){
			$('#hasta').DatePickerSetDate($('#hasta').val(), true);
		},
		onChange: function(formated, dates){
			$('#hasta').val(formated);			
		}
	});
	
});
</script>
<title>
<?=$page_title?>
</title>
</head>

<body id="body">
<?=$menuusuario?>
<?=validation_errors('<div class="alert">','<input type="button" id="close" value="Cerrar"></div>'); ?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td class="titulo" >Informe Pagaré</td>
</tr>
</table>
<form method="post" action="/pagare/informe">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
  <td colspan="2" height="20">Generar informe</td>
</tr>
<tr>
<td width="135" height="40" ><strong>Fecha inicio sección</strong></td>
<td >
<input type="text" name="desde" id="desde" size="10" value="<?=set_value('desde',date('d/m/Y'))?>" readonly="readonly" class="center"  /> / 
<input type="text" name="hasta" id="hasta" size="10" value="<?=set_value('hasta',date('d/m/Y'))?>" readonly="readonly" class="center"  />
<select name="estado">
<optgroup label="Estados">
<option value="1">Impagos</option>
<option value="2">Pagados</option>
</optgroup>
</select>
<input type="submit" value="Generar reporte" /> <a class="btn_excel" href="/webpay/informeexcel/<?=str_replace('/','-',set_value('desde',date('d/m/Y')))?>/<?=str_replace('/','-',set_value('hasta',date('d/m/Y')))?>">Exportar EXCEL</a>
</td>
</tr>
<tr>
<td height="20" colspan="2">Listando <strong><?=count($listado_webpay)?></strong> registros</td>
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