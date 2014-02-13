<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th width="60">No. bol</th>
<th width="80">Monto</th>
<th width="60">Fecha bol.</th>
<th width="60">Unidad</th>
<th width="60">Proyecto</th>
<th>Nombre actividad</th>
<th >Paterno materno</th>
<th>Nombre 1 nombre 2</th>
<th>Rut</th>
<th>Periodo curso</th>
<th>Forma de pago</th>
<th>Matriculador</th>
</tr><?
if(count($boletas) < 1 ){
	?>
    <tr>
    <td colspan="12">NADA PARA LISTAR</td>
    </tr><?
}else{
	foreach($boletas as $value){
		
		$partes = explode('-',$value['proyecto']);
		$partes2 = explode(',',$value['alumno']);
		?>		
		<tr class="row">
		<td align="center"><?=$value['NroBoleta']?></td>
		<td align="right"><?=number_format($value['MontoBoleta'],0,',','.')?></td>
		<td align="center"><?=$value['Fecha']?></td>
        <td align="center"><?=$partes[0]?></td>
        <td align="center"><?=$partes[1]?></td>
        <td ><?=$value['actividad']?></td>
        <td ><?=$partes2[0]?></td>
        <td ><?=$partes2[1]?></td>
        <td align="center"><?=$value['rut']?></td>
        <td align="center"><?=$value['FechaInicio']?>/<?=$value['FechaTermino']?></td>
        <td><?=$value['formaspago']?></td>
        <td ><?=$value['matriculador']?></td>
		<?		
	}
}?>
</table>
</body>
</html>
