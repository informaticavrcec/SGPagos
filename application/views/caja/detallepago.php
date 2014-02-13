<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>assets/images/favicon.ico" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/general.css" />
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	$(".show_detail").click(function(){
		var id = $(this).attr("id");		
		$("tr.detalle_pago_" + id ).toggle('fast');		
	});
	
});
</script>
<title><?=$page_title?></title>
</head>

<body>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td class="titulo" >Detalle pago</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
    <td width="150"><strong>Rut</strong></td>
    <td><?=$meta_datos[0]['rut']?></td>
    </tr>
    <tr>
    <td><strong>Nombres / Apellidos</strong></td>
    <td><?=$meta_datos[0]['NombreApellido']?></td>
    </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" >
<tr>
<td>&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th>Actividad</th>
<th width="100">Valor final</th>
</tr>
<?
foreach($meta_datos as $valor){
	?>    
    <tr class="row" title="ID: <?=$valor['IDSeccion']?>">    
    <td><?=$valor['Nombre_Curso']?> <strong><?=$valor['solo_cuota']?></strong></td>
    <td align="right"><strong style="float:left">$</strong> <strong><?=number_format($valor['ValorNuevo'],0,',','.')?></strong></td>
    </tr><?
	$suma += $valor['ValorNuevo'];
}?>
<tr>
<td ><strong>Valor a recaudar</strong></td>
<td style="background-color:#FFC;" align="right"><strong style="float:left">$</strong><strong><?=number_format($suma,0,',','.')?></strong></td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" >
<tr>
<td>&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result" >
<?
foreach($boleta as $info){
	?>
    <tr class="row2">
    <td colspan="2"><strong>Boleta</strong></td>
    </tr>
    <tr >
    <td width="150" ><strong>Numero</strong></td>
    <td><?=$info['NroBoleta']?></td>
    </tr>
    <tr >
    <td><strong>Fecha</strong></td>
    <td><?=$info['fecha']?></td>
    </tr>
    <tr >
    <td><strong>Responsable</strong></td>
    <td><?=$info['NombreApellido']?></td>
    </tr>
	<tr>
    <td><strong>Monto</strong></td>
    <td><strong>$</strong> <strong><?=number_format($info['MontoBoleta'],0,',','.')?></strong></td>
    </tr><?
}?>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<?
foreach($factura as $info){
	?>
    <tr class="row2">
    <td colspan="2"><strong>Factura</strong></td>
    </tr>
    <tr >
    <td width="150"><strong>Numero</strong></td>
    <td><?=$info['NroFactura']?></td>
    </tr>
    <tr >
    <td><strong>Fecha</strong></td>
    <td><?=$info['fecha']?></td>
    </tr>
    <tr >
    <td><strong>Responsable</strong></td>
    <td><?=$info['NombreApellido']?></td>
    </tr>
	<tr>
    <td><strong>Monto</strong></td>
    <td><strong>$</strong> <strong><?=number_format($info['MontoFactura'],0,',','.')?></strong></td>
    </tr>
	<tr >
    <td><strong>Empresa</strong></td>
    <td><?=$info['RUT']?> <?=$info['RSocial']?></td>
    </tr><?
}

if(count($factura) > 0 OR count($boleta) > 0){
	?>
    </table>
    <table width="100%" border="0" cellpadding="3" cellspacing="0" align="center">
    <tr>
    <td>&nbsp;</td>
    </tr>
    </table><?
}?>
<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="40">#</th>
<th>Tipo pago</th>
<th width="100">Monto</th>
<th width="100">Creado</th>
<th width="250">Responsable</th>
</tr>
<?
if($tiene_pago == 's'){
	$c = 1;
	foreach($pagos_padre as $value){	
		?>
		<tr class="row_father" >
		<td align="center"><strong><?=$c?></strong></td>
		<td><strong><?=$value['nombre_pago']?></strong>  <a href="#" class="show_detail" id="<?=$value['IDTipoPago']?>"> detalle pago</a></td>
		<td align="right"><strong style="float:left">$</strong><strong><?=number_format($value['suma_total'],0,',','.')?></strong></td>         
		<td align="center"><?=$value['FechaCreacion']?></td>
		<td ><?=$value['creador']?></td>
		</tr>
		<?
		foreach($value['detalle'] as $valor ){
			?>
			<tr class="detalle_pago_<?=$value['IDTipoPago']?> hide" >
			<td align="center"><?=$valor['documento']?></td>
			<td><?=$valor['detalle']?></td>
			<td align="right"><strong style="float:left">$</strong><strong><?=number_format($valor['Monto'],0,',','.')?></strong></td>
			<td align="center"><?=$valor['creado']?></td>
			<td><?=$valor['creador']?></td>
			</tr><?
		}
		$c++;		
	}
}else{	
	?>	
    <tr>  
    <td colspan="6">NO EXISTEN PAGOS ASOCIADOS</td>    
    </tr><?
}?>
<tr>
<td></td>
<td ><strong>Total recaudado</strong></td>
<td align="right" style="background-color:#FFC;" ><strong style="float:left">$</strong><strong><?=number_format($totales,0,',','.')?></strong></td>
<td colspan="2"></td>
</tr>
</table>
</body>
</html>
