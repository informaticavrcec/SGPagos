<table width="100%" border="1" cellpadding="3" cellspacing="0" align="center" class="tabla_result" >
<tr>
<th width="40">#</th>
<th width="80">Rut</th>
<th>Alumno</th>
<th>Programa</th>
<th>Actividad</th>
<th width="80">Cod. Secci�n</th>
<th width="80">Banco</th>
<th width="80">Serie</th>
<th width="80">Valor</th>
<th width="80">Fecha</th>
<th >Asistente</th>
<th width="80" >Factura</th>
</tr><?
$c = 1;
foreach($dia as $value){
	if($ultimo != $value['proyecto']){
		?>
        <tr class="row2">
        <td></td>
        <td align="center" ><?=$value['proyecto']?></td>
        <td colspan="10"></td>
        </tr><?
	}?>
    <tr class="row">
    <td align="center"><strong><?=$c?></strong></td>
    <td align="center"><?=$value['rut_alumno']?></td>
    <td><?=$value['nombre_alumno']?></td>
    <td><?=$value['programa']?></td>
    <td><?=$value['actividad']?></td>
    <td align="center"><?=$value['codigo']?></td>
    <td align="center"> <?=$value['banco']?></td>
    <td align="right"><?=$value['NSerie']?></td>
    <td align="right"><strong style="float:left">$</strong><strong><?=number_format($value['MontoFactura'],0,',','.')?></strong></td>
    <td align="center"><?=$value['Fecha']?></td>
    <td><?=$value['asistente']?></td>
    <td align="center"><?=$value['NroFactura']?></td>
    </tr><?
	$ultimo = $value['proyecto'];
	$suma += $value['MontoFactura'];
	if(!in_array(trim($value['NroFactura']),$ultima)){
		$total_documentos++; 	
	}
	$ultima[] = trim($value['NroFactura']);
	$c++;	
}?>
<tr>
<td colspan="6"></td>
<td align="right">&nbsp;</td>
<td align="right">&nbsp;</td>
<td align="right"><strong style="float:left">$</strong><strong><?=number_format($suma,0,',','.')?></strong></td>
<td colspan="3"></td>
</tr>
</table>

<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" >
<tr>
<td>&nbsp;</td>
</tr>
</table>
<table width="100%" border="1" cellpadding="3" cellspacing="0" align="center" class="tabla_result" >
<tr>
<th >Detalle</th>
<th width="80" >Totales</th>
</tr>
<tr class="row">
<td>Total recaudado</td>
<td align="right"><?=number_format($suma,0,',','.')?></td>
</tr>
<tr class="row">
<td>Total documentos</td>
<td align="right"><?=number_format($total_documentos,0,',','.')?></td>
</tr>
</table>