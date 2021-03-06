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
<td class="titulo" >Informe WEBPAY</td>
</tr>
</table>
<form method="post" action="/webpay/informe">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
  <td colspan="2" height="20">Generar informe</td>
</tr>
<tr>
<td width="135" height="40" ><strong>Desde / Hasta</strong></td>
<td >
<input type="text" name="desde" id="desde" size="10" value="<?=set_value('desde',date('d/m/Y'))?>" readonly="readonly" class="center"  /> / 
<input type="text" name="hasta" id="hasta" size="10" value="<?=set_value('hasta',date('d/m/Y'))?>" readonly="readonly" class="center"  />

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
<th>Proyecto</th>
<th>Cod. Autorización</th>
<th>Ultimos digitos tarjeta</th>
<th>Fecha de transacción</th>
<th>Monto</th>
<th>Alumno</th>
<th >Rut</th>
<th>Programa</th>
<th>Curso / SP</th>
<th>IDPI</th>
<th>Boleta / Factura</th>
</tr><?
if(count($listado_webpay) < 1 ){
	?>
    <tr>
    <td colspan="11">NADA PARA LISTAR</td>
    </tr><?
}else{
	foreach($listado_webpay as $value){
		if($proyecto != $value['proyecto']){
			?>
			<tr bgcolor="#F4F4F4">   
			<td></td>     
			<td align="center" ><strong><?=$value['proyecto']?></strong></td>
			<td colspan="9"></td>
			</tr><?	
		}
		?>
		<tr class="row">
		<td align="center"><?=$value['proyecto']?></td>
		<td align="center"><?=$value['CodigoAutorizacion']?></td>
		<td align="center"><?=$value['UltimosDigitosTarjeta']?></td>
		<td align="center"><?=$value['FechaTransaccion']?></td>
		<td align="right"><?=number_format($value['Monto'],0,',','.')?></td> 
		<td align="left"><?=$value['NombreApellido']?></td>
		<td align="center" style="mso-number-format:'@'" ><?=$value['rut']?></td> 
		<td align="left"><?=$value['programa']?></td>
		<td align="left"><?=$value['Nombre_Curso']?></td>
		<td align="center"><?=$value['IDPostulacionItem']?></td>
		<td align="left"><?=$value['NroBoleta']?><br /><?=$value['NroFactura']?></td>       
		</tr><?
		$proyecto = $value['proyecto'];
	}
}?>
</table>
</body>
</html>
