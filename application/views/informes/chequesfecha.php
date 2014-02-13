<table width="100%" border="0" cellpadding="3" cellspacing="0" align="center" class="tabla_result">
<tr>
<th >Serie cheque</th>
<th width="80">Vencimiento</th>
<th width="60">Monto</th>
<th width="30">Rut</th>
<th width="30">Unidad</th>
<th>Girador</th>
<th >Rut girador</th>
<th>Telefono</th>
<th>Banco</th>
<th>Unidad</th>
<th>Proyecto</th>
<th width="30">Caja</th>
<th width="60">Boleta</th>
<th width="60">F. carga</th>
</tr>
<?
if(count($fecha) < 1 ){
	?>
    <tr>
    <td colspan="11">NADA PARA LISTAR</td>
    </tr><?
}else{
	foreach($fecha as $value){
		
		$partes = explode('-',$value['proyecto']);
		$partes2 = explode(',',$value['alumno']);
		?>		
		<tr class="row">
		<td align="center"><?=$value['NSerie']?></td>
		<td align="center"><?=$value['vcto']?> </td>
		<td align="right"><?=number_format($value['MontoBoleta'],0,',','.')?></td>
        <td align="center" >5-1</td>
        <td align="center" >0001</td>
        <td ><?=$value['Nombre_Girador']?></td>
        <td align="right" ><?=$value['rut_girador']?></td>
        <td align="center" ><?=$value['telefono_girador']?></td>
        <td align="center"><?=$value['banco']?></td>
        <td align="center"><?=$partes[0]?></td>
        <td align="center"><?=$partes[1]?></td>
        <td align="center" >81</td>
        <td align="center" ><?=$value['NroBoleta']?></td>
        <td  align="center"><?=date('d-m-Y')?></td>
		<?		
	}
}?>
</table>
</body>
</html>
